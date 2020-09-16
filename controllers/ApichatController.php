<?php

namespace app\controllers;

use app\models\Devices;
use paragraph1\phpFCM\Recipient\Device;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Transaction;
use yii\debug\models\search\User;
use yii\swiftmailer\Mailer;
use yii\web\NotFoundHttpException;
use Da\QrCode\QrCode;
use Codeception\Events;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use app\models\Users;
use app\models\Merchants;
use app\models\Chats;
use yii\filters\auth\HttpBasicAuth;
use yii\web\UploadedFile;
use yii\helpers\Url;
//use paragraph1\phpFCM\Recipient\Device;
class ApichatController extends ActiveController
{
    private $userId = null;
    private $user_id;
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
        if($_SERVER['HTTP_HOST'] != 'tls.test') {
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
                        $this->user_id = $userdata->id;
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
            $user_id = $this->user_id;
            $query = Chats::find()
                ->orderBy([
                    'rumah_chats.created_at' => SORT_DESC
                ])
                ->joinWith([
                    'sender'=>function($q) use ($baseurl){
                        return $q->select(['id','full_name as name','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                    },
                    'receiver'=>function($q1) use ($baseurl){
                        return $q1->select(['id','full_name as name','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                    }
                ]);
            $query->where(['user_id'=>$user_id])->orWhere(['receiver_id'=>$user_id])
                ->where('rumah_chats.id in (select MAX(tc.id) from rumah_chats as tc where tc.user_id = '.$user_id.' or tc.receiver_id = '.$user_id.')');


            if(isset($_POST['offset'])){
                $query->offset($_POST['offset']);
            }

            $query->limit(20)->all();
            echo $query->createCommand()->getRawSql();exit;


            return array('status' => 1, 'data' => $data);
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
                $model->user_id = $this->user_id;
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
                                $q->select(['id','full_name as name','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                            }])
                            ->joinWith(['receiver'=>function($q) use ($baseurl){
                                $q->select(['id','full_name as name','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                            }])->where(['rumah_users.id'=>$model->id])->asArray()->one();
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
            $user_id = $this->user_id;

            if(!isset($_POST['user_id'])){
                echo json_encode(array('status' => 0, 'message' => 'User id is required'));exit;
            }

            $query = Chats::find()
                ->orderBy([
                    'rumah_chats.id' => SORT_DESC
                ])
                ->joinWith(['sender'=>function($q) use ($baseurl){
                    $q->select(['id','full_name as name','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                }])
                ->joinWith(['receiver'=>function($q) use ($baseurl){
                    $q->select(['id','full_name as name','case when rumah_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",rumah_users.image) else "" end as image']);
                }]);

            if($user_id != null){
                $query->where(['user_id'=>$user_id])->andWhere(['receiver_id'=>$_POST['user_id']]);

            }



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

            return array('status' => 1, 'data' => $data);
        }
    }
}
