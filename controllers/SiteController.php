<?php

namespace app\controllers;

use app\models\BookingRequests;
use app\models\Cronjobs;
use app\models\Msc;
use app\models\Packages;
use app\models\Payments;
use app\models\Properties;
use app\models\PropertyViews;
use app\models\RenovationQuotes;
use app\models\TodoList;
use app\models\Topups;
use app\models\Transactions;
use app\models\TransactionsItems;
use app\models\UserPackages;
use app\models\Users;
use kartik\mpdf\Pdf;
use phpDocumentor\Reflection\Types\Null_;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','logout'],
                'rules' => [
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {  date_default_timezone_set("Asia/Kuala_Lumpur");
//        $send = Yii::$app->mailer->compose()
//            ->setFrom('noreply@rumah-i.com.my')
//            ->setTo('ketansangani12@gmail.com')
//            ->setSubject('Rumah Test')
//            ->setHtmlBody('<h1>This is test email from new smtp.</h1>')
//            ->send();
//        var_dump($send);exit;
//        $handle = fopen("file.dif", "w");
//        fwrite($handle, "FH00013997567999PBB       20161125XXXDEBIT            20150708
//DT112371039999        0000000000075000MUHAMMAD HASWAN BIN XXXXX               03022015P00191      000000000075397                    MBB0227   03022015P00191080715
//DT112157089999        0000000000160000NIK FATIHAH BINTI XXXXXX                08052015P00216      000000000160878                    MBB0227   08052015P00216080715
//FT00013997567999PBB       000000000400000000000000235000000000000001275");
//        fclose($handle);
//
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename='.basename('file.dif'));
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        header('Content-Length: ' . filesize('file.dif'));
//        readfile('file.dif');
//        exit;
        $start_date = date('Y-m-d H:i:s', strtotime('first day of january this year'));
        $last_date = date('Y-m-d 23:59:00', strtotime('last day of december this year'));
        $current_date = date("Y-m-d");
        /*upper part*/
        /*$active_user = Users::find()->where(['status' => '1'])->count();*/
        $active_user = Users::find()->where(['role' => 'User'])->count();
        $active_property_agents = Users::find()->where(['status' => '1','role'=>'Agent'])->count();
        $active_listing = Properties::find()->where(['status' => 'Active'])->count();
        $managed_listing = Properties::find()->where(['is_managed' => '1'])->count();
        $active_listing_viewed = PropertyViews::find()
            ->joinWith(['property p'])
            ->where(['in','p.status', ['Active','Rented']])
            ->andWhere('rumah_property_views.created_at >= :created_at', [':created_at' => $start_date])
            ->andWhere('rumah_property_views.created_at <= :endcreated_at', [':endcreated_at' => $last_date])
            ->groupBy('property_id')
            ->count();

        $purchased_listing_package_active = UserPackages::find()
            ->where('end_date >= :enddate', [':enddate' => $current_date])
            ->groupBy('user_id')
            ->count();

        $purchased_tenancy_service_active = Properties::find()
            ->where(['digital_tenancy' => '1','status'=>'Active'])
            ->andWhere('created_at >= :created_at', [':created_at' => $start_date])
            ->andWhere('created_at <= :endcreated_at', [':endcreated_at' => $last_date])
            ->count();
        $subscribed_auto_rental_collection_service_active = Properties::find()
            ->where(['auto_rental' => '1','status'=>'Active'])
            ->andWhere('created_at >= :created_at', [':created_at' => $start_date])
            ->andWhere('created_at <= :endcreated_at', [':endcreated_at' => $last_date])
            ->count();
        $purchased_insurance_policy_active = Properties::find()
            ->where(['insurance' => '1','status'=>'Active'])
            ->andWhere('created_at >= :created_at', [':created_at' => $start_date])
            ->andWhere('created_at <= :endcreated_at', [':endcreated_at' => $last_date])
            ->count();
        /*bottom part*/
        $purchased_tenancy_service = Properties::find()
            ->where(['digital_tenancy' => '1'])
            ->count();
        $promo_code_used = Transactions::find()
            ->where(['!=','promo_code',' '])
            ->andWhere(['status'=>'Completed'])
            ->count();
        $gross_amount_of_collected_payment = Transactions::find()->where(['status' => 'Completed'])->sum('total_amount');;
        $aggregate_amount_of_purchased_package =  Transactions::find()->where(['status' => 'Completed','reftype'=>'Package Purchase'])->sum('total_amount');
        $aggregate_amount_of_collected_rental = Transactions::find()->where(['status' => 'Completed','reftype'=>'Monthly Rental'])->sum('total_amount');
        $transactions = Transactions::find()
            ->Where(['status'=>'Completed'])
            ->all();
        $totle_deposite_count = 0;
        if(!empty($transactions)) {
            foreach ($transactions as $deposite) {
                $totle_deposite = TransactionsItems::find()
                    ->where(['transaction_id' => $deposite['id']])
                    ->andWhere(['in','description', ['Deposit','Keycard Deposit','Utilities Deposit']])
                    ->sum('total_amount');
                $totle_deposite_count += $totle_deposite;
            }
        }
        $aggregate_amount_of_collected_agent_commission = Transactions::find()->where(['status' => 'Completed','reftype'=>'Agent Commision'])->sum('total_amount');
        $aggregate_amount_of_renovation_payment = Transactions::find()->where(['status' => 'Completed','reftype'=>'Renovation Payment'])->sum('total_amount');
        $aggregate_amount_of_service_payment = Transactions::find()->where(['status' => 'Completed','reftype'=>'Service'])->sum('total_amount');
        /*Booking*/
        $new_booking_request = BookingRequests::find()->where(['status' => 'New'])->count();
        $completed_booking_request = BookingRequests::find()->where(['status' => 'Completed'])->count();
        $declared_booking_request = BookingRequests::find()->where(['status' => 'New'])->count();
        $cancelled_booking_request = BookingRequests::find()->where(['status' => 'Cancelled'])->count();
        /*Renovation*/
        $approved_renovation_quote = RenovationQuotes::find()->where(['status' => 'Approved'])->count();
        $completed_renovation_quote = RenovationQuotes::find()->where(['status' => 'Completed'])->count();
        /*insurance*/
        $completed_insurance = TodoList::find()->where(['reftype'=>'insurance','status' => 'Completed'])->count();
        /*defectcase*/
        $completed_defect_case = TodoList::find()->where(['reftype'=>'Defect Report','status' => 'Completed'])->count();
        /*Auto rental collection*/
        $completed_rental_collection = TodoList::find()->where(['reftype'=>'Monthly Rental','status' => 'Paid'])->count();
        /*Service*/
        $new_service_request = TodoList::find()->where(['reftype'=>'Service','status' => 'New'])->count();
        $pending_service_request = TodoList::find()->where(['reftype'=>'Service','status' => 'Pending'])->count();
        $completed_service_request = TodoList::find()->where(['reftype'=>'Service','status' => 'Completed'])->count();
        return $this->render('index', [
            'active_user' => $active_user,
            'active_property_agents' => $active_property_agents,
            'active_listing' => $active_listing,
            'managed_listing' => $managed_listing,
            'active_listing_viewed' => $active_listing_viewed,
            'purchased_listing_package_active' => $purchased_listing_package_active,
            'purchased_tenancy_service_active' => $purchased_tenancy_service_active,
            'subscribed_auto_rental_collection_service_active' => $subscribed_auto_rental_collection_service_active,
            'purchased_insurance_policy_active' => $purchased_insurance_policy_active,
            'purchased_tenancy_service' => $purchased_tenancy_service,
            'promo_code_used' => $promo_code_used,
            'gross_amount_of_collected_payment' => $gross_amount_of_collected_payment,
            'aggregate_amount_of_purchased_package' => $aggregate_amount_of_purchased_package,
            'aggregate_amount_of_collected_rental' => $aggregate_amount_of_collected_rental,
            'aggregate_amount_of_collected_deposits' => $totle_deposite_count,
            'aggregate_amount_of_collected_agent_commission' => $aggregate_amount_of_collected_agent_commission,
            'aggregate_amount_of_renovation_payment' => $aggregate_amount_of_renovation_payment,
            'aggregate_amount_of_service_payment' => $aggregate_amount_of_service_payment,
            'new_booking_request' => $new_booking_request,
            'completed_booking_request' => $completed_booking_request,
            'declared_booking_request' => $declared_booking_request,
            'cancelled_booking_request' => $cancelled_booking_request,
            'approved_renovation_quote' => $approved_renovation_quote,
            'completed_renovation_quote' => $completed_renovation_quote,
            'completed_insurance' => $completed_insurance,
            'completed_defect_case' => $completed_defect_case,
            'completed_rental_collection' => $completed_rental_collection,
            'new_service_request' => $new_service_request,
            'pending_service_request' => $pending_service_request,
            'completed_service_request' => $completed_service_request,
        ]);
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Users();
        $model->scenario = 'login';
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionChangepassword()
    {
        $model = new Users();
        $model->scenario = 'changepassword';
        // print_r($model);exit;
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                $userdetails = Users::findOne(['id'=>Yii::$app->user->id]);
                // var_dump($_POST);exit;
                //print_r(Yii::$app->request->post('password'));exit;
                $userdetails->password = md5($_POST['Users']['password']);
                if($userdetails->save()){
                    Yii::$app->session->setFlash('success', "You have changed password successfully.");

                    return $this->redirect(['changepassword']);
                }
                //  Yii::$app->session->setFlash('contactFormSubmitted');
            }else{
                return $this->render('changepassword', [
                    'model' => $model,
                ]);
            }

            // return $this->refresh();
        }
        return $this->render('changepassword', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionRemoveproperties()
    {
        $todaydate = date('Y-m-d 11:59:59');
        $days_ago = date('Y-m-d 00:00:00', strtotime('-45 days', strtotime(date('Y-m-d'))));
        //echo $todaydate;
       // echo $days_ago;exit;
        $properties = Properties::find()->where(['digital_tenancy'=>0,'status'=>'Active'])->all();
        if(!empty($properties)){
           foreach ($properties as $property){
               $now = time(); // or your date as well

               $createddate = strtotime(date('Y-m-d',strtotime($property->created_at)));
               $datediff = $now - $createddate;

               $days = round($datediff / (60 * 60 * 24))."<br>";
               if($days>=45) {
                   $property->status = 'Inactive';
                   $property->updated_at = date('Y-m-d H:i:s');
                   $property->save(false);
               }
           }
           exit;

       }
        $cronjob = new Cronjobs();
        $cronjob->type = 'Remove Properties';
        $cronjob->created_at = date('Y-m-d H:i:s');
        $cronjob->save(false);
    }
  public function actionAddautorental()
{
    
    $requests = BookingRequests::find()->select('id,property_id,user_id,landlord_id,monthly_rental,commencement_date,tenancy_period')->where(['status'=>'Rented'])->all();
   // echo "<pre>";print_r($requests);exit;

    if(!empty($requests)){
        foreach ($requests as $request){
            $commencement_date = $request->commencement_date;
            $tenancy_period = $request->tenancy_period;
            $firstdate = date('Y-m-d',strtotime($commencement_date));
            $lastdate = date('Y-m-d', strtotime("+" . $tenancy_period . " months", strtotime($commencement_date)));
            $interval = new \DateInterval('P1M');
            $realEnd = new \DateTime($lastdate);
            $realEnd->add($interval);

            $period = new \DatePeriod(new \DateTime($firstdate), $interval, $realEnd);
            $format = 'Y-m-d';
            $dates = array();
            foreach($period as $date) {
                      $dates[] = $date->format($format);
            }
            if(!empty($dates)){
                foreach ($dates as $date){
                    if($date==date('Y-m-d')){
                        $servicefees = number_format($request->monthly_rental * 10 / 100, 2, '.', '');
                        $sst = Yii::$app->common->calculatesst($servicefees);
                        $rentalmodel = new TodoList();
                        $rentalmodel->request_id = $request->id;
                        $rentalmodel->property_id = $request->property_id;
                        $rentalmodel->user_id = $request->user_id;
                        $rentalmodel->landlord_id = $request->landlord_id;
                        $rentalmodel->rent_startdate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                        $rentalmodel->rent_enddate = $date;
                        $rentalmodel->pay_from = 'Tenant';
                        $rentalmodel->subtotal = $request->monthly_rental;
                        $rentalmodel->sst = $sst;
                        $rentalmodel->service_fees = $servicefees;
                        $rentalmodel->total = $request->monthly_rental+$servicefees+$sst;
                        $rentalmodel->reftype = 'Monthly Rental';
                        $rentalmodel->status = 'Unpaid';
                        $rentalmodel->created_at = date('Y-m-d H:i:s');
                        $rentalmodel->save(false);
                    }
                }

            }


        }
    }
    $cronjob = new Cronjobs();
    $cronjob->type = 'Auto Rental';
    $cronjob->created_at = date('Y-m-d H:i:s');
    $cronjob->save(false);
}
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionUpdaterequeststatus()
    {
        $mscrequests = Msc::find()->where(['in', 'status', ['Pending MSC Approval', 'Need Activation','Pending']])->andWhere(['id'=>4])->all();
        if (!empty($mscrequests)) {
            foreach ($mscrequests as $mscrequest) {
                $getrequeststatus = array();
                $getactivationlink = array();
                if (($mscrequest->request->status == 'Pending MSC Approval' && $mscrequest->status == 'Pending MSC Approval') || $mscrequest->status=='Pending') {
                    //echo "sdsd";
                    $getrequeststatus = $this->Getrequeststatus($mscrequest);
                    print_r($getrequeststatus);exit;
                    if (!empty($getrequeststatus)) {
                        $mscrequest->getrequeststatus_response = json_encode($getrequeststatus);
                        $mscrequest->updated_at = date('Y-m-d H:i:s');
                        $mscrequest->save(false);
                        if ($getrequeststatus['statusCode'] == 000 && $getrequeststatus['dataList']['requestStatus'] == 'Pending Activation') {
                            $mscrequest->status = 'Pending Activation';
                            $mscrequest->save(false);
                            $getactivationlink = $this->Getactivationlink($mscrequest);
                            if (!empty($getactivationlink)) {
                                $mscrequest->getactivationlink_response = json_encode($getactivationlink);
                                $mscrequest->updated_at = date('Y-m-d H:i:s');
                                $mscrequest->save(false);
                                if ($getactivationlink['statusCode'] == 000 && $getactivationlink['statusMsg'] == 'Success') {
                                    $mscrequest->activation_link = $getactivationlink['activationLink'];
                                    $mscrequest->status = 'Need Activation';
                                    $mscrequest->updated_at = date('Y-m-d H:i:s');
                                    $mscrequest->save(false);
                                    $todomodel = new TodoList();
                                    $todomodel->user_id = $mscrequest->user_id;
                                    $todomodel->msc_id = $mscrequest->id;
                                    $todomodel->property_id = $mscrequest->request->property_id;
                                    $todomodel->request_id = $mscrequest->request_id;
                                    $todomodel->reftype = 'Activation Link';
                                    $todomodel->created_at = date('Y-m-d H:i:s');
                                    $todomodel->updated_at = date('Y-m-d H:i:s');
                                    $todomodel->status = 'Pending';
                                    $todomodel->save(false);

                                }

                            }
                        }

                    }
                } else if ($mscrequest->status == 'Need Activation') {
                    echo "sdssdsdd";
                    $getrequeststatus = $this->Getrequeststatus($mscrequest);
                    print_r($getrequeststatus);exit;
                    if (!empty($getrequeststatus)) {
                        $mscrequest->getrequeststatus_response = json_encode($getrequeststatus);
                        $mscrequest->updated_at = date('Y-m-d H:i:s');
                        $mscrequest->save(false);
                        if ($getrequeststatus['statusCode'] == 000 && $getrequeststatus['dataList']['requestStatus'] == 'Completed') {
                            $mscrequest->status = 'Approved';
                            $mscrequest->updated_at = date('Y-m-d H:i:s');
                            $mscrequest->save(false);
                            $todomodel = TodoList::find()->where(['msc_id'=>$mscrequest->id])->one();
                            $todomodel->status = 'Completed';
                            $todomodel->updated_at = date('Y-m-d H:i:s');
                            $todomodel->save(false);
                            $usermodel = Users::findOne($mscrequest->user_id);
                            $usermodel->document_type = $mscrequest->type;
                            $usermodel->document_front = $mscrequest->document_front;
                            $usermodel->document_back = $mscrequest->document_back;
                            $usermodel->document_no = $mscrequest->document_no;
                            $usermodel->msccertificate = $mscrequest->mscrequest_id;
                            $usermodel->updated_at = date('Y-m-d H:i:s');
                            $usermodel->save(false);

                        }

                    }
                }
            }
        }
    }


    private function Getrequeststatus($mscrequestmodel)
    {
        $certificaterequest_id = $mscrequestmodel->mscrequest_id;
        $userID = $mscrequestmodel->document_no;


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "ec2-13-250-42-162.ap-southeast-1.compute.amazonaws.com/MTSAPilot/MyTrustSignerAgentWS?wsdl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:mtsa=\"http://mtsa.msctg.com/\">\n   <soapenv:Header/>\n   <soapenv:Body>\n      <mtsa:GetRequestStatus>\n         <!--1 or more repetitions:-->\n         <UserRequestList>\n            <!--Optional:-->\n            <requestID>".$certificaterequest_id."</requestID>\n            <!--Optional:-->\n            <userID>".$userID."</userID>\n         </UserRequestList>\n      </mtsa:GetRequestStatus>\n   </soapenv:Body>\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "Username: rumahi",
                "Password: YcuLxvMMcXWPLRaW",
                "Content-Type: text/xml"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        // echo $response;
        if ($err) {
            return '';
        } else {
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = new \SimpleXMLElement($response);
            $body = $xml->xpath('//SBody')[0];
            $responsearray = json_decode(json_encode((array)$body), TRUE);
            if(!empty($responsearray) &&  isset($responsearray['ns2GetRequestStatusResponse'])  && !empty($responsearray['ns2GetRequestStatusResponse'])){
                return $responsearray['ns2GetRequestStatusResponse']['return'];
            }else{
                return '';
            }
            //echo $response;exit;
        }


    }
    private function Getactivationlink($mscrequestmodel)
    {



        $certificaterequest_id = $mscrequestmodel->mscrequest_id;
        $userID = $mscrequestmodel->document_no;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "ec2-13-250-42-162.ap-southeast-1.compute.amazonaws.com/MTSAPilot/MyTrustSignerAgentWS?wsdl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:mtsa=\"http://mtsa.msctg.com/\">\n   <soapenv:Header/>\n   <soapenv:Body>\n      <mtsa:GetActivation>\n         <UserID>".$userID."</UserID>\n         <RequestID>".$certificaterequest_id."</RequestID>\n      </mtsa:GetActivation>\n   </soapenv:Body>\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "Username: rumahi",
                "Password: YcuLxvMMcXWPLRaW",
                "Content-Type: text/xml"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        // echo $response;
        if ($err) {
            return '';
        } else {
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = new \SimpleXMLElement($response);
            $body = $xml->xpath('//SBody')[0];
            $responsearray = json_decode(json_encode((array)$body), TRUE);

            if(!empty($responsearray) &&  isset($responsearray['ns2GetActivationResponse'])  && !empty($responsearray['ns2GetActivationResponse'])){
                return $responsearray['ns2GetActivationResponse']['return'];
            }else{
                return '';
            }
            //echo $response;exit;
        }


    }


    public function actionPaysuccess()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        if(!empty($_REQUEST)) {
            $transaction = Payments::find()->where(['order_id' => $_REQUEST['RefNo'], 'status' => 'Pending'])->one();
            if ($_REQUEST['Status'] == 1) {
                if (!empty($transaction)) {
                    $transaction1 = Yii::$app->db->beginTransaction();
                    try {
                        $transaction->status = 'Completed';
                        $transaction->response = json_encode($_POST);
                        $transaction->save(false);
                        if ($transaction->package_id != null) {

                            $packagedetails = Packages::findOne($transaction->package_id);

                            $model = new UserPackages();
                            $model->package_id = $transaction->package_id;
                            $model->user_id = $transaction->user_id;
                            $model->quantity = $packagedetails->quantity;
                            $model->start_date = date('Y-m-d');
                            $model->end_date = date('Y-m-d', strtotime('+1 month'));
                            $model->created_at = date('Y-m-d H:i:s');
                            $model->updated_at = date('Y-m-d H:i:s');
                            if ($model->save()) {
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $model->user_id;
                                $transactionmodel->amount = $transaction->amount;
                                $transactionmodel->sst = $transaction->sst;
                                $transactionmodel->discount = $transaction->discount;
                                $transactionmodel->coins = $transaction->coins;
                                $transactionmodel->coins_savings = $transaction->coins_savings;
                                $transactionmodel->total_amount = $transaction->total_amount;
                                $transactionmodel->package_id = $model->id;
                                $transactionmodel->payment_id = $transaction->id;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                $transactionmodel->reftype = 'Package Purchase';
                                $transactionmodel->status = 'Completed';
                                if($transactionmodel->save(false)){
                                    $lastid = $transactionmodel->id;
                                    $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = "TR".$reference_no;
                                    $transactionmodel->save(false);
                                    $user = Users::findOne($model->user_id);
                                    $user->membership_expire_date = date('Y-m-d', strtotime('+1 month'));
                                    $user->property_credited += $packagedetails->quantity;
                                    if($user->save(false)){
                                        if($transaction->coins>0) {
                                            Yii::$app->common->deductgoldcoinspurchase($model->user_id, $transaction->coins, $lastid);
                                        }
                                        $amountwithoutsst = $transaction->amount;
                                        $totaldiscount = $transaction->discount+$transaction->coins_savings;
                                        $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $totaldiscount;
                                        $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                        Yii::$app->common->addgoldcoinspurchase($this->user_id,$gold_coins,$lastid);

                                        $transaction1->commit();
                                        echo "RECEIVEOK";exit;

                                    }else{
                                        $transaction1->rollBack();
                                        echo "RECEIVEOK";exit;

                                    }

                                }

                            }

                        }else if($transaction->package_id==NULL && $transaction->todo_id==NULL){
                            $userbalance = Users::getbalance($transaction->user_id);

                            $model = new Topups();
                            $model->user_id = $transaction->user_id;
                            $model->amount =  $transaction->amount;
                            $model->total_amount = $transaction->total_amount;
                            $model->oldbalance = $userbalance;
                            $model->newbalance = $userbalance + $model->amount;
                            $model->status = 'Completed';
                            $model->created_at = date('Y-m-d H:i:s');
                            if($model->save(false)){
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $model->user_id;
                                $transactionmodel->amount = $transaction->amount;
                                $transactionmodel->total_amount = $transaction->amount;
                                $transactionmodel->topup_id = $model->id;
                                $transactionmodel->payment_id = $transaction->id;
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                $transactionmodel->reftype = 'Topup';
                                $transactionmodel->status = 'Completed';
                                if($transactionmodel->save(false)){
                                    $lastid = $transactionmodel->id;
                                    $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = "TR".$reference_no;
                                    if($transactionmodel->save(false)){
                                        Users::updatebalance($userbalance + $model->amount,$transaction->user_id);
                                        $transaction1->commit();
                                        echo "RECEIVEOK";exit;
                                    }else{
                                        $transaction1->rollBack(); // if save fails then rollback
                                        echo "FAILED";exit;
                                    }
                                }else{
                                    $transaction1->rollBack();
                                    echo "FAILED";exit;
                                }

                            }else{
                                echo "FAILED";exit;
                            }


                        }else if($transaction->request_id!='' && $transaction->todo_id!='') {
                            $bookingrequestmodel = BookingRequests::findOne($transaction->request_id);
                            $todomodel = TodoList::findOne($transaction->todo_id);
                            $transactionmodel = new Transactions();
                            $transactionmodel->user_id = $bookingrequestmodel->user_id;
                            $transactionmodel->request_id = $transaction->request_id;
                            $transactionmodel->landlord_id = $bookingrequestmodel->landlord_id;
                            $transactionmodel->amount = $transaction->amount;
                            $transactionmodel->total_amount = $transaction->amount;
                            $transactionmodel->payment_id = $transaction->id;
                            $transactionmodel->reftype = 'Booking Fees Payment';
                            $transactionmodel->status = 'Completed';
                            $transactionmodel->created_at = date('Y-m-d H:i:s');
                            if($transactionmodel->save(false)){
                                $lastid = $transactionmodel->id;
                                $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                $transactionmodel->reference_no = "TR".$reference_no;
                                if($transactionmodel->save(false)){
                                    $bookingrequestmodel->status = 'Confirmed';
                                    $bookingrequestmodel->updated_by = $bookingrequestmodel->user_id;
                                    $bookingrequestmodel->updated_at = date('Y-m-d H:i:s');
                                    if($bookingrequestmodel->save(false)){
                                        $todomodel->status = 'Confirmed';
                                        $todomodel->updated_at = date('Y-m-d H:i:s');
                                        $todomodel->save(false);
                                        $systemaccount = Yii::$app->common->getsystemaccount();
                                        $systemaccountbalance = $systemaccount->wallet_balance;
                                        $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance + $transaction->amount, $systemaccount->id);
                                        $content = file_get_contents('cc-letter/cc-letter.html');
                                        $content = str_replace("@name@",$transaction->name,$content);
                                        $content = str_replace("@document_no@",$transaction->document_no,$content);
                                        $content = str_replace("@date@",date('d M Y'),$content);
                                        $ipaddress = '';

                                        if (isset($_SERVER['HTTP_CLIENT_IP']))
                                            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                                        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                                            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                                        else if(isset($_SERVER['HTTP_X_FORWARDED']))
                                            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                                        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                                            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                                        else if(isset($_SERVER['HTTP_FORWARDED']))
                                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                                        else if(isset($_SERVER['REMOTE_ADDR']))
                                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                                        else
                                            $ipaddress = 'UNKNOWN';
                                        $content = str_replace("@ipaddress@",$ipaddress,$content);
                                        $pdf = new Pdf([
                                            // set to use core fonts only
                                            'mode' => Pdf::MODE_CORE,
                                            // A4 paper format
                                            'format' => Pdf::FORMAT_A4,
                                            // portrait orientation
                                            'orientation' => Pdf::ORIENT_PORTRAIT,
                                            // stream to browser inline
                                            'destination' => Pdf::DEST_BROWSER,
                                            // your html content input
                                            'content' => $content,
                                            // format content from your own css file if needed or use the
                                            // enhanced bootstrap css built by Krajee for mPDF formatting
                                            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/bootstrap.css',

                                            // any css to be embedded if required
                                            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

                                            // any css to be embedded if required
                                            'cssInline' => '.kv-heading-1{font-size:18px}',
                                            // call mPDF methods on the fly
                                            'methods' => [
                                                'SetHeader' => [''],
                                                'SetFooter' => [''],
                                            ]
                                        ]);
                                        $filename = \Yii::$app->security
                                                ->generateRandomString().time().".pdf";
                                        // return the pdf output as per the destination setting
                                        $pdf->output($content,'uploads/' .$filename,'F');
                                        $bookingrequestmodel->cc_letter = 'uploads/' .$filename;
                                        $bookingrequestmodel->save(false);
                                        $transaction1->commit();
                                        echo "RECEIVEOK";exit;
                                    }else{
                                        $transaction1->rollBack();
                                        echo "FAILED";exit;
                                    }

                                }else{
                                    $transaction1->rollBack();
                                    echo "FAILED";exit;
                                }
                            }else{
                                $transaction1->rollBack();
                                echo "FAILED";exit;
                            }

                        } else {
                            $post['amount'] = $transaction->amount;
                            $post['discount'] = $transaction->discount;
                            $post['promo_code'] = $transaction->promo_code;
                            $post['gold_coins'] = $transaction->coins;
                            $post['coins_savings'] = $transaction->coins_savings;
                            $todomodel = TodoList::findOne($transaction->todo_id);

                            $response = Yii::$app->common->payment($transaction->user_id,$transaction->todo_id,'Accepted',$todomodel->reftype,$post,$transaction->id);
                            $transaction1->commit();
                            //$dataresponse = json_decode($response);
                            if(!empty($response) && $response['status']==1){
                                echo "RECEIVEOK";exit;


                            }else{
                                $transaction1->rollBack();
                                echo "FAILED";exit;

                            }

                        }

                    } catch (Exception $e) {
                        // # if error occurs then rollback all transactions
                        $transaction1->rollBack();
                        echo "FAILED";exit;

                    }
                }else{
                    echo "FAILED";exit;

                }
            }else if($_REQUEST['Status']==0) {
                $transaction->response = json_encode($_REQUEST);
                $transaction->status = 'Failed';
                $transaction->save(false);


            }

        }

    }

    public function actionPayment()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
//
        if(isset($_GET['order_id']) && $_GET['order_id']!='')
        {
            $transaction = Payments::find()->where(['order_id'=>$_GET['order_id'],'status'=>'Pending'])->one();
            if(!empty($transaction)){
                if($transaction->package_id!=''){
                    $packagedetail = Packages::findOne($transaction->package_id);
                    $detail = "Purchase Package - ".$packagedetail->name;
                    //$hashed_string = md5($secretkey . $detail . $transaction->amount . $_GET['order_id']);
                }else if($transaction->package_id==NULL && $transaction->todo_id==NULL){
                    $detail = "Topup Wallet";

                }else if($transaction->request_id!='' && $transaction->todo_id!=''){
                    $detail = "Booking Fees Payment";

                }else {
                    $tododetails = TodoList::findOne($transaction->todo_id);
                    $detail = $tododetails->reftype;
                    //$hashed_string = md5($secretkey . $detail . $transaction->amount . $_GET['order_id']);
                }

                $paymentUrl = "https://payment.ipay88.com.my/epayment/entry.asp";
                $refno = $_GET['order_id'];
                //$merchantkey = 'QrB9d97iae';
                ///$merchantcode = 'M04853';
                $merchantkey = 'Rp4zceo1ai';
                $merchantcode = 'M27940';

                $fields = array(
                    'MerchantCode'=>'M27940',
                    'PaymentId'=>'',
                    'RefNo'=>$refno,
                    'Amount'=>$transaction->total_amount,
                    'Currency'=>'MYR',
                    'ProdDesc'=>$detail,
                    'UserName'=>$transaction->user->full_name,
                    'UserEmail'=>$transaction->user->email,
                    'UserContact'=>$transaction->user->contact_no,
                    'Remark'=>'',
                    'Lang'=>'UTF-8',
                    'SignatureType'=>'SHA256',
                    'Signature'=>'',
                    'ResponseURL'=>'https://admin.rumah-i.com/site/success',
                    'BackendURL'=>'https://admin.rumah-i.com/site/paysuccess',
                );
                //echo $merchantkey.$merchantcode.$fields['RefNo'].$fields['Amount'].$fields['Currency'];exit;
                $hash = hash('sha256', $merchantkey.$merchantcode.$fields['RefNo'].preg_replace('/[\.\,]/', '', $fields['Amount']).$fields['Currency']);
                //echo $hash;exit;
                $fields = array(
                    'MerchantCode'=>'M27940',
                    'PaymentId'=>'',
                    'RefNo'=>$refno,
                    'Amount'=>$transaction->total_amount,
                    'Currency'=>'MYR',
                    'ProdDesc'=>$detail,
                    'UserName'=>$transaction->user->full_name,
                    'UserEmail'=>$transaction->user->email,
                    'UserContact'=>$transaction->user->contact_no,
                    'Remark'=>'',
                    'Lang'=>'UTF-8',
                    'SignatureType'=>'SHA256',
                    'Signature'=>$hash,
                    'ResponseURL'=>'https://admin.rumah-i.com/site/success',
                    'BackendURL'=>'https://admin.rumah-i.com/site/paysuccess',

                );
                //print_r($fields);exit;

//url-ify the data for the POST
                echo "<form id='autosubmit' action='".$paymentUrl."' method='post'>";
                if (is_array($fields) || is_object($fields))
                {
                    foreach ($fields as $key => $val) {
                        echo "<input type='hidden' name='".$key."' value='".htmlspecialchars($val)."'>";
                    }
                }
                echo "</form>";
                echo "
		<script type='text/javascript'>
		    function submitForm() {
		        document.getElementById('autosubmit').submit();
		    }
		    window.onload = submitForm;
		</script>

		";

//close connection

            }else{
                echo "Something went wrong.Please try after sometimes2.";exit;
            }

        }else{
                echo "Something went wrong.Please try after sometimes1.";exit;
            }
        }
    public function actionSuccess()
    {
        date_default_timezone_set("Asia/Kuala_Lumpur");
//        $merchant_id = '539158696551957';
//        $secretkey = '2449-770';
        $merchant_id = '167157975459962';
        $secretkey = '17921-867';

            //echo "<pre>";print_r($_POST);exit;
            $transaction = Payments::find()->where(['order_id'=>$_POST['RefNo'],'status'=>'Pending'])->one();
            # if hash is the same then we know the data is valid
            if(!empty($_POST) && $_POST['Status']==1) {
                # this is a simple result page showing either the payment was successful or failed. In real life you will need to process the order made by the customer
                if (!empty($transaction)) {
                    $transaction1 = Yii::$app->db->beginTransaction();
                    try {
                        $transaction->status = 'Completed';
                        $transaction->response = json_encode($_POST);
                        $transaction->save(false);
                        if ($transaction->package_id != null) {

                            $packagedetails = Packages::findOne($transaction->package_id);

                            $model = new UserPackages();
                            $model->package_id = $transaction->package_id;
                            $model->user_id = $transaction->user_id;
                            $model->start_date = date('Y-m-d');
                            $model->quantity = $packagedetails->quantity;
                            $model->end_date = date('Y-m-d', strtotime('+1 month'));
                            $model->created_at = date('Y-m-d H:i:s');
                            $model->updated_at = date('Y-m-d H:i:s');
                            if ($model->save(false)) {
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $model->user_id;
                                $transactionmodel->amount = $transaction->amount;
                                $transactionmodel->sst = $transaction->sst;
                                $transactionmodel->discount = $transaction->discount;
                                $transactionmodel->coins = $transaction->coins;
                                $transactionmodel->coins_savings = $transaction->coins_savings;
                                $transactionmodel->total_amount = $transaction->total_amount;
                                $transactionmodel->package_id = $model->id;
                                $transactionmodel->payment_id = $transaction->id;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                $transactionmodel->reftype = 'Package Purchase';
                                $transactionmodel->status = 'Completed';
                                if($transactionmodel->save(false)){
                                    $lastid = $transactionmodel->id;
                                    $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = "TR".$reference_no;
                                    $transactionmodel->save(false);
                                    $user = Users::findOne($model->user_id);
                                    $user->membership_expire_date = date('Y-m-d', strtotime('+1 month'));
                                    $user->property_credited += $packagedetails->quantity;
                                    $user->updated_at = date('Y-m-d H:i:s');
                                    if($user->save(false)){
                                        if($transaction->coins>0) {
                                            Yii::$app->common->deductgoldcoinspurchase($model->user_id, $transaction->coins, $lastid);
                                        }
                                        $amountwithoutsst = $transaction->amount;
                                        $totaldiscount = $transaction->discount+$transaction->coins_savings;
                                        $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $totaldiscount;
                                        $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                        Yii::$app->common->addgoldcoinspurchase($this->user_id,$gold_coins,$lastid);
                                        $transaction1->commit();
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                        exit;
                                    }else{
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again123.</h1></body></html>';
                                        exit;
                                    }

                                }

                            }

                        }else if($transaction->package_id==NULL && $transaction->todo_id==NULL){
                                $userbalance = Users::getbalance($transaction->user_id);

                                $model = new Topups();
                                $model->user_id = $transaction->user_id;
                                $model->amount =  $transaction->amount;
                                $model->total_amount = $transaction->total_amount;
                                $model->oldbalance = $userbalance;
                                $model->newbalance = $userbalance + $model->amount;
                                $model->status = 'Completed';
                                $model->created_at = date('Y-m-d H:i:s');
                                if($model->save(false)){
                                    $transactionmodel = new Transactions();
                                    $transactionmodel->user_id = $model->user_id;
                                    $transactionmodel->amount = $transaction->amount;
                                    $transactionmodel->total_amount = $transaction->amount;
                                    $transactionmodel->topup_id = $model->id;
                                    $transactionmodel->payment_id = $transaction->id;
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    $transactionmodel->reftype = 'Topup';
                                    $transactionmodel->status = 'Completed';
                                    if($transactionmodel->save(false)){
                                        $lastid = $transactionmodel->id;
                                        $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = "TR".$reference_no;
                                        if($transactionmodel->save(false)){
                                            Users::updatebalance($userbalance + $model->amount,$transaction->user_id);
                                            $transaction1->commit();
                                            echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                            exit;
                                        }else{
                                            $transaction1->rollBack(); // if save fails then rollback
                                            echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again123.</h1></body></html>';
                                            exit;
                                        }
                                    }else{
                                        $transaction1->rollBack();
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again1234.</h1></body></html>';
                                        exit;
                                    }

                                }else{
                                    echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again12345.</h1></body></html>';
                                    exit;
                                }


                        }else if($transaction->request_id!='' && $transaction->todo_id!='') {
                            $bookingrequestmodel = BookingRequests::findOne($transaction->request_id);
                            $todomodel = TodoList::findOne($transaction->todo_id);
                            $transactionmodel = new Transactions();
                            $transactionmodel->user_id = $bookingrequestmodel->user_id;
                            $transactionmodel->request_id = $transaction->request_id;
                            $transactionmodel->landlord_id = $bookingrequestmodel->landlord_id;
                            $transactionmodel->amount = $transaction->amount;
                            $transactionmodel->total_amount = $transaction->amount;
                            $transactionmodel->payment_id = $transaction->id;
                            $transactionmodel->reftype = 'Booking Fees Payment';
                            $transactionmodel->status = 'Completed';
                            $transactionmodel->created_at = date('Y-m-d H:i:s');
                            if($transactionmodel->save(false)){
                                $lastid = $transactionmodel->id;
                                $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                $transactionmodel->reference_no = "TR".$reference_no;
                                if($transactionmodel->save(false)){
                                    $bookingrequestmodel->status = 'Confirmed';
                                    $bookingrequestmodel->updated_by = $bookingrequestmodel->user_id;
                                    $bookingrequestmodel->updated_at = date('Y-m-d H:i:s');
                                    if($bookingrequestmodel->save(false)){
                                        $todomodel->status = 'Confirmed';
                                        $todomodel->updated_at = date('Y-m-d H:i:s');
                                        $todomodel->save(false);
                                        $systemaccount = Yii::$app->common->getsystemaccount();
                                        $systemaccountbalance = $systemaccount->wallet_balance;
                                        $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance + $transaction->amount, $systemaccount->id);
                                        $content = file_get_contents('cc-letter/cc-letter.html');
                                        $content = str_replace("@name@",$transaction->name,$content);
                                        $content = str_replace("@document_no@",$transaction->document_no,$content);
                                        $content = str_replace("@date@",date('d M Y'),$content);
                                        $ipaddress = '';

                                        if (isset($_SERVER['HTTP_CLIENT_IP']))
                                            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                                        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                                            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                                        else if(isset($_SERVER['HTTP_X_FORWARDED']))
                                            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                                        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                                            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                                        else if(isset($_SERVER['HTTP_FORWARDED']))
                                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                                        else if(isset($_SERVER['REMOTE_ADDR']))
                                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                                        else
                                            $ipaddress = 'UNKNOWN';
                                        $content = str_replace("@ipaddress@",$ipaddress,$content);
                                        $pdf = new Pdf([
                                            // set to use core fonts only
                                            'mode' => Pdf::MODE_CORE,
                                            // A4 paper format
                                            'format' => Pdf::FORMAT_A4,
                                            // portrait orientation
                                            'orientation' => Pdf::ORIENT_PORTRAIT,
                                            // stream to browser inline
                                            'destination' => Pdf::DEST_BROWSER,
                                            // your html content input
                                            'content' => $content,
                                            // format content from your own css file if needed or use the
                                            // enhanced bootstrap css built by Krajee for mPDF formatting
                                            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/bootstrap.css',

                                            // any css to be embedded if required
                                            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

                                            // any css to be embedded if required
                                            'cssInline' => '.kv-heading-1{font-size:18px}',
                                            // call mPDF methods on the fly
                                            'methods' => [
                                                'SetHeader' => [''],
                                                'SetFooter' => [''],
                                            ]
                                        ]);
                                        $filename = \Yii::$app->security
                                                ->generateRandomString().time().".pdf";
                                        // return the pdf output as per the destination setting
                                        $pdf->output($content,'uploads/' .$filename,'F');
                                        $bookingrequestmodel->cc_letter = 'uploads/' .$filename;
                                        $bookingrequestmodel->save(false);
                                        $transaction1->commit();
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                        exit;
                                    }else{
                                        $transaction1->rollBack();
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again1234.</h1></body></html>';
                                        exit;
                                    }

                                }else{
                                    $transaction1->rollBack();
                                    echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again1234.</h1></body></html>';
                                    exit;
                                }
                            }else{
                                $transaction1->rollBack();
                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again1234.</h1></body></html>';
                                exit;
                            }

                        }else {
                            $post['amount'] = $transaction->amount;
                            $post['discount'] = $transaction->discount;
                            $post['promo_code'] = $transaction->promo_code;
                            $post['gold_coins'] = $transaction->coins;
                            $post['coins_savings'] = $transaction->coins_savings;
                            $todomodel = TodoList::findOne($transaction->todo_id);

                            $response = Yii::$app->common->payment($transaction->user_id,$transaction->todo_id,'Accepted',$todomodel->reftype,$post,$transaction->id);
                             $transaction1->commit();
                            //$dataresponse = json_decode($response);
                            if(!empty($response) && $response['status']==1){
                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                exit;

                            }else{
                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again12.</h1></body></html>';
                                exit;
                            }

                        }

                    } catch (Exception $e) {
                        // # if error occurs then rollback all transactions
                        $transaction1->rollBack();
                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again123.</h1></body></html>';
                        exit;
                    }
                }else{
                    echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Something went wrong, Please try again12345.</h1></body></html>';
                    exit;
                }
                //echo "<pre>";print_r($_POST);exit;

            }else{
                $transaction->status = 'Failed';
                $transaction->response = json_encode($_POST);
                $transaction->save(false);
            }
    }

    public function actionRequery(){
        $fromdate = date('Y-m-d 00:00:00');
        $todate = date('Y-m-d H:i:s');
        $payments = Payments::find()->where(['status'=>1])->andWhere(['>=','DATE(created_at)', $fromdate])->andWhere(['<=','DATE(created_at)', $todate])->all();
        if(!empty($payments)){
            $merchantkey = 'Rp4zceo1ai';
            $MerchantCode = 'M27940';

            foreach ($payments as $key => $transaction) {

                $transactionexist = Transactions::find()->where(['payment_id'=>$transaction->id])->one();
                if(empty($transactionexist)) {
                    $date2 = strtotime(date("Y-m-d H:i:s"));
                    $date1 = strtotime($transaction->created_at);
                    $diff = abs($date2 - $date1);

                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff - $years * 365 * 60 * 60 * 24)
                        / (30 * 60 * 60 * 24));

                    $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                            $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                    $hours = floor(($diff - $years * 365 * 60 * 60 * 24
                            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
                        / (60 * 60));
                    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
                            - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
                            - $hours * 60 * 60) / 60);
                    if ($minutes > 10) {
                        $RefNo = $transaction->order_id;
                        $Amount = $transaction->amount;
                        $query = "https://payment.ipay88.com.my/epayment/enquiry.asp?MerchantCode=" . $MerchantCode . "&RefNo=" . str_replace(" ", "%20", $RefNo) . "&Amount=" . $Amount;


                        $url = parse_url($query);
                        $host = $url["host"];
                        $sslhost = "ssl://" . $host;
                        $path = $url["path"] . "?" . $url["query"];
                        $timeout = 1;
                        $fp = fsockopen($sslhost, 443, $errno, $errstr, $timeout);
                        if ($fp) {
                            fputs($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
                            $buf = '';
                            while (!feof($fp)) {
                                $buf .= fgets($fp, 128);
                            }
                            $lines = preg_split("/\n/", $buf);
                            $Result = $lines[count($lines) - 1];
                            fclose($fp);
                        } else {
                            # enter error handing code here
                        }
                        if ($Result == '00') {
                            $transaction1 = Yii::$app->db->beginTransaction();
                            try {
                                $transaction->status = 'Completed';
                                $transaction->response = json_encode($_POST);
                                $transaction->save(false);
                                if ($transaction->package_id != null) {

                                    $packagedetails = Packages::findOne($transaction->package_id);

                                    $model = new UserPackages();
                                    $model->package_id = $transaction->package_id;
                                    $model->user_id = $transaction->user_id;
                                    $model->start_date = date('Y-m-d');
                                    $model->quantity = $packagedetails->quantity;
                                    $model->end_date = date('Y-m-d', strtotime('+1 month'));
                                    $model->created_at = date('Y-m-d H:i:s');
                                    $model->updated_at = date('Y-m-d H:i:s');
                                    if ($model->save()) {
                                        $transactionmodel = new Transactions();
                                        $transactionmodel->user_id = $model->user_id;
                                        $transactionmodel->amount = $transaction->total_amount;
                                        $transactionmodel->total_amount = $transaction->total_amount;
                                        $transactionmodel->package_id = $model->id;
                                        $transactionmodel->created_at = date('Y-m-d H:i:s');
                                        $transactionmodel->reftype = 'Package Purchase';
                                        $transactionmodel->status = 'Completed';
                                        if($transactionmodel->save(false)){
                                            $lastid = $transactionmodel->id;
                                            $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                            $transactionmodel->reference_no = "TR".$reference_no;
                                            $transactionmodel->save(false);
                                            $user = Users::findOne($model->user_id);
                                            $user->membership_expire_date = date('Y-m-d', strtotime('+1 month'));
                                            $user->property_credited += $packagedetails->quantity;
                                            if($user->save(false)){
                                                $transaction1->commit();
                                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                                exit;
                                            }else{
                                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again.</h1></body></html>';
                                                exit;
                                            }

                                        }

                                    }

                                }else if($transaction->package_id==NULL && $transaction->todo_id==NULL){
                                    $userbalance = Users::getbalance($transaction->user_id);

                                    $model = new Topups();
                                    $model->user_id = $transaction->user_id;
                                    $model->amount =  $transaction->amount;
                                    $model->total_amount = $transaction->total_amount;
                                    $model->oldbalance = $userbalance;
                                    $model->newbalance = $userbalance + $model->amount;
                                    $model->status = 'Completed';
                                    $model->created_at = date('Y-m-d H:i:s');
                                    if($model->save(false)){
                                        $transactionmodel = new Transactions();
                                        $transactionmodel->user_id = $model->user_id;
                                        $transactionmodel->amount = $transaction->amount;
                                        $transactionmodel->total_amount = $transaction->amount;
                                        $transactionmodel->topup_id = $model->id;
                                        $transactionmodel->payment_id = $transaction->id;
                                        $transactionmodel->created_at = date('Y-m-d H:i:s');
                                        $transactionmodel->reftype = 'Topup';
                                        $transactionmodel->status = 'Completed';
                                        if($transactionmodel->save(false)){
                                            $lastid = $transactionmodel->id;
                                            $reference_no = Yii::$app->common->generatereferencenumber($lastid);
                                            $transactionmodel->reference_no = "TR".$reference_no;
                                            if($transactionmodel->save(false)){
                                                Users::updatebalance($userbalance + $model->amount,$transaction->user_id);
                                                $transaction1->commit();
                                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                                exit;
                                            }else{
                                                $transaction1->rollBack(); // if save fails then rollback
                                                echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again123.</h1></body></html>';
                                                exit;
                                            }
                                        }else{
                                            $transaction1->rollBack();
                                            echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again1234.</h1></body></html>';
                                            exit;
                                        }

                                    }else{
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again12345.</h1></body></html>';
                                        exit;
                                    }


                                } else {
                                    $post['amount'] = $transaction->amount;
                                    $post['discount'] = $transaction->discount;
                                    $post['promo_code'] = $transaction->promo_code;
                                    $post['gold_coins'] = $transaction->coins;
                                    $post['coins_savings'] = $transaction->coins_savings;
                                    $todomodel = TodoList::findOne($transaction->todo_id);

                                    $response = Yii::$app->common->payment($transaction->user_id,$transaction->todo_id,'Accepted',$todomodel->reftype,$post,$transaction->id);
                                    $transaction1->commit();
                                    if(!empty($response) && $response['status']==1){
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is successful.</h1></body></html>';
                                        exit;

                                    }else{
                                        echo '<html><head></head><body><h1 style="width: 80%;height: 200px;text-align:center;font-size: 70px;position: absolute;top:0;bottom: 0;left: 0;right: 0;margin: auto;">Your payment is failed, Please try again12.</h1></body></html>';
                                        exit;
                                    }

                                }

                            } catch (Exception $e) {
                                // # if error occurs then rollback all transactions
                                $transaction1->rollBack();
                            }

                        } else if ($Result == 'Payment Fail' || $Result == 'Record not found' || $Result == 'M88Admin' || $Result == 'Incorrect amount' || $Result == 'Invalid parameters') {
                            $transaction->status = "Failed";
                            $transaction->response = $Result;
                            $transaction->updated_at = date('Y-m-d H:i:s');
                            $transaction->save(false);
                        }
                    }
                }
            }
            exit;
        }


    }


    public function actionSendappointmentreminder(){
        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $appointments = TodoList::find()->where(['reftype'=>'Appointment','status'=>'Pending'])->andWhere(['appointment_date'=>$tomorrow])->orWhere(['appointment_date'=>date('Y-m-d')])->all();
        if(!empty($appointments)){
           foreach ($appointments as $appointment){
               $timerange = $appointment['appointment_time'];
               $timearray = explode('-',$timerange);
               $starttime = date("H:i", strtotime($timearray[0]));
               $nowtime = date('H:i');
               $twohours = date('H:i',strtotime('-2 hours',strtotime($timearray[0])));
               if($starttime==$nowtime && $tomorrow==$appointment['appointment_date']){
                   $subject = 'Viewing appointment reminder 24 hours before';
                   $textmessage = 'You have a viewing appointment to attend tomorrow. Goes to “To Do” to check the time.';
                   if($appointment->agent_id!=''){
                       Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->agent_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');

                       Yii::$app->common->Savenotification($appointment->agent_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->agent_id,$subject,$textmessage,'Partner');

                   }else if($appointment->landlord_id!=''){
                       Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->landlord_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');
                       Yii::$app->common->Savenotification($appointment->landlord_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->landlord_id,$subject,$textmessage,'User');

                   }

               }elseif (date('Y-m-d')==$appointment['appointment_date'] && $twohours==$nowtime){

                   $subject = 'Viewing appointment reminder 2 hours before';
                   $textmessage = 'You have a viewing appointment to attend 2 hours later. Get prepared.';
                   if($appointment->agent_id!=''){
                       Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->agent_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');

                       Yii::$app->common->Savenotification($appointment->agent_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->agent_id,$subject,$textmessage,'Partner');

                   }else if($appointment->landlord_id!=''){
                       Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->landlord_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');
                       Yii::$app->common->Savenotification($appointment->landlord_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                       Yii::$app->common->Sendpushnotification($appointment->landlord_id,$subject,$textmessage,'User');

                   }
               }

           }
        }

    }


    public function actionSendunpaidbillreminder(){
        $yesterday = date("Y-m-d", strtotime('yesterday'));
        $bills = TodoList::find()->where(['reftype'=>'General','status'=>'Unpaid'])->andWhere(['due_date'=>$yesterday])->all();
        if(!empty($bills)){
            foreach ($bills as $bill){
                $subject = 'Unpaid bill pending';
                $textmessage = 'You got one unpaid bill has been due, kindly settle now to avoid any late charges.';

                if($bill->pay_from=='Landlord'){
                       Yii::$app->common->Savenotification($bill->landlord_id,$subject,$textmessage,'',$bill->property_id,$bill->id);

                       Yii::$app->common->Sendpushnotification($bill->landlord_id,$subject,$textmessage,'User');

                   }else if($bill->pay_from=='Tenant'){
                       Yii::$app->common->Savenotification($bill->user_id,$subject,$textmessage,'',$bill->property_id,$bill->id);

                       Yii::$app->common->Sendpushnotification($bill->user_id,$subject,$textmessage,'User');

                   }

            }
          }

    }

    public function actionSendunpaidrentalreminder(){
        $yesterday = date("Y-m-d", strtotime('-7 days'));
        //echo $yesterday;exit;
        $bills = TodoList::find()->where(['reftype'=>'Monthly Rental','status'=>'Unpaid'])->andWhere(['DATE(created_at)'=>$yesterday])->all();
        //echo "<pre>";print_r($bills);exit;
        if(!empty($bills)){
            foreach ($bills as $bill){
                $subject = 'Outstanding monthly rental still pending';
                $textmessage = 'You got one outstanding rental has been due more than 7 days, kindly settle now to avoid breach of contract & deposits forfeited.';


                    Yii::$app->common->Savenotification($bill->user_id,$subject,$textmessage,'',$bill->property_id,$bill->id);

                    Yii::$app->common->Sendpushnotification($bill->user_id,$subject,$textmessage,'User');



            }
        }

    }
    public function actionUnsubscribepackage(){
        $date = date('Y-m-d');
        $userpackages = UserPackages::find()->where(['end_date'=>$date])->all();
        if(!empty($userpackages)){
            foreach ($userpackages as $userpackage){
                $usermodel = Users::findOne($userpackage->user_id);
                if(!empty($usermodel)){
                    $usermodel->property_credited = 10;
                    $usermodel->membership_expire_date = NULL;
                    $usermodel->updated_at = date("Y-m-d H:i:s");
                    $usermodel->save(false);
                }
            }
        }


    }
    public function actionGetsignedpdf()
    {
        $mscrequests = Msc::find()->where(['status' => 'Approved'])->orderBy(['id' => SORT_DESC])->all();
        if (!empty($mscrequests)) {
            foreach ($mscrequests as $mscrequest) {
                if ($mscrequest->request->status == 'Rented') {
                    $landlord_id = $mscrequest->request->landlord_id;
                    $tenant_id = $mscrequest->request->user_id;
                    $request_id = $mscrequest->request_id;
                    $model = BookingRequests::findOne($request_id);
                    if ($mscrequest->user_id == $landlord_id && $mscrequest->pdf != '') {
                        if ($mscrequest->status == 'Completed') {
                            $tenantmscmodel = Msc::find()->where(['user_id' => $tenant_id, 'request_id' => $request_id, 'status' => 'Approved'])->one();
                            if (!empty($tenantmscmodel)) {
                                if ($tenantmscmodel->pdf != '') {
                                    $tenantmscmodel->pdf = $mscrequest->signedpdf;
                                    $tenantmscmodel->save(false);

                                }
                                $signpdftenantresponse = $this->signpdf($tenantmscmodel, $model);
                                if (!empty($signpdftenantresponse) && isset($signpdftenantresponse['return']) && !empty($signpdftenantresponse['return']) && $signpdftenantresponse['return']['statusCode'] = '000') {
                                    $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                                    $tenantmscmodel->signedpdf = $signpdftenantresponse['return']['signedPdfInBase64'];
                                    $tenantmscmodel->status = 'Completed';
                                    $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                                    if ($tenantmscmodel->save(false)) {
                                        $model->signed_agreement = $signpdftenantresponse['return']['signedPdfInBase64'];
                                        $model->updated_at = date('Y-m-d H:i:s');
                                        $decoded = base64_decode($model->signed_agreement);
                                        $filename = "signedagreement_".time().$model->reference_no.'.pdf';
                                        file_put_contents('uploads/agreements/'.$filename,$decoded);
                                        $model->signed_agreement_document = 'uploads/agreements/'.$filename;
                                        //$model->status = 'Agreement Processed';
                                        $model->save(false);

                                    }

                                } else {
                                    $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                                    $tenantmscmodel->save(false);

                                }

                            } else {


                            }
                        } else if ($mscrequest->status == 'Approved' && $mscrequest->x1!='' && $mscrequest->y1!='') {
                            $signpdfresponse = $this->signpdf($mscrequest, $model);
                            if (!empty($signpdfresponse) && isset($signpdfresponse['return']) && !empty($signpdfresponse['return']) && $signpdfresponse['return']['statusCode'] = '000') {
                                $mscrequest->signpdf_response = json_encode($signpdfresponse);
                                $mscrequest->signedpdf = $signpdfresponse['return']['signedPdfInBase64'];
                                $mscrequest->status = 'Completed';
                                $mscrequest->updated_at = date('Y-m-d H:i:s');
                                $mscrequest->save(false);
                                if (isset($signpdfresponse['return']['signedPdfInBase64']) && $signpdfresponse['return']['signedPdfInBase64'] != '') {                             $mscrequest->signpdf_response = json_encode($signpdfresponse);
                                    $mscrequest->signedpdf = $signpdfresponse['return']['signedPdfInBase64'];
                                    $mscrequest->status = 'Completed';
                                    $mscrequest->updated_at = date('Y-m-d H:i:s');
                                    $mscrequest->save(false);
                                    if(isset($signpdfresponse['return']['signedPdfInBase64']) && $signpdfresponse['return']['signedPdfInBase64']!=''){
                                        $tenantmscmodel = Msc::find()->where(['user_id' => $tenant_id, 'request_id' => $request_id, 'status' => 'Approved'])->one();

                                        $tenantmscmodel->pdf = $signpdfresponse['return']['signedPdfInBase64'];
                                        $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                                        $tenantmscmodel->save(false);
                                        $signpdftenantresponse = $this->signpdf($tenantmscmodel,$model);
                                        if(!empty($signpdftenantresponse) &&  isset($signpdftenantresponse['return']) && !empty($signpdftenantresponse['return']) && $signpdftenantresponse['return']['statusCode']='000') {
                                            $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                                            $tenantmscmodel->signedpdf = $signpdftenantresponse['return']['signedPdfInBase64'];
                                            $tenantmscmodel->status = 'Completed';
                                            $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                                            if($tenantmscmodel->save(false)){
                                                $model->signed_agreement = $signpdftenantresponse['return']['signedPdfInBase64'];
                                                $model->updated_at = date('Y-m-d H:i:s');
                                                $decoded = base64_decode($model->signed_agreement);
                                                $filename = "signedagreement_".time().$model->reference_no.'.pdf';
                                                file_put_contents('uploads/agreements/'.$filename,$decoded);
                                                    $model->signed_agreement_document = 'uploads/agreements/'.$filename;
                                                //$model->status = 'Agreement Processed';
                                                $model->save(false);

                                            }

                                        }else{
                                            $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                                            $tenantmscmodel->save(false);
//
                                        }

                                    }else{
                                        $mscrequest->signpdf_response = json_encode($signpdfresponse);
                                        $mscrequest->save(false);


                                    }



                                }

                            }
                        }
                    }
                }


            }
        }
    }

    private function signpdf($mscmodel,$model){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "ec2-13-250-42-162.ap-southeast-1.compute.amazonaws.com/MTSAPilot/MyTrustSignerAgentWS?wsdl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:mtsa=\"http://mtsa.msctg.com/\">\n   <soapenv:Header/>\n   <soapenv:Body>\n      <mtsa:SignPDF>\n         <UserID>".$mscmodel->document_no."</UserID>\n         <FullName>".$mscmodel->full_name."</FullName>\n         <!--Optional:-->\n         <AuthFactor></AuthFactor>\n\t\t<SignatureInfo>\n            <!--Optional:-->\n            <pageNo>".$mscmodel->page_no."</pageNo>\n            <!--Optional:-->\n            <pdfInBase64>".$mscmodel->pdf."</pdfInBase64>\n            <sigImageInBase64></sigImageInBase64>\n            <!--Optional:-->\n            <visibility>true</visibility>\n            <!--Optional:-->\n            <x1>".$mscmodel->x1."</x1>\n            <!--Optional:-->\n            <x2>".$mscmodel->x2."</x2>\n            <!--Optional:-->\n            <y1>".$mscmodel->y1."</y1>\n            <!--Optional:-->\n            <y2>".$mscmodel->y2."</y2>\n         </SignatureInfo>\n      </mtsa:SignPDF>\n   </soapenv:Body>\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "Username: rumahi",
                "Password: YcuLxvMMcXWPLRaW",
                "Content-Type: text/xml"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = new \SimpleXMLElement($response);
            $body = $xml->xpath('//SBody')[0];
            $responsearray = json_decode(json_encode((array)$body), TRUE);
            if(!empty($responsearray) &&  isset($responsearray['ns2SignPDFResponse'])  && !empty($responsearray['ns2SignPDFResponse'])){
                return $responsearray['ns2SignPDFResponse'];
            }else{
                return false;
            }
            //echo $response;exit;
        }
    }

    public function actionUploadtomsc()
    {
        $bookingrequests = BookingRequests::find()->where(['status' => 'Rented'])->andWhere(['is', 'signed_agreement', new \yii\db\Expression('null')])->all();
       // echo "<pre>";print_r($bookingrequests);exit;

        //->andWhere(['=','signed_agreement',''])->all();
        if (!empty($bookingrequests)) {
            foreach ($bookingrequests as $model) {

                $agreementdocument = $model->agreement_document;
                if ($agreementdocument != '') {

                    $tenantmscmodel = Msc::find()->where(['request_id' => $model->id, 'user_id' => $model->user_id, 'status' => 'Approved'])->orderBy(['id' => SORT_DESC])->one();
                    $landlordmscmodel = Msc::find()->where(['request_id' => $model->id, 'user_id' => $model->landlord_id, 'status' => 'Approved'])->orderBy(['id' => SORT_DESC])->one();

                    if (!empty($tenantmscmodel) && !empty($landlordmscmodel)) {
                        //echo "Sdsd";exit;

                        $b64Doc = chunk_split(base64_encode(file_get_contents($agreementdocument)));


                        $landlordmscmodel->pdf = $b64Doc;
                        $landlordmscmodel->updated_at = date('Y-m-d H:i:s');
                        if ($landlordmscmodel->save(false)) {

                            $tenantmscmodel->save(false);
                            $signpdfresponse = $this->actionSignpdf($landlordmscmodel, $model);

                            if (!empty($signpdfresponse) && isset($signpdfresponse['return']) && !empty($signpdfresponse['return']) && $signpdfresponse['return']['statusCode'] == '000') {
                                $landlordmscmodel->signpdf_response = json_encode($signpdfresponse);
                                $landlordmscmodel->signedpdf = $signpdfresponse['return']['signedPdfInBase64'];
                                $landlordmscmodel->status = 'Completed';
                                $landlordmscmodel->updated_at = date('Y-m-d H:i:s');
                                $landlordmscmodel->save(false);

                                if (isset($signpdfresponse['return']['signedPdfInBase64']) && $signpdfresponse['return']['signedPdfInBase64'] != '') {
                                    $tenantmscmodel->pdf = $signpdfresponse['return']['signedPdfInBase64'];
                                    $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                                    $tenantmscmodel->save(false);
                                    $signpdftenantresponse = $this->actionSignpdf($tenantmscmodel, $model);
                                    if (!empty($signpdftenantresponse) && isset($signpdftenantresponse['return']) && !empty($signpdftenantresponse['return']) && $signpdftenantresponse['return']['statusCode'] == '000') {
                                        $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                                        $tenantmscmodel->signedpdf = $signpdftenantresponse['return']['signedPdfInBase64'];
                                        $tenantmscmodel->status = 'Completed';
                                        $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                                        if ($tenantmscmodel->save(false)) {

                                            $model->signed_agreement = $signpdftenantresponse['return']['signedPdfInBase64'];
                                            $model->updated_at = date('Y-m-d H:i:s');
                                            $decoded = base64_decode($model->signed_agreement);
                                            $filename = "signedagreement_" . time() . $model->reference_no . '.pdf';
                                            file_put_contents('uploads/agreements/' . $filename, $decoded);
                                            $model->signed_agreement_document = 'uploads/agreements/' . $filename;
                                            //$model->status = 'Agreement Processed';
                                            $model->save(false);


                                        }

                                    } else {
                                        $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                                        $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                                        $tenantmscmodel->save(false);

//
                                    }

                                } else {
                                    $landlordmscmodel->signpdf_response = json_encode($signpdfresponse);
                                    $landlordmscmodel->updated_at = date('Y-m-d H:i:s');
                                    $landlordmscmodel->save(false);


                                }

                            } else {
                                $landlordmscmodel->signpdf_response = json_encode($signpdfresponse);
                                $landlordmscmodel->updated_at = date('Y-m-d H:i:s');
                                $landlordmscmodel->save(false);


                            }

                        } else {

                        }

                        //return $this->redirect(['index']);

                    } else {
                        Yii::$app->session->setFlash('error', "Verification process is still in Pending.Please try after verification done from MSC");
                    }


                }
            }
        }


    }
    private function actionSignpdf($mscmodel,$model){
        //echo "<pre>";  print_r($mscmodel);exit;

        $curl = curl_init();

      $sadfc=  curl_setopt_array($curl, array(
            CURLOPT_URL => "ec2-13-250-42-162.ap-southeast-1.compute.amazonaws.com/MTSAPilot/MyTrustSignerAgentWS?wsdl",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:mtsa=\"http://mtsa.msctg.com/\">\n   <soapenv:Header/>\n   <soapenv:Body>\n      <mtsa:SignPDF>\n         <UserID>".$mscmodel->document_no."</UserID>\n         <FullName>".$mscmodel->full_name."</FullName>\n         <!--Optional:-->\n         <AuthFactor></AuthFactor>\n\t\t<SignatureInfo>\n            <!--Optional:-->\n            <pageNo>".$mscmodel->page_no."</pageNo>\n            <!--Optional:-->\n            <pdfInBase64>".$mscmodel->pdf."</pdfInBase64>\n            <sigImageInBase64></sigImageInBase64>\n            <!--Optional:-->\n            <visibility>true</visibility>\n            <!--Optional:-->\n            <x1>".$mscmodel->x1."</x1>\n            <!--Optional:-->\n            <x2>".$mscmodel->x2."</x2>\n            <!--Optional:-->\n            <y1>".$mscmodel->y1."</y1>\n            <!--Optional:-->\n            <y2>".$mscmodel->y2."</y2>\n         </SignatureInfo>\n      </mtsa:SignPDF>\n   </soapenv:Body>\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "Username: rumahi",
                "Password: YcuLxvMMcXWPLRaW",
                "Content-Type: text/xml"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
            $xml = new \SimpleXMLElement($response);
            $body = $xml->xpath('//SBody')[0];
            $responsearray = json_decode(json_encode((array)$body), TRUE);

            if(!empty($responsearray) &&  isset($responsearray['ns2SignPDFResponse'])  && !empty($responsearray['ns2SignPDFResponse'])){
                return $responsearray['ns2SignPDFResponse'];
            }else{
                return false;
            }
            //echo $response;exit;
        }
    }


}
