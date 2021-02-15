<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\BookingRequests;
use app\models\Cronjobs;
use app\models\Msc;
use app\models\Properties;
use app\models\TodoList;
use app\models\Users;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

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
                            $rentalmodel = new TodoList();
                            $rentalmodel->request_id = $request->id;
                            $rentalmodel->property_id = $request->property_id;
                            $rentalmodel->user_id = $request->user_id;
                            $rentalmodel->landlord_id = $request->landlord_id;
                            $rentalmodel->rent_startdate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                            $rentalmodel->rent_enddate = $date;
                            $rentalmodel->pay_from = 'Tenant';
                            $rentalmodel->subtotal = $request->monthly_rental;
                            $rentalmodel->total = $request->monthly_rental;
                            $rentalmodel->reftype = 'Monthly Rental';
                            $rentalmodel->status = 'Unpaid';
                            $rentalmodel->created_at = date('Y-m-d H:i:s');
                            $rentalmodel->save(false);
                            //$subject = 'Monthly Rental Reminder';
                            //$textmessage = 'Kindly ensure your bank account has sufficient fund to be debited for this month rental on this 5th of the month to avoid any late charges.';
                            //\Yii::$app->common->Savenotification($rentalmodel->user_id,$subject,$textmessage,'',$rentalmodel->property_id,$rentalmodel->id);

                            //\Yii::$app->common->Sendpushnotification($rentalmodel->user_id,$subject,$textmessage,'User');
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
    public function actionUpdaterequeststatus()
    {
        $mscrequests = Msc::find()->where(['in', 'status', ['Pending MSC Approval', 'Need Activation','Pending']])->all();
        if (!empty($mscrequests)) {
            foreach ($mscrequests as $mscrequest) {
                $getrequeststatus = array();
                $getactivationlink = array();
                if (($mscrequest->request->status == 'Pending MSC Approval' && $mscrequest->status == 'Pending MSC Approval') || $mscrequest->status=='Pending') {
                    $getrequeststatus = $this->Getrequeststatus($mscrequest);
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

                    $getrequeststatus = $this->Getrequeststatus($mscrequest);
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
        $cronjob = new Cronjobs();
        $cronjob->type = 'Get MSC request status';
        $cronjob->created_at = date('Y-m-d H:i:s');
        $cronjob->save(false);
    }
    public function actionGetsignedpdf()
    {
        $mscrequests = Msc::find()->where(['status' => 'Approved'])->orderBy(['id' => SORT_DESC])->all();
        if (!empty($mscrequests)) {
            foreach ($mscrequests as $mscrequest) {
                if ($mscrequest->request->status == 'Agreement Processing') {
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
                                        $model->status = 'Agreement Processed';
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
                                                $model->status = 'Agreement Processed';
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
        $cronjob = new Cronjobs();
        $cronjob->type = 'Get Signed PDF';
        $cronjob->created_at = date('Y-m-d H:i:s');
        $cronjob->save(false);
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
    public function actionSendappointmentreminder(){
        date_default_timezone_set("Asia/Kuala_Lumpur");

        $tomorrow = date("Y-m-d", strtotime('tomorrow'));
        $appointments = TodoList::find()->where(['reftype'=>'Appointment','status'=>'Pending'])->andWhere(['appointment_date'=>$tomorrow])->orWhere(['appointment_date'=>date('Y-m-d')])->all();
        //print_r($appointments);exit;
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
                        \Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->agent_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');

                        \Yii::$app->common->Savenotification($appointment->agent_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->agent_id,$subject,$textmessage,'Partner');

                    }else if($appointment->landlord_id!=''){
                        \Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->landlord_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');
                        \Yii::$app->common->Savenotification($appointment->landlord_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->landlord_id,$subject,$textmessage,'User');

                    }

                }elseif (date('Y-m-d')==$appointment['appointment_date'] && $twohours==$nowtime){

                    $subject = 'Viewing appointment reminder 2 hours before';
                    $textmessage = 'You have a viewing appointment to attend 2 hours later. Get prepared.';
                    if($appointment->agent_id!=''){
                        \Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->agent_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');

                        \Yii::$app->common->Savenotification($appointment->agent_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->agent_id,$subject,$textmessage,'Partner');

                    }else if($appointment->landlord_id!=''){
                        \Yii::$app->common->Savenotification($appointment->user_id,$subject,$textmessage,$appointment->landlord_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->user_id,$subject,$textmessage,'User');
                        \Yii::$app->common->Savenotification($appointment->landlord_id,$subject,$textmessage,$appointment->user_id,$appointment->property_id,$appointment->id);

                        \Yii::$app->common->Sendpushnotification($appointment->landlord_id,$subject,$textmessage,'User');

                    }
                }

            }
        }

    }


    public function actionSendunpaidbillreminder(){
        date_default_timezone_set("Asia/Kuala_Lumpur");

        $yesterday = date("Y-m-d", strtotime('yesterday'));
        $bills = TodoList::find()->where(['reftype'=>'General','status'=>'Unpaid'])->andWhere(['due_date'=>$yesterday])->all();
        if(!empty($bills)){
            foreach ($bills as $bill){
                $subject = 'Unpaid bill pending';
                $textmessage = 'You got one unpaid bill has been due, kindly settle now to avoid any late charges.';

                if($bill->pay_from=='Landlord'){
                    \Yii::$app->common->Savenotification($bill->landlord_id,$subject,$textmessage,'',$bill->property_id,$bill->id);

                    \Yii::$app->common->Sendpushnotification($bill->landlord_id,$subject,$textmessage,'User');

                }else if($bill->pay_from=='Tenant'){
                    \Yii::$app->common->Savenotification($bill->user_id,$subject,$textmessage,'',$bill->property_id,$bill->id);

                    \Yii::$app->common->Sendpushnotification($bill->user_id,$subject,$textmessage,'User');

                }

            }
        }

    }

    public function actionSendunpaidrentalreminder(){
        date_default_timezone_set("Asia/Kuala_Lumpur");

        $yesterday = date("Y-m-d", strtotime('-7 days'));
        //echo $yesterday;exit;
        $bills = TodoList::find()->where(['reftype'=>'Monthly Rental','status'=>'Unpaid'])->andWhere(['DATE(created_at)'=>$yesterday])->all();
        //echo "<pre>";print_r($bills);exit;
        if(!empty($bills)){
            foreach ($bills as $bill){
                $subject = 'Outstanding monthly rental still pending';
                $textmessage = 'You got one outstanding rental has been due more than 7 days, kindly settle now to avoid breach of contract & deposits forfeited.';


                \Yii::$app->common->Savenotification($bill->user_id,$subject,$textmessage,'',$bill->property_id,$bill->id);

                \Yii::$app->common->Sendpushnotification($bill->user_id,$subject,$textmessage,'User');



            }
        }

    }

}
