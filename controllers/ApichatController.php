<?php

namespace app\controllers;

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
                $userid = Yii::$app->common->decrypt($headers['token']);
                $userexist = Users::findOne([
                    'id' => $userid
                ]);
                if (!empty($userexist)) {
                    $this->userId = $userid;
                    return true;
                }else {
                    echo json_encode(array('status' => 0, 'message' => 'Authentication Failed.'));
                    exit;

                }
            } else {
                echo json_encode(array('status' => 0, 'message' => 'Authentication Failed.'));exit;
            }
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
            $query = Chats::find()
                ->orderBy([
                    'tls_chats.created_at' => SORT_DESC
                ])
                ->joinWith([
                    'merchant'=>function($q) use ($baseurl){
                        return $q->select(['id','business_title as name','case when tls_merchants.image != "" then CONCAT("'.$baseurl.'/uploads/merchants/",tls_merchants.image) else "" end as image']);
                    },
                    'user'=>function($q1) use ($baseurl){
                        return $q1->select(['id','CONCAT(first_name," ",last_name) as name','case when tls_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",tls_users.image) else "" end as image']);
                    }
                ]);

            if($this->userId != null){
                $query->where(['user_id'=>$this->userId])
                    ->where('tls_chats.id in (select MAX(tc.id) from tls_chats as tc where tc.user_id = '.$this->userId.' and tc.merchant_id = tls_chats.merchant_id)');
            }

            if($this->merchantId != null){
                $query->where(['merchant_id'=>$this->userId])
                    ->where('tls_chats.id in (select MAX(tc.id) from tls_chats as tc where tc.merchant_id = '.$this->merchantId.' and tc.user_id = tls_chats.user_id)');;
            }

            if(isset($_POST['offset'])){
                $query->offset($_POST['offset']);
            }

            $data = $query->limit(20)->asArray()->all();

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
                        if($model->send_by=='customer'){
                            $devices = Devices::find()->where(['merchant_id'=>$model->merchant_id])->all();
                            if(!empty($devices)) {
                                $note = Yii::$app->fcm2->createNotification($model->user->first_name . " " . $model->user->last_name, ($_POST['msg_type'] == 'image') ? 'Sent an Image' : $model->msg);
                                $note->setSound('default')
                                    ->setClickAction('FCM_PLUGIN_ACTIVITY')
                                    ->setColor('#ffffff');

                                $message = Yii::$app->fcm2->createMessage();

                                foreach ($devices as $device) {
                                    $message->addRecipient(new Device($device->device_token));
                                }

                                $message->setNotification($note)
                                    ->setData([
                                        'notification_type' => 'chat',
                                        'title' => $model->user->first_name . " " . $model->user->last_name,
                                        'body' => ($_POST['msg_type'] == 'image') ? 'Sent an Image' : $model->msg,
                                    ]);

                                $response = Yii::$app->fcm2->send($message);
                            }
                        }elseif ($model->send_by=='merchant'){
                            $devices = Devices::find()->where(['user_id'=>$model->user_id])->all();
                            if(!empty($devices)) {
                                $note = Yii::$app->fcm1->createNotification($model->merchant->business_title, ($_POST['msg_type'] == 'image') ? 'Sent an Image' : $model->msg);
                                $note->setSound('default')
                                    ->setClickAction('FCM_PLUGIN_ACTIVITY')
                                    ->setColor('#ffffff');

                                $message = Yii::$app->fcm1->createMessage();

                                foreach ($devices as $device) {
                                    $message->addRecipient(new Device($device->device_token));
                                }

                                $message->setNotification($note)
                                    ->setData([
                                        'notification_type' => 'chat',
                                        'title' => $model->merchant->business_title,
                                        'body' => ($_POST['msg_type'] == 'image') ? 'Sent an Image' : $model->msg
                                    ]);

                                $response = Yii::$app->fcm1->send($message);
                            }
                        }
                        $lastmessage = Chats::find()
                            ->orderBy([
                                'tls_chats.id' => SORT_DESC
                            ])
                            ->joinWith(['merchant'=>function($q) use ($baseurl){
                                $q->select(['id','business_title as name','case when tls_merchants.image != "" then CONCAT("'.$baseurl.'/uploads/merchants/",tls_merchants.image) else "" end as image']);
                            }])
                            ->joinWith(['user'=>function($q) use ($baseurl){
                                $q->select(['id','CONCAT(first_name," ",last_name) as name','case when tls_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",tls_users.image) else "" end as image']);
                            }])->where(['tls_chats.id'=>$model->id])->asArray()->one();
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
            if($this->userId != null && !isset($_POST['merchant_id'])){
                echo json_encode(array('status' => 0, 'message' => 'Merchant id is required'));exit;
            }

            if($this->merchantId != null && !isset($_POST['user_id'])){
                echo json_encode(array('status' => 0, 'message' => 'User id is required'));exit;
            }

            $query = Chats::find()
                ->orderBy([
                    'tls_chats.id' => SORT_DESC
                ])
                ->joinWith(['merchant'=>function($q) use ($baseurl){
                    $q->select(['id','business_title as name','case when tls_merchants.image != "" then CONCAT("'.$baseurl.'/uploads/merchants/",tls_merchants.image) else "" end as image']);
                }])
                ->joinWith(['user'=>function($q) use ($baseurl){
                    $q->select(['id','CONCAT(first_name," ",last_name) as name','case when tls_users.image != "" then CONCAT("'.$baseurl.'/uploads/users/",tls_users.image) else "" end as image']);
                }]);

            if($this->userId != null){
                $query->where(['user_id'=>$this->userId])->andWhere(['merchant_id'=>$_POST['merchant_id']]);
            }

            if($this->merchantId != null){
                $query->where(['merchant_id'=>$this->merchantId])->andWhere(['user_id'=>$_POST['user_id']]);
            }

            if(isset($_POST['last_msg_at'])){
                $query->andWhere(['>=','tls_chats.created_at',$_POST['last_msg_at']]);
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