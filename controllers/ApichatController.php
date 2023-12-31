<?php

namespace app\controllers;

use app\models\BookingRequests;
use app\models\Properties;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Transaction;
use yii\debug\models\search\User;
use yii\swiftmailer\Mailer;
use yii\web\NotFoundHttpException;
use Codeception\Events;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use app\models\Users;
use app\models\Chats;
use yii\filters\auth\HttpBasicAuth;
use yii\web\UploadedFile;
use yii\helpers\Url;
//use paragraph1\phpFCM\Recipient\Device;
class ApichatController extends ActiveController
{
    private $userId = null;
    private $merchantId = null;
    public $baseurl = null;
    public $modelClass = 'app\models\Users';
    private $language = 1;

    public static function allowedDomains()
    {
        return [
            '*',
            // star allows all domains
            // 'http://localhost:3000',
            // 'http://test2.example.com',
        ];
    }
    public function init()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        if($_SERVER['HTTP_HOST'] != 'rumah.test') {
            $this->baseurl = Url::base('https');
        }else{
            $this->baseurl = Url::base(true);
        }
        parent::init(); // TODO: Change the autogenerated stub
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ],

        ];
    }
    public function beforeAction($action)
    {
        header('Access-Control-Allow-Origin: *');

        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        header("Access-Control-Allow-Headers: X-Requested-With,token,user");
        parent::beforeAction($action);

        if ($action->actionMethod != 'actionLogin' && $action->actionMethod != 'actionRegister' && $action->actionMethod!='actionForgotpassword' && $action->actionMethod!='actionAddrefferal') {
            $headers = Yii::$app->request->headers;
            if(!empty($headers) && isset($headers['token']) && $headers['token']!=''){
                try{
                    $token = Yii::$app->jwt->getParser()->parse((string) $headers['token']);
                    $data = Yii::$app->jwt->getValidationData(); // It will use the current time to validate (iat, nbf and exp)
                    $data->setIssuer(\Yii::$app->params[ 'hostInfo' ]);
                    $data->setAudience(\Yii::$app->params[ 'hostInfo' ]);
                    $data->setId('4f1g23a12aa');
                    // $data->setCurrentTime(time() + 61);
                    if($token->validate($data)){
                        $userdata = $token->getClaim('uid');
                        $this->userId = $userdata->id;
                        return true;


                    }else{
                        echo json_encode(array('status' => 0, 'message' => 'Authentication Failed.'));exit;

                    }
                }catch (Exception $e) {
                    echo json_encode(array('status' => 0, 'message' => 'Authentication Failed.'));exit;

                }

                //var_dump($token->validate($data));exit;

                //return true;
            }else{

                echo json_encode(array('status' => 0, 'message' => 'Authentication Failed.'));exit;
            }
            //exit;
        }
        return true;


    }


    public function actionGetchatlist()
    {
        $baseurl = $this->baseurl;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            return array('status' => 0, 'message' => 'Bad request.');
        } else {

            $query = Chats::find()->select(['case when (sender_id = '.$this->userId.') then receiver_id else sender_id end as opponent_id','MAX(rumah_chats.id) as chat_id','msg','msg_type',
                '(select max(rc2.created_at) from rumah_chats as rc2 where (rc2.sender_id = rumah_chats.sender_id and rc2.receiver_id = rumah_chats.receiver_id) or (rc2.sender_id = rumah_chats.receiver_id and rc2.receiver_id = rumah_chats.sender_id)) as created_at','rumah_chats.property_id','rumah_chats.sender_id','rumah_chats.receiver_id'])
                ->orderBy([
                    'created_at' => SORT_DESC
                ])
                ->joinWith(['sender'=>function($q) use ($baseurl){
                    $q->select(['id','full_name','role','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                }])
                ->joinWith(['receiver'=>function($q) use ($baseurl){
                    $q->select(['id','full_name','role','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                }])
                ->joinWith(['property'=>function($q) use ($baseurl){
                    $q->select(['id','property_no','title','user_id']);
                }]);

            if($this->userId != null){
                $query->where('(sender_id = '.$this->userId.' OR receiver_id = '.$this->userId.')');


            }

            $query->groupBy(['property_id','opponent_id']);

            if(isset($_POST['offset'])){
                $query->offset($_POST['offset']);
            }

           //echo  $query->createCommand()->getRawSql();exit;

            $messages = $query->asArray()->all();
            if(!empty($messages)){
                foreach ($messages as $key=>$message){
                    $msgdetails = Chats::findOne($message['chat_id']);
                    $messages[$key]['created_at'] = $msgdetails->created_at;
                }
            }
           // echo "<pre>";print_r($messages);exit;
            return array('status' => 1, 'data' => $messages);
        }
    }

    public function actionSendchatmsgs()
    {
        $baseurl = $this->baseurl;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            return array('status' => 0, 'message' => 'Bad request.');
        } else {
            if(!empty($_POST)){
                $model = new Chats();
                $model->attributes = Yii::$app->request->post();
                if($_POST['msg_type'] == 'image') {
                    try{
                        $filename = uniqid();
                        $data = Yii::$app->common->processBase64($_POST['msg']);
                        file_put_contents('uploads/chat/'.$filename.'.'.$data['type'], $data['data']);
                        $model->msg = $baseurl.'/uploads/chat/'.$filename . '.' . $data['type'];
                    }catch (Exception $e) {
                        return array('status' => 0, 'message' => $e);
                    }
                }
                if ($model->validate()) {
                    $model->created_at =date('Y-m-d H:i:s');
                    if($model->save(false)){
                        //print_r($model->merchant);exit;

                        $lastmessage = Chats::find()
                            ->orderBy([
                                'rumah_chats.id' => SORT_DESC
                            ])
                            ->joinWith(['sender'=>function($q) use ($baseurl){
                                $q->select(['id','full_name','role','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                            }])
                            ->joinWith(['receiver'=>function($q) use ($baseurl){
                                $q->select(['id','full_name','role','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                            }])->where(['rumah_chats.id'=>$model->id])->asArray()->one();
                        $receiver = Users::findOne($model->receiver_id);
                        $sender = Users::findOne($model->sender_id);
                        $subject = 'New Message';
                        $textmessage = $sender->full_name.' just text you, check Rumah-i inbox';
                        if($receiver->role=='User'){

                            //Yii::$app->common->Savenotification($model->receiver_id,$subject,$textmessage,'',$model->property_id);

                            Yii::$app->common->Sendpushnotification($model->receiver_id,$subject,$textmessage,'User','','','','chat');

                        }else{

                            //Yii::$app->common->Savenotification($model->receiver_id,$subject,$textmessage,'',$model->property_id);

                            Yii::$app->common->Sendpushnotification($model->receiver_id,$subject,$textmessage,'Partner','','','','chat');
                        }

                        return array('status' => 1, 'message' => 'You have added chat msg successfully.','data'=>$lastmessage);
                    }else{
                        return array('status' => 0, 'message' => $model->getErrors());
                    }

                } else {
                    return array('status' => 0, 'message' => $model->getErrors());
                }
            } else {
                return array('status' => 0, 'message' => 'Please enter mandatory fields.');
            }
        }
    }

    public function actionGetchatmsgs()
    {
        $baseurl = $this->baseurl;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            return array('status' => 0, 'message' => 'Bad request.');
        } else {
            if(!isset($_POST['sender_id']) || $_POST['sender_id']==''){
                echo json_encode(array('status' => 0, 'message' => 'Sender id is required'));exit;
            }

            if(!isset($_POST['receiver_id']) || $_POST['receiver_id']==''){
                echo json_encode(array('status' => 0, 'message' => 'Receiver id is required'));exit;
            }

            if(!isset($_POST['property_id']) || $_POST['property_id']==''){
                echo json_encode(array('status' => 0, 'message' => 'Property id is required'));exit;
            }
            $query = Chats::find()->select(['rumah_chats.id','msg','msg_type','rumah_chats.created_at','rumah_chats.property_id','rumah_chats.sender_id','rumah_chats.receiver_id'])
                ->orderBy([
                    'rumah_chats.id' => SORT_DESC
                ])
                ->joinWith(['sender'=>function($q) use ($baseurl){
                    $q->select(['id','full_name','role','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                }])
                ->joinWith(['receiver'=>function($q) use ($baseurl){
                    $q->select(['id','full_name','role','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                }])->joinWith(['property'=>function($q) use ($baseurl){
                    $q->select(['id','property_no','title','user_id']);
                }]);

            //if($_POST['sender_id'] != null){
//                $query->where(['or',
//                    ['sender_id'=>$_POST['sender_id']],
//                    ['receiver_id'=>$_POST['receiver_id']]
//                ])->orWhere(['or',
//                    ['sender_id'=>$_POST['receiver_id']],
//                    ['receiver_id'=>$_POST['sender_id']]
//                ])
                 $sender_id = $_POST['sender_id'];
                 $receiver_id = $_POST['receiver_id'];
                 $query->where('(sender_id = '.$sender_id.' AND receiver_id = '.$receiver_id.') OR (sender_id = '.$receiver_id.' AND receiver_id = '.$sender_id.')');
                 $query->andWhere(['property_id'=>$_POST['property_id']]);
           // }



            if(isset($_POST['last_msg_at'])){
                $query->andWhere(['>=','rumah_chats.created_at',$_POST['last_msg_at']]);
            }

            if(isset($_POST['offset']) && !isset($_POST['last_msg_at'])){
                $query->offset($_POST['offset']);
            }

            if(!isset($_POST['last_msg_at'])){
                $query->limit(20);
            }

            $data = $query->asArray()->all();
            $property_id = $_POST['property_id'];
            $propertymodel = Properties::findOne($property_id);

            return array('status' => 1, 'data' => $data);
        }
    }
    public function actionDeletechat()
    {
        $baseurl = $this->baseurl;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            return array('status' => 0, 'message' => 'Bad request.');
        } else {
            if(!isset($_POST['sender_id']) || $_POST['sender_id']==''){
                echo json_encode(array('status' => 0, 'message' => 'Sender id is required'));exit;
            }



            if(!isset($_POST['property_id']) || $_POST['property_id']==''){
                echo json_encode(array('status' => 0, 'message' => 'Property id is required'));exit;
            }


            $sender_id = $this->userId;
            $receiver_id = $_POST['receiver_id'];
            $property_id = $_POST['property_id'];
            $delete = Yii::$app->db->createCommand("
    DELETE FROM rumah_chats 
    WHERE ((sender_id = '$sender_id' AND receiver_id = '$receiver_id') OR (sender_id = '$receiver_id' AND receiver_id = '$sender_id')) AND property_id = '$property_id'
")->execute();
           if($delete){
               return array('status' => 1, 'message' => 'You have deleted chat successfully.');

           }else{
               return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

           }
        }
    }

}