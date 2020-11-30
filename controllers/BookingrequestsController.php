<?php

namespace app\controllers;

use app\models\AgreementTemplates;
use app\models\Msc;
use app\models\TodoItems;
use app\models\TodoList;
use kartik\mpdf\Pdf;
use Yii;
use app\models\BookingRequests;
use app\models\BookingRequestsSearch;
use yii\base\Exception;
use yii\base\Model;
use yii\base\Response;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * BookingrequestsController implements the CRUD actions for BookingRequests model.
 */
class BookingrequestsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
            'class' => AccessControl::className(),
            'only' => ['index','view','update','choosetemplate','printagreement','uploadagreement','uploadmoveout','moveoutinvoice','moveoutinvoiceupdate'],
            'rules' => [
                [
                    'actions' => ['index','view','update','choosetemplate','printagreement','uploadagreement','uploadmoveout','moveoutinvoice','moveoutinvoiceupdate'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BookingRequests models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new BookingRequestsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookingRequests model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionViewcancelbooking($id)
    {

        $query = TodoItems::find()->where(['todo_id'=>$id]);
        $model = TodoList::find()->where(['id'=>$id,'reftype'=>'Cancellation Refund'])->one();
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        //$milestones = ;

        return $this->render('viewcancelbooking', [
            'model' => $model,
            'dataProvider' => $dataProvider,

        ]);

    }
    /**
     * Creates a new BookingRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate1()
    {
        $model = new BookingRequests();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BookingRequests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'updatecreditscore';
        if ($model->load(Yii::$app->request->post())) {
            $model->report = \yii\web\UploadedFile::getInstance($model, 'report');
            if($model->validate()){
                $todomodel = TodoList::find()->where(['request_id'=>$id,'reftype'=>'Booking','status'=>'Confirmed'])->one();
                if(empty($todomodel)){
                    throw new NotFoundHttpException('The requested page does not exist.');

                }
               $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->report->extension;
               $model->credit_score_report = $newFileName;
               if($model->status=='Confirmed'){
                   $model->status = 'Pending';
               }
                $model->updated_by = Yii::$app->user->id;
                $model->updated_at = date('Y-m-d H:i:s');
               if($model->save(false)){
                   $todomodel->status = 'Pending';
                   $todomodel->save();
                   $model->report->saveAs('uploads/creditscorereports/' . $newFileName);
                   return $this->redirect(['index']);

               }else{
                   return $this->render('update', [
                       'model' => $model,
                   ]);
               }
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionChoosetemplate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'choosetemplate';
        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()) {
                $model->status = 'Agreement Processing';
                $model->subtotal += $model->stamp_duty;
                //$sst = Yii::$app->common->calculatesst($model->subtotal);
                $total_amount = $model->subtotal;
                $model->total = $total_amount;
                $model->updated_at = date('Y-m-d H:i:s');
                $model->updated_by = Yii::$app->user->id;
                if($model->save()){
                    return $this->redirect(['index']);

                }else{
                    return $this->render('choosetemplate', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('choosetemplate', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('choosetemplate', [
                'model' => $model,
            ]);
        }
    }
    public function actionUploadtomsc($id)
    {
        $model = $this->findModel($id);
//        if ($model->status!='Agreement Processing') {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
        $model->scenario = 'uploadtomsc';
        if ($model->load(Yii::$app->request->post())) {
            $model->pdf = \yii\web\UploadedFile::getInstance($model, 'pdf');

            if($model->validate()) {
                $tenantmscmodel = Msc::find()->where(['request_id'=>$id,'user_id'=>$model->user_id,'status'=>'Completed'])->orderBy(['id'=>SORT_DESC])->one();
                $landlordmscmodel = Msc::find()->where(['request_id'=>$id,'user_id'=>$model->landlord_id,'status'=>'Completed'])->orderBy(['id'=>SORT_DESC])->one();
//                if(empty($tenantmscmodel) || empty($landlordmscmodel)){
//                    Yii::$app->session->setFlash('error', "Verification process is still in Pending.Please try after verification done from MSC");
//                    return $this->redirect(['index']);
//
//                }
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->pdf->extension;
                $model->pdf->saveAs('uploads/agreements/' . $newFileName);
                if($_SERVER['HTTP_HOST'] != 'rumah.test') {
                    $baseurl = Url::base('https');
                }else{
                    $baseurl = Url::base(true);
                }
                $b64Doc = chunk_split(base64_encode(file_get_contents('uploads/agreements/' . $newFileName)));

                $landlordmscmodel->x1 = $model->landlordx1;
                $landlordmscmodel->y1 = $model->landlordy1;
                $landlordmscmodel->x2 = $model->landlordx2;
                $landlordmscmodel->y2 = $model->landlordy2;
                $landlordmscmodel->page_no = $model->landlordpageno;
                $landlordmscmodel->pdf = $b64Doc;
                $landlordmscmodel->updated_at = date('Y-m-d H:i:s');
                if($landlordmscmodel->save(false)){
                    $tenantmscmodel->x1 = $model->tenantx1;
                    $tenantmscmodel->x2 = $model->tenantx2;
                    $tenantmscmodel->y1 = $model->tenanty1;
                    $tenantmscmodel->y2 = $model->tenanty2;
                    $tenantmscmodel->page_no = $model->tenantpageno;
                    $tenantmscmodel->save(false);
                   $signpdfresponse = $this->actionSignpdf($landlordmscmodel,$model);
                   if(!empty($signpdfresponse) &&  isset($signpdfresponse['return']) && !empty($signpdfresponse['return']) && $signpdfresponse['return']['statusCode']='000'){
                       $landlordmscmodel->signpdf_response = json_encode($signpdfresponse);
                       $landlordmscmodel->signedpdf = $signpdfresponse['return']['signedPdfInBase64'];
                       $landlordmscmodel->status = 'Completed';
                       $landlordmscmodel->updated_at = date('Y-m-d H:i:s');
                       $landlordmscmodel->save(false);
                       if(isset($signpdfresponse['return']['signedPdfInBase64']) && $signpdfresponse['return']['signedPdfInBase64']!=''){
                           $tenantmscmodel->pdf = $signpdfresponse['return']['signedPdfInBase64'];
                           $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                           $tenantmscmodel->save(false);
                           $signpdftenantresponse = $this->actionSignpdf($tenantmscmodel,$model);
                           //echo "<pre>";print_r($signpdftenantresponse);exit;
                           if(!empty($signpdftenantresponse) &&  isset($signpdftenantresponse['return']) && !empty($signpdftenantresponse['return']) && $signpdftenantresponse['return']['statusCode']='000') {
                               $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                               $tenantmscmodel->signedpdf = $signpdftenantresponse['return']['signedPdfInBase64'];
                               $tenantmscmodel->status = 'Completed';
                               $tenantmscmodel->updated_at = date('Y-m-d H:i:s');
                               if($tenantmscmodel->save(false)){
                                   $model->signed_agreement = $signpdftenantresponse['return']['signedPdfInBase64'];
                                   $model->updated_at = date('Y-m-d H:i:s');
                                   $model->status = 'Agreement Processed';
                                   $model->save(false);
                                   Yii::$app->session->setFlash('success', "You have done with Digital Signing.You can download Signed Agreement.");

                                   return $this->redirect(['index']);

                               }

                           }else{
                               $tenantmscmodel->signpdf_response = json_encode($signpdftenantresponse);
                               $tenantmscmodel->save(false);
                               Yii::$app->session->setFlash('success', "Signing process is still in Pending.MSC will send Signed Agreement Once it is done");
                               return $this->redirect(['index']);
//
                           }

                       }else{
                           $landlordmscmodel->signpdf_response = json_encode($signpdfresponse);
                           $landlordmscmodel->save(false);
                           Yii::$app->session->setFlash('error', "There is something went wrong with MSC.Please check with them.");
                           return $this->redirect(['index']);


                       }

                   }else{
                       $landlordmscmodel->signpdf_response = json_encode($signpdfresponse);
                       $landlordmscmodel->save(false);
                       Yii::$app->session->setFlash('error', "There is something went wrong with MSC.Please check with them.");
                       return $this->redirect(['index']);

                   }

                }else{
                    return $this->render('uploadtomsc', [
                        'model' => $model,
                    ]);
                }


            }else{
                return $this->render('uploadtomsc', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadtomsc', [
                'model' => $model,
            ]);
        }
    }
    private function actionSignpdf($mscmodel,$model){

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

    public function actionPrintagreement($id)
    {
        $model = $this->findModel($id);
        $content = $model->document_content;
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,

            'filename' => 'agreement_'.$model->reference_no.'.pdf',

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
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['defaultheaderline' => 0,  //for header
                'defaulfooterline' => 0,  //for footer
                'title' => 'Customer Invoice'
        ],
            // call mPDF methods on the fly
            'methods' => [
                //'SetHeader' => ['Krajee Report Header'],
                //'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    public function actionDownload($id){
        $model = $this->findModel($id);
        if ($model->status!='Agreement Processed' || $model->signed_agreement=='') {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $decoded = base64_decode($model->signed_agreement);
        $pdf_base64 = 'samplerumah.pdf';
        //$pdf_base64 = "base64pdf.txt";
//Get File content from txt file

//Decode pdf content
        $pdf_decoded = $decoded;
//Write data back to pdf file
        $pdf = fopen ('samplerumah.pdf','r');
        fwrite ($pdf,$pdf_decoded);
        fclose ($pdf);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($pdf_base64));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($pdf_base64));
        header("Content-Type: application/pdf");
        readfile($pdf_base64);

//close output file
        echo 'Done';


    }
    public function actionContent()
    {
        if(!empty($_POST)){
            $template_id = $_POST['template'];
            $request_id = $_POST['request_id'];
            $templatedetails = AgreementTemplates::findOne($template_id);
            $requestdetails = BookingRequests::findOne($request_id);
            $content = Yii::$app->common->replaceLetterContent($templatedetails->document,$requestdetails);
            echo json_encode(array('content'=>$content));
            exit;
        }
    }
    public function actionUploadagreement($id)
    {
        $model = $this->findModel($id);
        $todomodel = TodoList::find()->where(['request_id'=>$model->id,'reftype'=>'Booking'])->one();
        $model->scenario = 'uploadagreement';
        if ($model->load(Yii::$app->request->post())) {
            $model->agreement = \yii\web\UploadedFile::getInstance($model, 'agreement');
            if(!empty($_FILES) && isset($_FILES['BookingRequests']['name']['stampdutycertificate']) && $_FILES['BookingRequests']['name']['stampdutycertificate']!='') {
                $model->stampdutycertificate = \yii\web\UploadedFile::getInstance($model, 'stampdutycertificate');
            }
            if($model->validate()) {
                $newFileName = \Yii::$app->security
                        ->generateRandomString().'.'.$model->agreement->extension;
                $model->agreement_document = $newFileName;
                if(!empty($_FILES) && isset($_FILES['BookingRequests']['name']['stampdutycertificate']) && $_FILES['BookingRequests']['name']['stampdutycertificate']!='') {
                    $newFileName1 = \Yii::$app->security
                            ->generateRandomString().'.'.$model->stampdutycertificate->extension;
                    $model->stampduty_certificate = $newFileName1;

                }
                $oldstatus = $model->status;
                    if($model->status=='Agreement Processed'){
                    $model->status = 'Payment Requested';
                }
                $model->updated_at = date('Y-m-d H:i:s');
                $model->updated_by = Yii::$app->user->id;
                if($model->save(false)){
                    if($oldstatus =='Agreement Processed') {
                        $todomodel->status = 'Unpaid';
                        $todomodel->updated_at = date('Y-m-d H:i:s');
                        $todomodel->save(false);
                    }
                    $model->agreement->saveAs('uploads/agreements/' . $newFileName);
                    if(!empty($_FILES) && isset($_FILES['BookingRequests']['name']['stampdutycertificate']) && $_FILES['BookingRequests']['name']['stampdutycertificate']!='') {
                        $model->stampdutycertificate->saveAs('uploads/agreements/' . $newFileName1);

                    }
                        return $this->redirect(['index']);

                }else{
                    return $this->render('uploadagreement', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('uploadagreement', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadagreement', [
                'model' => $model,
            ]);
        }
    }
    public function actionUploadmovein($id)
    {
        $model = $this->findModel($id);
        $todomodel = TodoList::find()->where(['request_id'=>$model->id,'reftype'=>'Booking'])->one();
        $model->scenario = 'uploadmovein';
        if ($model->load(Yii::$app->request->post())) {
            $model->movein = \yii\web\UploadedFile::getInstance($model, 'movein');
            if($model->validate()) {
                $newFileName1 = \Yii::$app->security
                        ->generateRandomString().'.'.$model->movein->extension;
                $model->movein_document = $newFileName1;

                $model->updated_at = date('Y-m-d H:i:s');
                $model->updated_by = Yii::$app->user->id;

                if($model->save()){
                    $model->movein->saveAs('uploads/moveinout/' . $newFileName1);
                    return $this->redirect(['index']);

                }else{
                    return $this->render('uploadmovein', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('uploadmovein', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadmovein', [
                'model' => $model,
            ]);
        }
    }
    public function actionUploadmoveout($id)
    {

        $model = $this->findModel($id);
        if($model->status!='Rented'){
            return $this->redirect(['index']);
        }
        $model->scenario = 'uploadmoveout';
        if ($model->load(Yii::$app->request->post())) {
            $model->moveout = \yii\web\UploadedFile::getInstance($model, 'moveout');
            if($model->validate()) {
                $newFileName1 = \Yii::$app->security
                        ->generateRandomString().'.'.$model->moveout->extension;
                $model->moveout_document = $newFileName1;
                $model->moveout_date = date('Y-m-d',strtotime($model->moveout_date));
                $model->updated_at = date('Y-m-d H:i:s');
                $model->updated_by = Yii::$app->user->id;

                if($model->save()){
                    $model->moveout->saveAs('uploads/moveinout/' . $newFileName1);
                    return $this->redirect(['index']);

                }else{
                    return $this->render('uploadmoveout', [
                        'model' => $model,
                    ]);
                }
            }else{
                return $this->render('uploadmoveout', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('uploadmoveout', [
                'model' => $model,
            ]);
        }
    }
    public function actionMoveoutinvoice($id)
    {

        $model = $this->findModel($id);
        if($model->status!='Rented'){
            return $this->redirect(['index']);
        }
        $modelCustomer = new TodoList();
        $modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {

            $modelsAddress = Model::createMultiple(TodoItems::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($modelCustomer)
                );
            }

            // validate all models
            $valid = $modelCustomer->validate();
            $valid = Model::validateMultiple($modelsAddress) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $modelCustomer->property_id = $model->property_id;
                    $modelCustomer->request_id = $id;
                    $modelCustomer->user_id = $model->user_id;
                    $modelCustomer->reftype = "Moveout Refund";
                    $modelCustomer->status = "Pending";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    $modelCustomer->updated_at = date('Y-m-d H:i:s');

                    if ($flag = $modelCustomer->save(false)) {
                        $total = 0;
                        foreach ($modelsAddress as $modelAddress) {
                            $total+=$modelAddress->price+$modelAddress->platform_deductible;
                            $modelAddress->reftype = "Refund";
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                        }
                        $sst = Yii::$app->common->calculatesst($total);
                        $grandtotal = $total+$sst;
                        $modelCustomer->subtotal = $total;
                        $modelCustomer->sst = $sst;
                        $modelCustomer->total = $grandtotal;
                        $modelCustomer->save(false);

                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('uploadmoveoutinvoice', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress,
                'type'=>'moveoutinvoice'
            ]);
        }

    }
    public function actionMoveoutinvoiceupdate($id)
    {

        $model = $this->findModel($id);
        if($model->status!='Rented'){
            return $this->redirect(['index']);
        }
        $modelCustomer = TodoList::find()->where(['request_id'=>$id,'reftype'=>'Moveout Refund','status'=>'Pending'])->one();
        $modelsAddress = $modelCustomer->todoItems;

        if (!empty($_POST)) {

            $oldIDs = ArrayHelper::map($modelsAddress, 'id', 'id');
            $modelsAddress = Model::createMultiple(TodoItems::classname(), $modelsAddress);
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAddress, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($modelCustomer)
                );
            }

            // validate all models
            $valid = $modelCustomer->validate();
            $valid = Model::validateMultiple($modelsAddress) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelCustomer->save(false)) {
                        if (! empty($deletedIDs)) {
                            TodoItems::deleteAll(['id' => $deletedIDs]);
                        }
                        $total = 0;
                        foreach ($modelsAddress as $modelAddress) {
                            $total+=$modelAddress->price+$modelAddress->platform_deductible;

                            $modelAddress->reftype = "Refund";
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        $sst = Yii::$app->common->calculatesst($total);
                        $grandtotal = $total+$sst;
                        $modelCustomer->subtotal = $total;
                        $modelCustomer->sst = $sst;
                        $modelCustomer->total = $grandtotal;
                        $modelCustomer->save(false);

                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelCustomer->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('uploadmoveoutinvoice', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress,
                'type'=>'moveoutinvoice'
            ]);
        }

    }
    public function actionCancelbooking($id)
    {

        $model = $this->findModel($id);
        if($model->status!='Agreement Processed'){
            return $this->redirect(['index']);
        }
        $modelCustomer = new TodoList();
        $modelsAddress = [new TodoItems()];
        if (!empty($_POST)) {

            $modelsAddress = Model::createMultiple(TodoItems::classname());
            Model::loadMultiple($modelsAddress, Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($modelCustomer)
                );
            }

            // validate all models
            $valid = $modelCustomer->validate();
            $valid = Model::validateMultiple($modelsAddress) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $model->status = 'Refund Requested';
                    $model->updated_at = date('Y-m-d H:i:s');
                    $model->updated_by = Yii::$app->user->id;
                    $model->save(false);
                    $modelCustomer->property_id = $model->property_id;
                    $modelCustomer->request_id = $id;
                    $modelCustomer->user_id = $model->user_id;
                    $modelCustomer->reftype = "Cancellation Refund";
                    $modelCustomer->status = "Pending";
                    $modelCustomer->created_at = date('Y-m-d H:i:s');
                    if ($flag = $modelCustomer->save(false)) {
                        foreach ($modelsAddress as $modelAddress) {
                            $modelAddress->todo_id = $modelCustomer->id;
                            $modelAddress->reftype = "Refund";
                            $modelAddress->created_at = date('Y-m-d H:i:s');
                            if (! ($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }else {
            return $this->render('uploadmoveoutinvoice', [
                'model' => $model,
                'modelCustomer' => $modelCustomer,
                'modelsAddress' => (empty($modelsAddress)) ? [new TodoItems()] : $modelsAddress,
                'type'=>'cancelbooking'
            ]);
        }

    }
    /**
     * Deletes an existing BookingRequests model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BookingRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookingRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookingRequests::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
