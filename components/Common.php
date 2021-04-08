<?php
namespace app\components;


use app\models\BookingRequests;
use app\models\Devices;
use app\models\GoldTransactions;
use app\models\Notifications;
use app\models\PlatformFees;
use app\models\PromoCodes;
use app\models\ServiceRequests;
use app\models\TodoList;
use app\models\Transactions;
use app\models\TransactionsItems;
use app\models\Users;
use paragraph1\phpFCM\Recipient\Device;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\debug\models\search\User;

class Common extends Component
{

    public function amenities()
    {
        return array('Air-conditioning' => 'Air-conditioning','Wifi'=>'Wifi','Washing Machine'=>'Washing Machine',
            'Cooking Allowed'=>'Cooking Allowed','Individual Meter Reader'=>'Individual Meter Reader','Mini Market'=>'Mini Market',
            'Swimming Pool'=>'Swimming Pool','Gymnasium'=>'Gymnasium','24hrs Security'=>'24hrs Security','Playground'=>'Playground',
            'Surau'=>'Surau');


    }

    public function Commute()
    {
        return array('Nearby' => 'Nearby','MRT'=>'MRT','LRT'=>'LRT',
            'KTM'=>'KTM','Monorail'=>'Monorail','Bus Station'=>'Bus Station');


    }

    public function Propertytype()
    {
        return array('House' => 'House','Flat'=>'Flat','Apartment'=>'Apartment',
            'Condominimum'=>'Condominimum','Studio'=>'Studio','Town House'=>'Town House','Terrace'=>'Terrace','Semi-D'=>'Semi-D',
            'Bungalow'=>'Bungalow');


    }

    public function Roomtype()
    {
        return array('Single' => 'Single','Medium'=>'Medium','Master'=>'Master',
            'Duplex'=>'Duplex','Entire Unit'=>'Entire Unit');


    }

    public function Preference()
    {
        return array('Male' => 'Male','Female'=>'Female','Mixed Gender'=>'Mixed Gender');


    }
    public function replaceLetterContent($content,$model){
        $content = str_replace("#tenantname#",$model->user->full_name,$content);
        $content = str_replace("#TENANTNAME#",$model->user->full_name,$content);

        $content = str_replace("#landlordname#",$model->landlord->full_name,$content);
        $content = str_replace("#LANDLORDNAME#",$model->landlord->full_name,$content);

        $content = str_replace("#landlordidentitycardno#",$model->landlord->document_no,$content);
        $content = str_replace("#tenantidentitycardno#",$model->user->document_no,$content);
        $content = str_replace("#agreementcreationdate#",date('d/m/Y',strtotime($model->updated_at)),$content);
        $content = str_replace("#propertydetails#",$model->property->title,$content);
        $content = str_replace("#landlordcontactno#",$model->landlord->contact_no,$content);
        $content = str_replace("#tenantcontactno#",$model->user->contact_no,$content);
        $content = str_replace("#rentalcommencementdate#",date('d/m/Y',strtotime($model->commencement_date)),$content);
        $effectiveDate = date('d/m/Y', strtotime("+".$model->tenancy_period." months", strtotime($model->commencement_date)));

        $content = str_replace("#tenancyperiod#",$model->tenancy_period." Months",$content);
        $content = str_replace("#rentalexpirydate#",$effectiveDate,$content);
        $content = str_replace("#monthlyrental#",$model->monthly_rental.".00",$content);
        $content = str_replace("#securitydeposit#",$model->security_deposit.".00",$content);
        $content = str_replace("#utilitydeposit#",$model->utilities_deposit.".00",$content);
        $content = str_replace("#keycarddeposit#",$model->keycard_deposit.".00",$content);


        return $content;
    }
    public function getReftype($status)
    {
        switch ($status){
            case "Cleaner";
                return "<span class='btn bg-purple btn-xs'>Cleaner</span>";
                break;
            case "Laundry";
                return "<span class='btn bg-green btn-xs'>Laundry</span>";
                break;
            case "Handyman";
                return "<span class='btn btn-danger btn-xs'>Handyman</span>";
                break;
            case "Mover";
                return "<span class='btn bg-blue btn-xs'>Mover</span>";
                break;

        }
    }

    public function getGolcointype($status)
    {
        switch ($status){
            case "Rental On Time";
                return "<span class='btn bg-purple btn-xs'>Rental On Time</span>";
                break;
            case "In App Purchase";
                return "<span class='btn bg-green btn-xs'>In App Purchase</span>";
                break;
            case "Onboarding";
                return "<span class='btn btn-danger btn-xs'>Onboarding</span>";
                break;
            case "Tenancy signed";
                return "<span class='btn bg-blue btn-xs'>Tenancy signed</span>";
                break;
            case "1st Property Listed";
                return "<span class='btn bg-orange btn-xs'>1st Property Listed</span>";
                break;
            case "1st Rent Listed";
                return "<span class='btn bg-orange btn-xs'>1st Rent Listed</span>";
                break;

        }
    }

    public function getStatus($status)
    {
        switch ($status){
            case "New";
                return "<span class='btn btn-warning btn-xs'>New</span>";
                break;
            case "Active";
                return "<span class='btn btn-warning btn-xs'>Active</span>";
                break;
            case "Pending";
                return "<span class='btn btn-warning btn-xs'>Pending</span>";

                break;
            case "Approved";
                return "<span class='btn bg-green btn-xs'>Approved</span>";
                break;
            case "Accepted";
                return "<span class='btn bg-green btn-xs'>Accepted</span>";
                break;
            case "Completed";
                return "<span class='btn bg-green btn-xs'>Completed</span>";
                break;
            case "Agreement Processed";
                return "<span class='btn bg-orange btn-xs'>Agreement Processed</span>";
                break;
            case "Agreement Processing";
                return "<span class='btn bg-blue btn-xs'>Agreement Processing</span>";
                break;
            case "Refund Requested";
                return "<span class='btn bg-orange btn-xs'>Refund Requested</span>";
                break;
            case "Pending MSC Approval";
                return "<span class='btn bg-orange btn-xs'>Pending MSC Approval</span>";
                break;
            case "Confirmed";
                return "<span class='btn bg-orange btn-xs'>Confirmed</span>";
                break;
            case "Work In Progress";
                return "<span class='btn bg-orange btn-xs'>Work In Progress</span>";
                break;
            case "Picked Up";
                return "<span class='btn bg-blue btn-xs'>Picked Up</span>";
                break;
            case "Rented";
                return "<span class='btn bg-olive btn-xs'>Rented</span>";
                break;
            case "In Progress";
                return "<span class='btn bg-purple btn-xs'>In Progress</span>";
                break;
            case "Processing";
                return "<span class='btn bg-blue btn-xs'>Processing</span>";
                break;
            case "Processed";
                return "<span class='btn bg-blue btn-xs'>Processed</span>";
                break;
            case "Declined";
                return "<span class='btn btn-danger btn-xs'>Declined</span>";
                break;
            case "Rejected";
                return "<span class='btn btn-danger btn-xs'>Rejected</span>";
                break;
            case "Cancelled";
                return "<span class='btn btn-danger btn-xs'>Cancelled</span>";
                break;
            case "Closed";
                return "<span class='btn btn-danger btn-xs'>Closed</span>";
                break;
            case "Unpaid";
                return "<span class='btn btn-danger btn-xs'>Unpaid</span>";
                break;
            case "Paid";
                return "<span class='btn bg-green btn-xs'>Paid</span>";
                break;
            case "Refunded";
                return "<span class='btn bg-green btn-xs'>Refunded</span>";
                break;
            case "Moved Out";
                return "<span class='btn bg-blue btn-xs'>Moved Out</span>";
                break;
            case "Payment Requested";
                return "<span class='btn bg-blue btn-xs'>Payment Requested</span>";
                break;
            case "Out For Delivey";
                return "<span class='btn bg-blue btn-xs'>Out For Delivey</span>";
                break;
            case "Inactive";
                return "<span class='btn btn-danger btn-xs'>Inactive</span>";
                break;
            case "Suspended";
                return "<span class='btn btn-danger btn-xs'>Suspended</span>";
                break;
            case "Terminated";
                return "<span class='btn btn-danger btn-xs'>Terminated</span>";
                break;
            case "Incompleted";
                return "<span class='btn btn-danger btn-xs'>Incompleted</span>";
                break;
        }
    }
    public function processBase64($data){
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }

            return ['data'=>$data, 'type'=>$type];
        } else {
            throw new \Exception('did not match data URI with image data');
        }
    }
    public function processBase64pdf($data){
        if (preg_match('/^data:application\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'pdf' ])) {
                throw new \Exception('invalid pdf type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }

            return ['data'=>$data, 'type'=>$type];
        } else {
            throw new \Exception('did not match data URI with pdf data');
        }
    }
    public function generatereferencenumber($id)
    {
        if ($id && $id != null && $id != '' && is_numeric($id)) {
            $encrypted = (((((($id * 2) + 383) * 8) + 1048) - 157) - 28) * 3;
            return $encrypted;
        } else {
            return null;
        }

    }
    public function calculatesst($amount)
    {
        $sst = 6;
        $total_fees = number_format($amount * $sst / 100, 2, '.', '');
        return $total_fees;


    }
    public function getsystemaccount()
    {
        $systemaccount = Users::find()->where(['role'=>'Systemaccount'])->one();
        return $systemaccount;


    }
    public function validatesecondarypassword($user_id,$password){
        $userdetails = Users::findOne($user_id);

        return ($userdetails->secondary_password==md5($password))?true:false;


    }
    public function getplatformfees($name){
        $platformfees = PlatformFees::find()->where(['name'=>$name])->asArray()->one();

        return $platformfees;


    }
    public function addgoldcoinspurchase($user_id,$goldcoins,$transaction_id,$type='',$reffer_id=''){
        date_default_timezone_set("Asia/Kuala_Lumpur");
        if($type=='Onboarding' && $reffer_id!=''){
            $usercoinsbalance = Users::getcoinsbalance($user_id);
            if($reffer_id!='') {
                $usercoinsbalance1 = Users::getcoinsbalance($reffer_id);
            }
            $goldtransaction = new GoldTransactions();
            $goldtransaction->user_id = $user_id;
            $goldtransaction->refferer_id = $reffer_id;
            $goldtransaction->gold_coins = $goldcoins;
            $goldtransaction->transaction_id = $transaction_id;
            $goldtransaction->olduserbalance = isset($usercoinsbalance1)?$usercoinsbalance1:NULL;
            $goldtransaction->newuserbalance = $usercoinsbalance1 + $goldcoins;
            $goldtransaction->incoming = 1;
            $goldtransaction->reftype = 'Onboarding';
            $goldtransaction->status = 'Completed';
            $goldtransaction->created_at = date('Y-m-d H:i:s');
            if ($goldtransaction->save(false)) {
                //$update = Users::updatecoinsbalance($usercoinsbalance + $goldcoins, $user_id);

                if($reffer_id!='') {
                    $update = Users::updatecoinsbalance($usercoinsbalance1 + $goldcoins, $reffer_id);
                }

                if ($update) {
//                    $subject = 'Gold coins earned';
//                    $textmessage = 'Congratulation!! You just earned enormous gold coin. Goes to “My Profile” to check your balance.';
//                    $this->Savenotification($user_id,$subject,$textmessage);
//                    $this->Sendpushnotification($user_id,$subject,$textmessage,'User');

                    if($reffer_id!='') {
                        $subject1 = 'Gold coins earned';
                        $textmessage1 = 'Congratulation!! You just earned enormous gold coin. Goes to “My Profile” to check your balance.';
                        $this->Savenotification($reffer_id, $subject1, $textmessage1);
                        $this->Sendpushnotification($reffer_id, $subject1, $textmessage1, 'User');
                    }

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }elseif($type=='1st Property Listed'){
            $usercoinsbalance1 = Users::getcoinsbalance($user_id);
            $goldtransaction = new GoldTransactions();
            $goldtransaction->user_id = $user_id;
            $goldtransaction->refferer_id = NULL;
            $goldtransaction->gold_coins = $goldcoins;
            $goldtransaction->olduserbalance = $usercoinsbalance1;
            $goldtransaction->newuserbalance = $usercoinsbalance1 + $goldcoins;
            $goldtransaction->incoming = 1;
            $goldtransaction->reftype = '1st Property Listed';
            $goldtransaction->status = 'Completed';
            $goldtransaction->created_at = date('Y-m-d H:i:s');
            if ($goldtransaction->save(false)){
                $update = Users::updatecoinsbalance($usercoinsbalance1 + $goldcoins, $user_id);

                if ($update) {
                    $subject = 'Gold coins earned';
                    $textmessage = 'Congratulation!! You just earned enormous gold coin. Goes to “My Profile” to check your balance.';
                    $this->Savenotification($user_id,$subject,$textmessage);
                    $this->Sendpushnotification($user_id,$subject,$textmessage,'User');

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }elseif($type=='Tenancy Signed'){
            $usercoinsbalance1 = Users::getcoinsbalance($reffer_id);
            $goldtransaction = new GoldTransactions();
            $goldtransaction->user_id = $user_id;
            $goldtransaction->refferer_id = $reffer_id;
            $goldtransaction->gold_coins = $goldcoins;
            $goldtransaction->olduserbalance = $usercoinsbalance1;
            $goldtransaction->newuserbalance = $usercoinsbalance1 + $goldcoins;
            $goldtransaction->incoming = 1;
            $goldtransaction->reftype = 'Tenancy Signed';
            $goldtransaction->status = 'Completed';
            $goldtransaction->created_at = date('Y-m-d H:i:s');
            if ($goldtransaction->save(false)){
                $update = Users::updatecoinsbalance($usercoinsbalance1 + $goldcoins, $user_id);

                if ($update) {
                    $subject = 'Gold coins earned';
                    $textmessage = 'Congratulation!! You just earned enormous gold coin. Goes to “My Profile” to check your balance.';
                    $this->Savenotification($reffer_id,$subject,$textmessage);
                    $this->Sendpushnotification($reffer_id,$subject,$textmessage,'User');

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }   else if($type!='Onboarding') {
            $usercoinsbalance = Users::getcoinsbalance($user_id);
            $goldtransaction = new GoldTransactions();
            $goldtransaction->user_id = $user_id;
            $goldtransaction->gold_coins = $goldcoins;
            $goldtransaction->transaction_id = $transaction_id;
            $goldtransaction->olduserbalance = $usercoinsbalance;
            $goldtransaction->newuserbalance = $usercoinsbalance + $goldcoins;
            $goldtransaction->incoming = 1;
            $goldtransaction->reftype = ($type != '') ? $type : 'In App Purchase';
            $goldtransaction->status = 'Completed';
            $goldtransaction->created_at = date('Y-m-d H:i:s');
            if ($goldtransaction->save(false)) {
                $update = Users::updatecoinsbalance($usercoinsbalance + $goldcoins, $user_id);
                if ($update) {
                    $subject = 'Gold coins earned';
                    $textmessage = 'Congratulation!! You just earned enormous gold coin. Goes to “My Profile” to check your balance.';
                    $this->Savenotification($user_id,$subject,$textmessage);
                    $this->Sendpushnotification($user_id,$subject,$textmessage,'User');

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

    }
    public function deductgoldcoinspurchase($user_id,$goldcoins,$transaction_id,$type=''){
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $usercoinsbalance = Users::getcoinsbalance($user_id);
        $goldtransaction = new GoldTransactions();
        $goldtransaction->user_id = $user_id;
        $goldtransaction->gold_coins = $goldcoins;
        $goldtransaction->transaction_id = $transaction_id;
        $goldtransaction->olduserbalance =$usercoinsbalance;
        $goldtransaction->newuserbalance = $usercoinsbalance-$goldcoins;
        $goldtransaction->incoming = 0;
        $goldtransaction->reftype = 'In App Purchase';
        $goldtransaction->status = 'Completed';
        $goldtransaction->created_at = date('Y-m-d H:i:s');
        if($goldtransaction->save(false)){
            $update = Users::updatecoinsbalance($usercoinsbalance-$goldcoins,$user_id);
            if($update){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
    public function payment($user_id,$todo_id,$status,$reftype,$post,$payment_id=''){
        date_default_timezone_set("Asia/Kuala_Lumpur");
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $systemaccount = $this->getsystemaccount();
        $todomodel = TodoList::findOne($todo_id);
        $promocode = (isset($post['promo_code']) && $post['promo_code'] != '') ? $post['promo_code'] : '';
        $amount = (isset($post['amount']) && $post['amount'] != '') ? $post['amount'] : '';
        $discount = (isset($post['discount']) && $post['discount'] != '') ? $post['discount'] : 0;
        $goldcoins = (isset($post['gold_coins']) && $post['gold_coins'] != '') ? $post['gold_coins'] : 0;
        $coins_savings = (isset($post['coins_savings']) && $post['coins_savings'] != '') ? $post['coins_savings'] : 0;
        $payment_id = ($payment_id != '') ? $payment_id : '';
        if ($promocode != '') {
            $promocodedetails = PromoCodes::find()->where(['promo_code' => $promocode])->one();
        }
        $todomodel = TodoList::findOne($todo_id);
        switch ($reftype) {
            case "Booking";
                $model = BookingRequests::findOne($todomodel->request_id);
                $sst = $model->sst;
                $totalamount = $amount;
                //$totalamountafterdiscount = (int)$totalamount-(int)$discount-(int)$coins_savings;
                $amountwithoutsst = $todomodel->subtotal;
                $tenancyfees = $model->tenancy_fees;
                $totaldiscount = (int)$discount-(int)$coins_savings;
                $totaltenancyfees = $model->tenancy_fees-$totaldiscount;
                $sst =Yii::$app->common->calculatesst($totaltenancyfees);
                $tenancyfeeswithsst = $totaltenancyfees+$sst;
                $bookingfees = $model->booking_fees;
                $stamp_duty = $model->stamp_duty;
                $totaldiscount = $discount+$coins_savings;
                $subtotal = $model->security_deposit+$model->keycard_deposit+$model->utilities_deposit+$tenancyfees+$stamp_duty-$bookingfees;
                $totalcoinsamountapplied = $tenancyfees - (int)$discount-(int)$coins_savings;
                $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $model->security_deposit+$model->keycard_deposit+$model->utilities_deposit+(int)$totalcoinsamountapplied+$sst+$stamp_duty-$bookingfees;
                //$sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                //$totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount;

                $receiverbalance = Users::getbalance($model->landlord_id);
                $senderbalance = Users::getbalance($model->user_id);
                $systemaccount = Yii::$app->common->getsystemaccount();
                $systemaccountbalance = $systemaccount->wallet_balance;

                $transaction1 = Yii::$app->db->beginTransaction();

                try {


                    $transaction = new Transactions();
                    $transaction->user_id = $this->user_id;
                    $transaction->request_id = $model->id;
                    $transaction->landlord_id = $model->landlord_id;
                    $transaction->promo_code = ($promocode!='')?$promocodedetails->id:NULL;
                    $transaction->payment_id=$payment_id;
                    $transaction->amount = $subtotal;
                    $transaction->sst = $sst;
                    $transaction->discount = $discount;
                    $transaction->coins = $goldcoins;
                    $transaction->coins_savings = $coins_savings;
                    $transaction->total_amount = $totalamountafterdiscount;
                    $transaction->reftype = 'Booking Payment';
                    $transaction->status = 'Completed';
                    $transaction->created_at = date('Y-m-d H:i:s');
                    if ($transaction->save(false)) {
                        $lastid = $transaction->id;
                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                        $transaction->reference_no = $reference_no;
                        if ($transaction->save(false)) {
//                                    if($model->booking_fees>0){
//                                        $transactionitems = new TransactionsItems();
//                                        $transactionitems->sender_id = $model->user_id;
//                                        $transactionitems->receiver_id = $model->landlord_id;
//                                        $transactionitems->amount = $model->booking_fees;
//                                        $transactionitems->total_amount = $model->booking_fees;
//                                        $transactionitems->oldsenderbalance = $senderbalance;
//                                        $transactionitems->newsenderbalance = $senderbalance-$model->booking_fees;
//                                        $transactionitems->oldreceiverbalance = $receiverbalance;
//                                        $transactionitems->newreceiverbalance = $receiverbalance+$model->booking_fees;
//                                        $transactionitems->description = 'Booking Fees';
//                                        $transactionitems->created_at = date('Y-m-d H:i:s');
//                                        $transactionitems->save(false);
//                                    }
                            if($model->security_deposit>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $model->landlord_id;
                                $transactionitems->amount = $model->security_deposit;
                                $transactionitems->total_amount = $model->security_deposit;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $receiverbalance;
                                $transactionitems->newreceiverbalance = $receiverbalance+$model->security_deposit;
                                $transactionitems->description = 'Deposit';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->keycard_deposit>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $model->landlord_id;
                                $transactionitems->amount = $model->keycard_deposit;
                                $transactionitems->total_amount = $model->keycard_deposit;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $receiverbalance;
                                $transactionitems->newreceiverbalance = $receiverbalance+$model->keycard_deposit;
                                $transactionitems->description = 'Keycard Deposit';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->utilities_deposit>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $model->landlord_id;
                                $transactionitems->amount = $model->utilities_deposit;
                                $transactionitems->total_amount = $model->utilities_deposit;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $receiverbalance;
                                $transactionitems->newreceiverbalance = $receiverbalance+$model->utilities_deposit;
                                $transactionitems->description = 'Utilities Deposit';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->stamp_duty>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $systemaccount->id;
                                $transactionitems->amount = $model->stamp_duty;
                                $transactionitems->total_amount = $model->stamp_duty;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $systemaccountbalance;
                                $transactionitems->newreceiverbalance = $systemaccountbalance+$model->stamp_duty;
                                $transactionitems->description = 'Stamp Duty';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->tenancy_fees>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $systemaccount->id;
                                $transactionitems->amount = $model->tenancy_fees;
                                $transactionitems->total_amount = $model->tenancy_fees;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $systemaccountbalance;
                                $transactionitems->newreceiverbalance = $systemaccountbalance+$model->tenancy_fees;
                                $transactionitems->description = 'Tenancy Fees';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            $model->updated_by = $this->user_id;
                            $model->status = 'Rented';
                            $model->rented_at = date('Y-m-d H:i:s');
                            if ($model->save(false)) {
                                $todomodel->status = 'Paid';
                                $todomodel->save(false);
                                $months = $model->tenancy_period;
                                $effectiveDate = date('Y-m-d', strtotime("+".$months." months", strtotime($model->commencement_date)));
                                $model->property->availability = date('Y-m-d', strtotime("+".$months." months", strtotime($effectiveDate)));
                                $model->property->status = 'Rented';
                                $model->property->request_id = $model->id;
                                if($model->property->save(false)){
                                    if($model->property->agent_id!=''){
                                        $todorequest = TodoList::find()->where(['landlord_id'=>$model->landlord_id,'agent_id'=>$model->property->agent_id,'reftype'=>'Transfer Request','status'=>'Accepted','property_id'=>$model->property_id,'user_id'=>$model->user_id])->orderBy(['id'=>SORT_DESC])->one();
                                        if(!empty($todorequest)){
                                            $todorequest->status = 'Completed';
                                            $todorequest->save(false);
                                            if($todorequest->receive_via=='Rumah-i') {
                                                $commision = $todorequest->commission;
                                                $agentbalance = Users::getbalance($model->property->agent_id);
                                                $commisiontransaction = new Transactions();
                                                $commisiontransaction->reftype = 'Agent Commision';
                                                $commisiontransaction->user_id = $model->property->agent_id;
                                                $commisiontransaction->property_id = $model->property_id;
                                                $commisiontransaction->todo_id = $todorequest->id;
                                                $commisiontransaction->amount = $commision;
                                                $commisiontransaction->total_amount = $commision;
                                                $commisiontransaction->type = 'Payment';
                                                $commisiontransaction->status = 'Completed';
                                                $commisiontransaction->created_at = date('Y-m-d H:i:s');
                                                if($commisiontransaction->save(false)){
                                                    $lastid1 = $commisiontransaction->id;
                                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid1);
                                                    $commisiontransaction->reference_no = $reference_no;
                                                    $commisiontransaction->save(false);
                                                    Users::updatebalance($agentbalance+$commision,$model->property->agent_id);
                                                }else{
                                                    $transaction1->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            }
                                        }
                                    }

                                    if($goldcoins>0) {
                                        $this->deductgoldcoinspurchase($model->user_id, $goldcoins, $lastid);
                                    }
                                    $gold_coins = $totalcoinsamountapplied*1.5;
                                    $this->addgoldcoinspurchase($model->user_id,$gold_coins,$lastid);
                                    $usermodel = Users::findOne($model->user_id);
                                    if($usermodel->referred_by!='') {
                                        $checkgoldcoinsalreadyreceived = GoldTransactions::find()->where(['user_id'=>$model->user_id,'refferer_id'=>$usermodel->referred_by,'reftype'=>'Tenancy Signed'])->one();
                                        if(empty($checkgoldcoinsalreadyreceived)) {

                                            $gold_coinsreffer = 4688;
                                            $this->addgoldcoinspurchase($model->user_id, $gold_coinsreffer, $lastid, 'Tenancy Signed', $usermodel->referred_by);
                                        }
                                    }
                                    //$updatesenderbalance = Users::updatebalance($senderbalance-$totalamountafterdiscount,$model->user_id);
                                    $updatereceiverbalance = Users::updatebalance($receiverbalance+$model->rental_deposit+$model->utilities_deposit+$model->keycard_deposit,$model->landlord_id);
                                    $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$model->tenancy_fees+$model->stamp_duty+$sst,$systemaccount->id);

                                    $transaction1->commit();
                                    return array('status' => 1, 'message' => 'You have rented property successfully.');


                                }else{
                                    $transaction1->rollBack();
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }


                            }else{
                                $transaction1->rollBack();

                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }

                        }else{
                            $transaction1->rollBack();

                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        }


                    } else {
                        $transaction1->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                    }
                }catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction1->rollBack();
                }
                break;
            case "Moveout Refund";
                $todoitems = $todomodel->todoItems;


                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status=='Pending') {
                        if ($status == 'Accepted') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                if (!empty($todoitems)) {

                                    $transactionmodel = new Transactions();
                                    $transactionmodel->user_id = $user_id;
                                    $transactionmodel->landlord_id = $todomodel->request->landlord_id;
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->request_id = $todomodel->request_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->amount = $todomodel->total;
                                    $transactionmodel->sst = $todomodel->sst;
                                    $transactionmodel->total_amount = $todomodel->total;
                                    $transactionmodel->type = 'Refund';
                                    $transactionmodel->reftype = 'Moveout Refund';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save(false)) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($user_id);
                                            $senderbalance = Users::getbalance($todomodel->request->landlord_id);
                                            foreach ($todoitems as $todoitem) {

                                                if ($todoitem->platform_deductible > 0) {
                                                    $totalplatform_deductible += $todoitem->platform_deductible;
                                                    $transactionitemmodel = new TransactionsItems();
                                                    $transactionitemmodel->sender_id = $systemaccount->id;
                                                    $transactionitemmodel->transaction_id = $lastid;
                                                    $transactionitemmodel->receiver_id = $user_id;
                                                    $transactionitemmodel->amount = $todoitem->platform_deductible;
                                                    $transactionitemmodel->total_amount = $todoitem->platform_deductible;
                                                    $transactionitemmodel->oldsenderbalance = $systemaccount->wallet_balance;
                                                    $transactionitemmodel->newsenderbalance = $systemaccount->wallet_balance - $todoitem->platform_deductible;
                                                    $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                    $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->platform_deductible;
                                                    $transactionitemmodel->type = 'Refund';
                                                    $transactionitemmodel->status = 'Completed';
                                                    $transactionitemmodel->description = $todoitem->description;
                                                    $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                    if ($flag = $transactionitemmodel->save(false)) {
                                                        $totaldeductfromuser += $todoitem->price;
                                                        $transactionitemmodel1 = new TransactionsItems();
                                                        $transactionitemmodel1->transaction_id = $lastid;
                                                        $transactionitemmodel1->sender_id = $todomodel->request->landlord_id;
                                                        $transactionitemmodel1->receiver_id = $user_id;
                                                        $transactionitemmodel1->amount = $todoitem->price;
                                                        $transactionitemmodel1->total_amount = $todoitem->price;

                                                        $transactionitemmodel1->oldsenderbalance = $senderbalance;
                                                        $transactionitemmodel1->newsenderbalance = $senderbalance - $todoitem->price;
                                                        $transactionitemmodel1->oldreceiverbalance = $receiverbalance;
                                                        $transactionitemmodel1->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                        $transactionitemmodel1->type = 'Refund';
                                                        $transactionitemmodel1->status = 'Completed';

                                                        $transactionitemmodel1->description = $todoitem->description;
                                                        $transactionitemmodel1->created_at = date('Y-m-d H:i:s');
                                                        $transactionitemmodel1->save(false);
                                                        if (!($flag = $transactionitemmodel1->save(false))) {
                                                            $transaction->rollBack();
                                                            break;
                                                        }


                                                    } else {
                                                        $transaction->rollBack();
                                                        break;
                                                    }

                                                } else {
                                                    $totaldeductfromuser += $todoitem->price;
                                                    $transactionitemmodel = new TransactionsItems();
                                                    $transactionitemmodel->transaction_id = $lastid;
                                                    $transactionitemmodel->sender_id = $todomodel->request->landlord_id;
                                                    $transactionitemmodel->receiver_id = $user_id;
                                                    $transactionitemmodel->amount = $todoitem->price;
                                                    $transactionitemmodel->total_amount = $todoitem->price;

                                                    $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                    $transactionitemmodel->newsenderbalance = $senderbalance - $todoitem->price;
                                                    $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                    $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                    $transactionitemmodel->type = 'Refund';
                                                    $transactionitemmodel->status = 'Completed';
                                                    $transactionitemmodel->description = $todoitem->description;
                                                    $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                    if (!($flag = $transactionitemmodel->save(false))) {
                                                        $transaction->rollBack();
                                                        break;
                                                    }

                                                }


                                            }
                                            if ($flag) {
                                                $updatesenderbalance = Users::updatebalance($senderbalance - $totaldeductfromuser, $todomodel->request->landlord_id);
                                                $updatesystembalance = Users::updatebalance($systemaccount->wallet_balance - $totalplatform_deductible - $todomodel->sst, $systemaccount->id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaldeductfromuser + $totalplatform_deductible + $todomodel->sst, $user_id);
                                                if ($updatereceiverbalance && $updatesenderbalance && $updatesystembalance) {
                                                    $todomodel->status = 'Completed';
                                                    if ($todomodel->save(false)) {
                                                        $todomodel->property->status = 'Active';
                                                        $todomodel->property->save(false);
                                                        $todomodel->request->status = 'Moved Out';
                                                        $todomodel->request->updated_by = $user_id;
                                                        $todomodel->request->save(false);
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have accepted refund request successfully.');

                                                    } else {
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                    }

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        $transaction->rollBack();
                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }


                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = ($status == 'Rejected') ? 'Refund Rejected' : '';
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected refund request successfully.');

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }
                break;
            case "Renovation Milestone";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status=='Unpaid') {

                        if ($status == 'Accepted') {
                            $totalamount = $amount;
                            $amountwithoutsst = $todomodel->subtotal;
                            $totaldiscount = $discount+$coins_savings;
                            $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $discount - $coins_savings;
                            $sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                            $totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount;
                            $senderbalance = Users::getbalance($todomodel->landlord_id);
//                            if ($senderbalance < $totalamountafterdiscount) {
//                                return array('status' => 0, 'message' => 'You don"t have enough wallet balance');
//
//                            }
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                if (!empty($todoitems)) {

                                    $transactionmodel = new Transactions();
                                    $transactionmodel->user_id = $user_id;
                                    $transactionmodel->landlord_id = $todomodel->landlord_id;
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->renovation_quote_id = $todomodel->renovation_quote_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = ($totaldiscount>0)?$totalamount:$amountwithoutsst;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->payment_id = ($payment_id!='')?$payment_id:NULL;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'Renovation Payment';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            $senderbalance = Users::getbalance($todomodel->landlord_id);
                                            foreach ($todoitems as $todoitem) {

                                                $totaldeductfromuser += $todoitem->price;
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $totalamount;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }


                                            }
                                            if ($flag) {
                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);

//                                               if ($goldcoins > 0) {
//                                                   $usercoinsbalance = Users::getcoinsbalance($user_id);
//                                                   $goldtransaction = new GoldTransactions();
//                                                   $goldtransaction->user_id = $user_id;
//                                                   $goldtransaction->gold_coins = $goldcoins;
//                                                   $goldtransaction->transaction_id = $lastid;
//                                                   $goldtransaction->olduserbalance = $usercoinsbalance;
//                                                   $goldtransaction->newuserbalance = $usercoinsbalance - $goldcoins;
//                                                   $goldtransaction->reftype = 'In App Purchase';
//                                                   $goldtransaction->created_at = date('Y-m-d H:i:s');
//                                                   if ($goldtransaction->save(false)) {
//                                                       Users::updatecoinsbalance($usercoinsbalance - $goldcoins, $user_id);
//                                                   }
//                                               }
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->landlord_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->status = 'Paid';
                                                    $todomodel->save(false);
                                                    $todomodel->renovationquote->status = 'Work In Progress';
                                                    $todomodel->renovationquote->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');


                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                }
                break;
            case "Insurance";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status == 'Unpaid') {
                        if ($status == 'Accepted') {
                            $totalpayableamount = $todomodel->total;
                            $stamp_duty = $todomodel->stamp_duty;
                            $totalamount = $amount;
                            $amountwithoutsst = $todomodel->subtotal;
                            $totaldiscount = $discount+$coins_savings;
                            $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst  - $discount - $coins_savings;
                            $sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                            $totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount+$stamp_duty;
                            $senderbalance = Users::getbalance($todomodel->landlord_id);
//                            if ($totalpayableamount > $senderbalance) {
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please recharge your wallet.');
//
//                            }
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                $sst = $todomodel->sst;

                                if (!empty($todoitems)) {

                                    $transactionmodel = new Transactions();
                                    $transactionmodel->landlord_id = $todomodel->landlord_id;
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = ($totaldiscount>0)?$totalamount:$amountwithoutsst;;
                                    $transactionmodel->sst = $sstafterdiscount;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'Insurance';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            foreach ($todoitems as $todoitem) {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $totalamount;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }

                                            }
                                            if ($flag) {

                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->landlord_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->status = 'Paid';
                                                    $todomodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        $transaction->rollBack();

                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }

                break;
            case "General";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status == 'Unpaid') {
                        if ($status == 'Accepted') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                $totalpayableamount = $todomodel->total;
                                $totalamount = $amount;
                                $amountwithoutsst = $todomodel->subtotal;
                                $totaldiscount = $discount+$coins_savings;
                                $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $discount - $coins_savings;
                                if($todomodel->is_sst==1){
                                    $sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                                    $totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount;

                                }else{
                                    $sstafterdiscount = $todomodel->sst;
                                    $totalamountafterdiscount = $totalamountafterdiscount;
                                }
                                if ($todomodel->pay_from == 'Tenant') {
                                    $senderbalance = Users::getbalance($todomodel->user_id);

                                } else {
                                    $senderbalance = Users::getbalance($todomodel->landlord_id);

                                }

//                                if ($totalpayableamount > $senderbalance) {
//                                    $transaction->rollBack();
//                                    return array('status' => 0, 'message' => 'You don`t have enough balance.Please recharge your wallet.');
//
//                                }
                                if (!empty($todoitems)) {



                                    $transactionmodel = new Transactions();
                                    if ($todomodel->pay_from == 'Tenant') {
                                        $transactionmodel->user_id = $todomodel->user_id;

                                    } else {
                                        $transactionmodel->landlord_id = $todomodel->landlord_id;

                                    }
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = ($totaldiscount>0)?$totalamount:$amountwithoutsst;;
                                    $transactionmodel->sst = $sstafterdiscount;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'General';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            foreach ($todoitems as $todoitem) {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                if ($todomodel->pay_from == 'Tenant') {
                                                    $transactionitemmodel->sender_id = $todomodel->user_id;

                                                } else {
                                                    $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                }

                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }

                                            }
                                            if ($flag) {
                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
//                                                $gold_coins = $totalamountafterdiscount*1.5;
//                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, ($todomodel->pay_from == 'Tenant') ? $todomodel->user_id : $todomodel->landlord_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->status = 'Paid';
                                                    $todomodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }

                break;
            case "Defect Report";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status=='Unpaid') {
                        if ($status == 'Accepted') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                $totalpayableamount = $todomodel->total;
                                $senderbalance = Users::getbalance($todomodel->user_id);

                                if ($totalpayableamount > $senderbalance) {
                                    $transaction->rollBack();
                                    return array('status' => 0, 'message' => 'You don`t have enough balance.Please recharge your wallet.');

                                }
                                if (!empty($todoitems)) {
                                    $totalamount = $amount;
                                    $totalamountafterdiscount = $totalamount - $discount - $coins_savings;


                                    $transactionmodel = new Transactions();
                                    if ($todomodel->pay_from == 'Tenant') {
                                        $transactionmodel->user_id = $todomodel->user_id;

                                    } else {
                                        $transactionmodel->landlord_id = $todomodel->user_id;

                                    }
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = $totalamount;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'Defect Report';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            foreach ($todoitems as $todoitem) {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                if ($todomodel->pay_from == 'Tenant') {
                                                    $transactionitemmodel->sender_id = $todomodel->user_id;

                                                } else {
                                                    $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                }

                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }

                                            }
                                            if ($flag) {
                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscount*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, ($todomodel->pay_from == 'Tenant') ? $todomodel->user_id : $todomodel->user_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->updated_by = $todomodel->user_id;
                                                    $todomodel->status = 'In Progress';
                                                    $todomodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        $transaction->rollBack();

                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Closed';
                            $todomodel->updated_by = $todomodel->user_id;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }elseif($todomodel->status=='Pending'){
                        if ($status == 'Accepted') {
                            $todomodel->status = 'In Progress';
                            $todomodel->updated_at = date('Y-m-d H:i:s');
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have accepted request successfully.');



                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Closed';
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected defect report successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }

                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }

                break;
            case "Appointment";
                if ($status == 'Completed') {
                    $todomodel->status = 'Completed';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        return array('status' => 1, 'message' => 'You have completed appointment successfully.');

                    }
                } else if ($status == 'Cancelled') {
                    $todomodel->status = 'Cancelled';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        return array('status' => 1, 'message' => 'You have cancelled appointment successfully.');

                    }
                }
                break;
            case "Renovation Quote";
                if ($status == 'Accepted') {
                    $todomodel->status = 'Approved';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        $todomodel->renovationquote->status = 'Approved';
                        $todomodel->renovationquote->save(false);
                        return array('status' => 1, 'message' => 'You have accepted renovation quote successfully.');

                    }
                } else if ($status == 'Rejected') {
                    $todomodel->status = 'Rejected';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        $todomodel->renovationquote->status = 'Rejected';
                        $todomodel->renovationquote->save(false);
                        return array('status' => 1, 'message' => 'You have Rejected renovation quote successfully.');

                    }
                }
                break;
            case "Service";
                if (($todomodel->service_type == 'Handyman' || $todomodel->service_type == 'Mover') && $todomodel->status == 'Pending') {

                    if ($status == 'Accepted') {
                        $todomodel->status = 'Accepted';
                        $todomodel->updated_at = date("Y-m-d H:i:s");
                        if ($todomodel->save(false)) {
                            $todomodel->servicerequest->status = 'Accepted';
                            $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->servicerequest->save(false)) {
                                return array('status' => 1, 'message' => 'You have accepted request successfully.');

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else {
                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        }

                    } else if ($status == 'Rejected') {
                        $todomodel->status = 'Rejected';
                        $todomodel->updated_at = date("Y-m-d H:i:s");
                        if ($todomodel->save(false)) {
                            $todomodel->servicerequest->status = 'Rejected';
                            $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->servicerequest->save(false)) {
                                $vendor = Users::findOne($todomodel->vendor_id);
                                $vendor->current_status = 'Free';
                                $vendor->save(false);
                                return array('status' => 1, 'message' => 'You have rejected request successfully.');

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else {
                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        }
                    }
                } else if (($todomodel->service_type == 'Handyman' || $todomodel->service_type == 'Mover') && $todomodel->status == 'Unpaid') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);
                            $totalpayableamount = $todomodel->total;
                            $totalamount = $amount;
                            $sst = $todomodel->sst;
                            $totaldiscount = $discount+$coins_savings;
                            $amountwithoutsst = $todomodel->subtotal;
                            $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $discount - $coins_savings;
                            $sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                            $totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount;
                            $senderbalance = Users::getbalance($todomodel->user_id);
//                            if ($totalpayableamount > $senderbalance) {
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please topup your wallet.');
//
//                            }
                            if (!empty($todoitems)) {

                                $receiverbalance = Users::getbalance($systemaccount->id);
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $todomodel->user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->payment_id=$payment_id;
                                $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                $transactionmodel->amount = ($totaldiscount>0)?$totalamount:$amountwithoutsst;;
                                $transactionmodel->sst = $sstafterdiscount;
                                $transactionmodel->discount = $discount;
                                $transactionmodel->coins = $goldcoins;
                                $transactionmodel->coins_savings = $coins_savings;
                                $transactionmodel->total_amount = $totalamountafterdiscount;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->reftype = 'Service';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save()) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_deductible = 0;
                                        $totaldeductfromuser = 0;

                                        foreach ($todoitems as $todoitem) {
                                            $transactionitemmodel = new TransactionsItems();
                                            $transactionitemmodel->transaction_id = $lastid;
                                            $transactionitemmodel->sender_id = $todomodel->user_id;
                                            $transactionitemmodel->receiver_id = $systemaccount->id;
                                            $transactionitemmodel->amount = $todoitem->price;
                                            $transactionitemmodel->total_amount = $todoitem->price;
                                            $transactionitemmodel->oldsenderbalance = $senderbalance;
                                            $transactionitemmodel->newsenderbalance = $senderbalance;
                                            $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                            $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                            $transactionitemmodel->type = 'Payment';
                                            $transactionitemmodel->status = 'Completed';
                                            $transactionitemmodel->description = $todoitem->description;
                                            $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                            if (!($flag = $transactionitemmodel->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }

                                        }
                                        if ($flag) {
                                            if($goldcoins>0) {
                                                Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                            }
                                            $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                            Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                            //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->user_id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                            if ($updatereceiverbalance) {
                                                $todomodel->payment_date = date('Y-m-d H:i:s');
                                                $todomodel->status = 'Confirmed';
                                                if($todomodel->save(false)){
                                                    $servicerequestmodel->status = 'Confirmed';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    if($servicerequestmodel->save(false)){
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                    }else{
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');


                                                    }
                                                }else{
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            }



                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Rejected';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Rejected';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have rejected request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }


                } else if (($todomodel->service_type == 'Cleaner' ) && $todomodel->status == 'Unpaid') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $platformfees =  Yii::$app->common->getplatformfees('Cleaner');
                            $otherfees = 100;
                            $platformfeesapplied = 0;
                            if(!empty($platformfees)){
                                $platformfeesapplied = $platformfees['platform_fees'];
                                $otherfees = 100 - $platformfeesapplied;
                            }

                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);
                            $totalpayableamount = $todomodel->total;
                            $sst = $todomodel->sst;
                            $totalamount = $amount;
                            $amountwithoutsst = $todomodel->subtotal;
                            $totaldiscount = $discount+$coins_savings;
                            $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $discount - $coins_savings;
                            $sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                            $totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount;
                            $systemaccount = Yii::$app->common->getsystemaccount();
                            $systemaccountbalance = $systemaccount->wallet_balance;

                            $senderbalance = Users::getbalance($todomodel->user_id);
                            $receiverbalance = Users::getbalance($todomodel->vendor_id);

//                            if ($totalpayableamount > $senderbalance) {
//                                $transaction->rollBack();
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please topup your wallet.');
//
//                            }
                            if (!empty($todoitems)) {
                                $totalamount = $amount;
                                $totalamountafterdiscount = $totalamount - $discount - $coins_savings;
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $todomodel->user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->vendor_id = $todomodel->vendor_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->payment_id=$payment_id;
                                $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                $transactionmodel->amount = ($totaldiscount>0)?$totalamount:$amountwithoutsst;
                                $transactionmodel->sst = $sstafterdiscount;
                                $transactionmodel->discount = $discount;
                                $transactionmodel->coins = $goldcoins;
                                $transactionmodel->coins_savings = $coins_savings;
                                $transactionmodel->total_amount = $totalamountafterdiscount;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->reftype = 'Service';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save()) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_added = 0;
                                        $totaladdedtovendor = 0;

                                        foreach ($todoitems as $todoitem) {
                                            if($platformfeesapplied > 0){

                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;

                                                $transactionitemmodel1 = new TransactionsItems();
                                                $transactionitemmodel1->transaction_id = $lastid;
                                                $transactionitemmodel1->sender_id = $todomodel->user_id;
                                                $transactionitemmodel1->receiver_id = $systemaccount->id;
                                                $transactionitemmodel1->amount = $todoitem->price;
                                                $transactionitemmodel1->total_amount = ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel1->newsenderbalance = $senderbalance - ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->oldreceiverbalance = $systemaccountbalance;
                                                $transactionitemmodel1->newreceiverbalance = $systemaccountbalance + ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->type = 'Payment';
                                                $transactionitemmodel1->status = 'Completed';
                                                $transactionitemmodel1->description = $todoitem->description;
                                                $transactionitemmodel1->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel1->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totalplatform_added += $transactionitemmodel1->total_amount;

                                            }else {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;
                                            }

                                        }
                                        if ($flag) {
                                            if($goldcoins>0) {
                                                Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                            }
                                            $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                            Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                            //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->user_id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaladdedtovendor, $todomodel->vendor_id);
                                            $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$totalplatform_added+$sstafterdiscount,$systemaccount->id);

                                            if ($updatereceiverbalance  && $updatesystemaccountbalance) {
                                                $todomodel->payment_date = date('Y-m-d H:i:s');
                                                $todomodel->status = 'In Progress';
                                                if($todomodel->save(false)){
                                                    $servicerequestmodel->status = 'In Progress';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    if($servicerequestmodel->save(false)){
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                    }else{
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');


                                                    }
                                                }else{
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            }



                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Cancelled';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Cancelled';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have rejected request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }


                }else if (($todomodel->service_type == 'Laundry' ) && $todomodel->status == 'Unpaid') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $platformfees =  Yii::$app->common->getplatformfees('Laundry');
                            $otherfees = 100;
                            $platformfeesapplied = 0;
                            if(!empty($platformfees)){
                                $platformfeesapplied = $platformfees['platform_fees'];
                                $otherfees = 100 - $platformfeesapplied;
                            }

                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);
                            $totalpayableamount = $todomodel->total;
                            $sst = $todomodel->sst;
                            $totalamount = $amount;
                            $amountwithoutsst = $todomodel->subtotal;
                            $totaldiscount = $discount+$coins_savings;
                            $totalamountafterdiscountwithoutsst = $totalamountafterdiscount = $amountwithoutsst - $discount - $coins_savings;
                            $sstafterdiscount = Yii::$app->common->calculatesst($totalamountafterdiscount);
                            $totalamountafterdiscount = $totalamountafterdiscount+$sstafterdiscount;
                            $systemaccount = Yii::$app->common->getsystemaccount();
                            $systemaccountbalance = $systemaccount->wallet_balance;

                            $senderbalance = Users::getbalance($todomodel->user_id);
                            $receiverbalance = Users::getbalance($todomodel->vendor_id);

//                            if ($totalpayableamount > $senderbalance) {
//                                $transaction->rollBack();
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please topup your wallet.');
//
//                            }
                            if (!empty($todoitems)) {
//                                $totalamount = $amount;
//                                $totalamountafterdiscount = $totalamount - $discount - $coins_savings;
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $todomodel->user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->vendor_id = $todomodel->vendor_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->payment_id=$payment_id;
                                $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                $transactionmodel->amount = ($totaldiscount>0)?$totalamount:$amountwithoutsst;
                                $transactionmodel->sst = $sstafterdiscount;
                                $transactionmodel->discount = $discount;
                                $transactionmodel->coins = $goldcoins;
                                $transactionmodel->coins_savings = $coins_savings;
                                $transactionmodel->total_amount = $totalamountafterdiscount;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->reftype = 'Service';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save()) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_added = 0;
                                        $totaladdedtovendor = 0;

                                        foreach ($todoitems as $todoitem) {
                                            if($platformfeesapplied > 0){

                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;

                                                $transactionitemmodel1 = new TransactionsItems();
                                                $transactionitemmodel1->transaction_id = $lastid;
                                                $transactionitemmodel1->sender_id = $todomodel->user_id;
                                                $transactionitemmodel1->receiver_id = $systemaccount->id;
                                                $transactionitemmodel1->amount = $todoitem->price;
                                                $transactionitemmodel1->total_amount = ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel1->newsenderbalance = $senderbalance;
                                                $transactionitemmodel1->oldreceiverbalance = $systemaccountbalance;
                                                $transactionitemmodel1->newreceiverbalance = $systemaccountbalance + ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->type = 'Payment';
                                                $transactionitemmodel1->status = 'Completed';
                                                $transactionitemmodel1->description = $todoitem->description;
                                                $transactionitemmodel1->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel1->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totalplatform_added += $transactionitemmodel1->total_amount;

                                            }else {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;
                                            }

                                        }
                                        if ($flag) {
                                            if($goldcoins>0) {
                                                Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                            }
                                            $gold_coins = $totalamountafterdiscountwithoutsst*1.5;
                                            Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                            //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->user_id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaladdedtovendor, $todomodel->vendor_id);
                                            $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$totalplatform_added+$sstafterdiscount,$systemaccount->id);

                                            if ($updatereceiverbalance  && $updatesystemaccountbalance) {
                                                $todomodel->payment_date = date('Y-m-d H:i:s');
                                                $todomodel->status = 'In Progress';
                                                if($todomodel->save(false)){
                                                    $servicerequestmodel->status = 'In Progress';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    if($servicerequestmodel->save(false)){
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                    }else{
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');


                                                    }
                                                }else{
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            }



                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Cancelled';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Cancelled';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have Cancelled request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }


                }else if (($todomodel->service_type == 'Handyman' || $todomodel->service_type == 'Mover') && $todomodel->status == 'Refund Requested') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);

                            if (!empty($todoitems)) {

                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->amount = $todomodel->total;
                                $transactionmodel->sst = $todomodel->sst;
                                $transactionmodel->total_amount = $todomodel->total;
                                $transactionmodel->type = 'Refund';
                                $transactionmodel->reftype = 'Cancellation Refund';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save(false)) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_deductible = 0;
                                        $totaldeductfromuser = 0;
                                        $receiverbalance = Users::getbalance($user_id);
                                        $systemaccount = Yii::$app->common->getsystemaccount();
                                        $senderbalance = Users::getbalance($systemaccount->id);
                                        foreach ($todoitems as $todoitem) {

                                            $totaldeductfromuser += $todoitem->price;
                                            $transactionitemmodel = new TransactionsItems();
                                            $transactionitemmodel->transaction_id = $lastid;
                                            $transactionitemmodel->sender_id = $systemaccount->id;
                                            $transactionitemmodel->receiver_id = $user_id;
                                            $transactionitemmodel->amount = $todoitem->price;
                                            $transactionitemmodel->total_amount = $todoitem->price;

                                            $transactionitemmodel->oldsenderbalance = $senderbalance;
                                            $transactionitemmodel->newsenderbalance = $senderbalance - $todoitem->price;
                                            $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                            $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                            $transactionitemmodel->type = 'Refund';
                                            $transactionitemmodel->status = 'Completed';
                                            $transactionitemmodel->description = $todoitem->description;
                                            $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                            if (!($flag = $transactionitemmodel->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }




                                        }
                                        if ($flag) {
                                            $updatesenderbalance = Users::updatebalance($systemaccount->wallet_balance - $todomodel->sst - $totaldeductfromuser, $systemaccount->id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaldeductfromuser + $totalplatform_deductible + $todomodel->sst, $user_id);
                                            if ($updatereceiverbalance && $updatesenderbalance) {
                                                $todomodel->status = 'Refunded';
                                                if ($todomodel->save(false)) {
                                                    $servicerequestmodel->status = 'Refunded';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    $servicerequestmodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have accepted refund request successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    $transaction->rollBack();
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }


                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Refund Rejected';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Refund Rejected';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have Rejected Refund request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }

                }else{
                    return array('status' => 0, 'message' => 'Data not found.');

                }
                break;

        }
    }

    public function Sendpushnotification($user_id,$subject,$textmessage,$type,$sender_id='',$property_id='',$todo_id='',$nttype=''){

                        if($subject!='' && $textmessage!='' ){
                        $devices = Devices::find()->where(['user_id'=>$user_id])->all();
                        if(!empty($devices)) {
                            if($type=='User'){
                                $note = Yii::$app->fcm1->createNotification($subject, $textmessage);
                                $note->setIcon('fcm_push_icon')->setSound('default')->setClickAction('FCM_PLUGIN_ACTIVITY')
                                    ->setColor('#ffffff');

                                $message = Yii::$app->fcm1->createMessage();

                                foreach ($devices as $device) {
                                    $message->addRecipient(new Device($device->device_token));
                                }
                                if($nttype!=''){
                                    $message->setNotification($note)
                                        ->setData([
                                            'notification_type' => 'chat',
                                            'title' => $subject,
                                            'body' => $textmessage
                                        ]);
                                }else{
                                    $message->setNotification($note)
                                        ->setData([
                                            'title' => $subject,
                                            'body' => $textmessage
                                        ]);
                                }


                                $response = Yii::$app->fcm1->send($message);
                                }else if($type=='Partner'){
                                $note = Yii::$app->fcm2->createNotification($subject, $textmessage);
                                $note->setIcon('fcm_push_icon')->setSound('default')->setClickAction('FCM_PLUGIN_ACTIVITY')
                                    ->setColor('#ffffff');

                                $message = Yii::$app->fcm2->createMessage();

                                foreach ($devices as $device) {
                                    $message->addRecipient(new Device($device->device_token));
                                }

                                if($nttype!=''){
                                    $message->setNotification($note)
                                        ->setData([
                                            'notification_type' => 'chat',
                                            'title' => $subject,
                                            'body' => $textmessage
                                        ]);
                                }else{
                                    $message->setNotification($note)
                                        ->setData([
                                            'title' => $subject,
                                            'body' => $textmessage
                                        ]);
                                }

                                $response = Yii::$app->fcm2->send($message);
                            }

                        }
          }
    }

    public function Savenotification($receiver_id,$subject,$textmessage,$sender_id='',$property_id='',$todo_id=''){
        $notificationmodel = new Notifications();
        $notificationmodel->sender_id = ($sender_id!='')?$sender_id:NULL;
        $notificationmodel->receiver_id = $receiver_id;
        $notificationmodel->property_id = ($property_id!='')?$property_id:NULL;
        $notificationmodel->todo_id = ($todo_id!='')?$todo_id:NULL;
        $notificationmodel->subject = $subject;
        $notificationmodel->text = $textmessage;
        $notificationmodel->created_at = date('Y-m-d H:i:s');
        $notificationmodel->save(false);
    }

    public function paymentold($user_id,$todo_id,$status,$reftype,$post,$payment_id=''){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $systemaccount = $this->getsystemaccount();
        $todomodel = TodoList::findOne($todo_id);
        $promocode = (isset($post['promo_code']) && $post['promo_code'] != '') ? $post['promo_code'] : '';
        $amount = (isset($post['amount']) && $post['amount'] != '') ? $post['amount'] : '';
        $discount = (isset($post['discount']) && $post['discount'] != '') ? $post['discount'] : 0;
        $goldcoins = (isset($post['gold_coins']) && $post['gold_coins'] != '') ? $post['gold_coins'] : 0;
        $coins_savings = (isset($post['coins_savings']) && $post['coins_savings'] != '') ? $post['coins_savings'] : 0;
        $payment_id = ($payment_id != '') ? $payment_id : '';
        if ($promocode != '') {
            $promocodedetails = PromoCodes::find()->where(['promo_code' => $promocode])->one();
        }
        $todomodel = TodoList::findOne($todo_id);
        switch ($reftype) {
            case "Booking";
                $model = BookingRequests::findOne($todomodel->request_id);
                $sst = $model->sst;
                $totalamount = $amount;
                $totalamountafterdiscount = (int)$totalamount-(int)$discount-(int)$coins_savings;

                $receiverbalance = Users::getbalance($model->landlord_id);
                $senderbalance = Users::getbalance($model->user_id);
                $systemaccount = Yii::$app->common->getsystemaccount();
                $systemaccountbalance = $systemaccount->wallet_balance;

                $transaction1 = Yii::$app->db->beginTransaction();

                try {


                    $transaction = new Transactions();
                    $transaction->user_id = $this->user_id;
                    $transaction->request_id = $model->id;
                    $transaction->landlord_id = $model->landlord_id;
                    $transaction->promo_code = ($promocode!='')?$promocodedetails->id:NULL;
                    $transaction->payment_id=$payment_id;
                    $transaction->amount = $totalamount;
                    $transaction->sst = $sst;
                    $transaction->discount = $discount;
                    $transaction->coins = $goldcoins;
                    $transaction->coins_savings = $coins_savings;
                    $transaction->total_amount = $totalamountafterdiscount;
                    $transaction->reftype = 'Booking Payment';
                    $transaction->status = 'Completed';
                    $transaction->created_at = date('Y-m-d H:i:s');
                    if ($transaction->save(false)) {
                        $lastid = $transaction->id;
                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                        $transaction->reference_no = $reference_no;
                        if ($transaction->save(false)) {
//                                    if($model->booking_fees>0){
//                                        $transactionitems = new TransactionsItems();
//                                        $transactionitems->sender_id = $model->user_id;
//                                        $transactionitems->receiver_id = $model->landlord_id;
//                                        $transactionitems->amount = $model->booking_fees;
//                                        $transactionitems->total_amount = $model->booking_fees;
//                                        $transactionitems->oldsenderbalance = $senderbalance;
//                                        $transactionitems->newsenderbalance = $senderbalance-$model->booking_fees;
//                                        $transactionitems->oldreceiverbalance = $receiverbalance;
//                                        $transactionitems->newreceiverbalance = $receiverbalance+$model->booking_fees;
//                                        $transactionitems->description = 'Booking Fees';
//                                        $transactionitems->created_at = date('Y-m-d H:i:s');
//                                        $transactionitems->save(false);
//                                    }
                            if($model->security_deposit>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $model->landlord_id;
                                $transactionitems->amount = $model->security_deposit;
                                $transactionitems->total_amount = $model->security_deposit;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $receiverbalance;
                                $transactionitems->newreceiverbalance = $receiverbalance+$model->security_deposit;
                                $transactionitems->description = 'Deposit';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->keycard_deposit>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $model->landlord_id;
                                $transactionitems->amount = $model->keycard_deposit;
                                $transactionitems->total_amount = $model->keycard_deposit;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $receiverbalance;
                                $transactionitems->newreceiverbalance = $receiverbalance+$model->keycard_deposit;
                                $transactionitems->description = 'Keycard Deposit';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->utilities_deposit>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $model->landlord_id;
                                $transactionitems->amount = $model->utilities_deposit;
                                $transactionitems->total_amount = $model->utilities_deposit;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $receiverbalance;
                                $transactionitems->newreceiverbalance = $receiverbalance+$model->utilities_deposit;
                                $transactionitems->description = 'Utilities Deposit';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->stamp_duty>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $systemaccount->id;
                                $transactionitems->amount = $model->stamp_duty;
                                $transactionitems->total_amount = $model->stamp_duty;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $systemaccountbalance;
                                $transactionitems->newreceiverbalance = $systemaccountbalance+$model->stamp_duty;
                                $transactionitems->description = 'Stamp Duty';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            if($model->tenancy_fees>0){
                                $transactionitems = new TransactionsItems();
                                $transactionitems->sender_id = $model->user_id;
                                $transactionitems->receiver_id = $systemaccount->id;
                                $transactionitems->amount = $model->tenancy_fees;
                                $transactionitems->total_amount = $model->tenancy_fees;
                                $transactionitems->oldsenderbalance = $senderbalance;
                                $transactionitems->newsenderbalance = $senderbalance;
                                $transactionitems->oldreceiverbalance = $systemaccountbalance;
                                $transactionitems->newreceiverbalance = $systemaccountbalance+$model->tenancy_fees;
                                $transactionitems->description = 'Tenancy Fees';
                                $transactionitems->created_at = date('Y-m-d H:i:s');
                                $transactionitems->save(false);
                            }
                            $model->updated_by = $this->user_id;
                            $model->status = 'Rented';
                            $model->rented_at = date('Y-m-d H:i:s');
                            if ($model->save(false)) {
                                $todomodel->status = 'Paid';
                                $todomodel->save(false);
                                $months = $model->tenancy_period;
                                $effectiveDate = date('Y-m-d', strtotime("+".$months." months", strtotime($model->commencement_date)));
                                $model->property->availability = date('Y-m-d', strtotime("+".$months." months", strtotime($effectiveDate)));
                                $model->property->status = 'Rented';
                                $model->property->request_id = $model->id;
                                if($model->property->save(false)){
                                    if($model->property->agent_id!=''){
                                        $todorequest = TodoList::find()->where(['landlord_id'=>$model->landlord_id,'agent_id'=>$model->property->agent_id,'reftype'=>'Transfer Request','status'=>'Accepted','property_id'=>$model->property_id,'user_id'=>$model->user_id])->orderBy(['id'=>SORT_DESC])->one();
                                        if(!empty($todorequest)){
                                            $todorequest->status = 'Completed';
                                            $todorequest->save(false);
                                            if($todorequest->receive_via=='Rumah-i') {
                                                $commision = $todorequest->commission;
                                                $agentbalance = Users::getbalance($model->property->agent_id);
                                                $commisiontransaction = new Transactions();
                                                $commisiontransaction->reftype = 'Agent Commision';
                                                $commisiontransaction->user_id = $model->property->agent_id;
                                                $commisiontransaction->property_id = $model->property_id;
                                                $commisiontransaction->todo_id = $todorequest->id;
                                                $commisiontransaction->amount = $commision;
                                                $commisiontransaction->total_amount = $commision;
                                                $commisiontransaction->type = 'Payment';
                                                $commisiontransaction->status = 'Completed';
                                                $commisiontransaction->created_at = date('Y-m-d H:i:s');
                                                if($commisiontransaction->save(false)){
                                                    $lastid1 = $commisiontransaction->id;
                                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid1);
                                                    $commisiontransaction->reference_no = $reference_no;
                                                    $commisiontransaction->save(false);
                                                    Users::updatebalance($agentbalance+$commision,$model->property->agent_id);
                                                }else{
                                                    $transaction1->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            }
                                        }
                                    }

                                    if($goldcoins>0) {
                                        $this->deductgoldcoinspurchase($model->user_id, $goldcoins, $lastid);
                                    }
                                    $gold_coins = $totalamountafterdiscount*1.5;
                                    $this->addgoldcoinspurchase($model->user_id,$gold_coins,$lastid);

                                    //$updatesenderbalance = Users::updatebalance($senderbalance-$totalamountafterdiscount,$model->user_id);
                                    $updatereceiverbalance = Users::updatebalance($receiverbalance+$model->booking_fees+$model->rental_deposit+$model->utilities_deposit+$model->keycard_deposit,$model->landlord_id);
                                    $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$model->tenancy_fees+$model->stamp_duty+$sst,$systemaccount->id);

                                    $transaction1->commit();
                                    return array('status' => 1, 'message' => 'You have rented property successfully.');


                                }else{
                                    $transaction1->rollBack();
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }


                            }else{
                                $transaction1->rollBack();

                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }

                        }else{
                            $transaction1->rollBack();

                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        }


                    } else {
                        $transaction1->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                    }
                }catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction1->rollBack();
                }
                break;
            case "Moveout Refund";
                $todoitems = $todomodel->todoItems;


                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status=='Pending') {
                        if ($status == 'Accepted') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                if (!empty($todoitems)) {

                                    $transactionmodel = new Transactions();
                                    $transactionmodel->user_id = $user_id;
                                    $transactionmodel->landlord_id = $todomodel->request->landlord_id;
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->request_id = $todomodel->request_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->amount = $todomodel->total;
                                    $transactionmodel->sst = $todomodel->sst;
                                    $transactionmodel->total_amount = $todomodel->total;
                                    $transactionmodel->type = 'Refund';
                                    $transactionmodel->reftype = 'Moveout Refund';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save(false)) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($user_id);
                                            $senderbalance = Users::getbalance($todomodel->request->landlord_id);
                                            foreach ($todoitems as $todoitem) {

                                                if ($todoitem->platform_deductible > 0) {
                                                    $totalplatform_deductible += $todoitem->platform_deductible;
                                                    $transactionitemmodel = new TransactionsItems();
                                                    $transactionitemmodel->sender_id = $systemaccount->id;
                                                    $transactionitemmodel->transaction_id = $lastid;
                                                    $transactionitemmodel->receiver_id = $user_id;
                                                    $transactionitemmodel->amount = $todoitem->platform_deductible;
                                                    $transactionitemmodel->total_amount = $todoitem->platform_deductible;
                                                    $transactionitemmodel->oldsenderbalance = $systemaccount->wallet_balance;
                                                    $transactionitemmodel->newsenderbalance = $systemaccount->wallet_balance - $todoitem->platform_deductible;
                                                    $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                    $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->platform_deductible;
                                                    $transactionitemmodel->type = 'Refund';
                                                    $transactionitemmodel->status = 'Completed';
                                                    $transactionitemmodel->description = $todoitem->description;
                                                    $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                    if ($flag = $transactionitemmodel->save(false)) {
                                                        $totaldeductfromuser += $todoitem->price;
                                                        $transactionitemmodel1 = new TransactionsItems();
                                                        $transactionitemmodel1->transaction_id = $lastid;
                                                        $transactionitemmodel1->sender_id = $todomodel->request->landlord_id;
                                                        $transactionitemmodel1->receiver_id = $user_id;
                                                        $transactionitemmodel1->amount = $todoitem->price;
                                                        $transactionitemmodel1->total_amount = $todoitem->price;

                                                        $transactionitemmodel1->oldsenderbalance = $senderbalance;
                                                        $transactionitemmodel1->newsenderbalance = $senderbalance - $todoitem->price;
                                                        $transactionitemmodel1->oldreceiverbalance = $receiverbalance;
                                                        $transactionitemmodel1->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                        $transactionitemmodel1->type = 'Refund';
                                                        $transactionitemmodel1->status = 'Completed';

                                                        $transactionitemmodel1->description = $todoitem->description;
                                                        $transactionitemmodel1->created_at = date('Y-m-d H:i:s');
                                                        $transactionitemmodel1->save(false);
                                                        if (!($flag = $transactionitemmodel1->save(false))) {
                                                            $transaction->rollBack();
                                                            break;
                                                        }


                                                    } else {
                                                        $transaction->rollBack();
                                                        break;
                                                    }

                                                } else {
                                                    $totaldeductfromuser += $todoitem->price;
                                                    $transactionitemmodel = new TransactionsItems();
                                                    $transactionitemmodel->transaction_id = $lastid;
                                                    $transactionitemmodel->sender_id = $todomodel->request->landlord_id;
                                                    $transactionitemmodel->receiver_id = $user_id;
                                                    $transactionitemmodel->amount = $todoitem->price;
                                                    $transactionitemmodel->total_amount = $todoitem->price;

                                                    $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                    $transactionitemmodel->newsenderbalance = $senderbalance - $todoitem->price;
                                                    $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                    $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                    $transactionitemmodel->type = 'Refund';
                                                    $transactionitemmodel->status = 'Completed';
                                                    $transactionitemmodel->description = $todoitem->description;
                                                    $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                    if (!($flag = $transactionitemmodel->save(false))) {
                                                        $transaction->rollBack();
                                                        break;
                                                    }

                                                }


                                            }
                                            if ($flag) {
                                                $updatesenderbalance = Users::updatebalance($senderbalance - $totaldeductfromuser, $todomodel->request->landlord_id);
                                                $updatesystembalance = Users::updatebalance($systemaccount->wallet_balance - $totalplatform_deductible - $todomodel->sst, $systemaccount->id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaldeductfromuser + $totalplatform_deductible + $todomodel->sst, $user_id);
                                                if ($updatereceiverbalance && $updatesenderbalance && $updatesystembalance) {
                                                    $todomodel->status = 'Completed';
                                                    if ($todomodel->save(false)) {
                                                        $todomodel->property->status = 'Active';
                                                        $todomodel->property->save(false);
                                                        $todomodel->request->status = 'Moved Out';
                                                        $todomodel->request->updated_by = $user_id;
                                                        $todomodel->request->save(false);
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have accepted refund request successfully.');

                                                    } else {
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                    }

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        $transaction->rollBack();
                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }


                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = ($status == 'Rejected') ? 'Refund Rejected' : '';
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected refund request successfully.');

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }
                break;
            case "Renovation Milestone";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status=='Unpaid') {

                        if ($status == 'Accepted') {
                            $totalamount = $amount;
                            $totalamountafterdiscount = $totalamount - $discount - $coins_savings;
                            $senderbalance = Users::getbalance($todomodel->landlord_id);
//                            if ($senderbalance < $totalamountafterdiscount) {
//                                return array('status' => 0, 'message' => 'You don"t have enough wallet balance');
//
//                            }
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                if (!empty($todoitems)) {

                                    $transactionmodel = new Transactions();
                                    $transactionmodel->user_id = $user_id;
                                    $transactionmodel->landlord_id = $todomodel->landlord_id;
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->renovation_quote_id = $todomodel->renovation_quote_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = $totalamount;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->payment_id = ($payment_id!='')?$payment_id:NULL;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'Renovation Payment';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            $senderbalance = Users::getbalance($todomodel->landlord_id);
                                            foreach ($todoitems as $todoitem) {

                                                $totaldeductfromuser += $todoitem->price;
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $totalamount;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }


                                            }
                                            if ($flag) {
                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscount*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);

//                                               if ($goldcoins > 0) {
//                                                   $usercoinsbalance = Users::getcoinsbalance($user_id);
//                                                   $goldtransaction = new GoldTransactions();
//                                                   $goldtransaction->user_id = $user_id;
//                                                   $goldtransaction->gold_coins = $goldcoins;
//                                                   $goldtransaction->transaction_id = $lastid;
//                                                   $goldtransaction->olduserbalance = $usercoinsbalance;
//                                                   $goldtransaction->newuserbalance = $usercoinsbalance - $goldcoins;
//                                                   $goldtransaction->reftype = 'In App Purchase';
//                                                   $goldtransaction->created_at = date('Y-m-d H:i:s');
//                                                   if ($goldtransaction->save(false)) {
//                                                       Users::updatecoinsbalance($usercoinsbalance - $goldcoins, $user_id);
//                                                   }
//                                               }
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->landlord_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->status = 'Paid';
                                                    $todomodel->save(false);
                                                    $todomodel->renovationquote->status = 'Work In Progress';
                                                    $todomodel->renovationquote->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');


                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                }
                break;
            case "Insurance";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status == 'Unpaid') {
                        if ($status == 'Accepted') {
                            $totalpayableamount = $todomodel->total;
                            $senderbalance = Users::getbalance($todomodel->landlord_id);
//                            if ($totalpayableamount > $senderbalance) {
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please recharge your wallet.');
//
//                            }
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                $sst = $todomodel->sst;

                                if (!empty($todoitems)) {
                                    $totalamount = $amount;
                                    $totalamountafterdiscount = $totalamount - $discount - $coins_savings;

                                    $transactionmodel = new Transactions();
                                    $transactionmodel->landlord_id = $todomodel->landlord_id;
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = $totalamount;
                                    $transactionmodel->sst = $todomodel->sst;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'Insurance';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            foreach ($todoitems as $todoitem) {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $totalamount;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }

                                            }
                                            if ($flag) {

                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($todomodel->landlord_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscount*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($todomodel->landlord_id,$gold_coins,$lastid);
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->landlord_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->status = 'Paid';
                                                    $todomodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        $transaction->rollBack();

                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }

                break;
            case "General";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status == 'Unpaid') {
                        if ($status == 'Accepted') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                $totalpayableamount = $todomodel->total;
                                if ($todomodel->pay_from == 'Tenant') {
                                    $senderbalance = Users::getbalance($todomodel->user_id);

                                } else {
                                    $senderbalance = Users::getbalance($todomodel->landlord_id);

                                }

//                                if ($totalpayableamount > $senderbalance) {
//                                    $transaction->rollBack();
//                                    return array('status' => 0, 'message' => 'You don`t have enough balance.Please recharge your wallet.');
//
//                                }
                                if (!empty($todoitems)) {
                                    $totalamount = $amount;
                                    $totalamountafterdiscount = $totalamount - $discount - $coins_savings;


                                    $transactionmodel = new Transactions();
                                    if ($todomodel->pay_from == 'Tenant') {
                                        $transactionmodel->user_id = $todomodel->user_id;

                                    } else {
                                        $transactionmodel->landlord_id = $todomodel->landlord_id;

                                    }
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = $totalamount;
                                    $transactionmodel->sst = $todomodel->sst;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'General';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            foreach ($todoitems as $todoitem) {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                if ($todomodel->pay_from == 'Tenant') {
                                                    $transactionitemmodel->sender_id = $todomodel->user_id;

                                                } else {
                                                    $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                }

                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }

                                            }
                                            if ($flag) {
                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscount*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, ($todomodel->pay_from == 'Tenant') ? $todomodel->user_id : $todomodel->landlord_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->status = 'Paid';
                                                    $todomodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }

                break;
            case "Defect Report";
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if($todomodel->status=='Unpaid') {
                        if ($status == 'Accepted') {
                            $todomodel->status = $status;
                            if ($todomodel->save()) {
                                $todoitems = $todomodel->todoItems;
                                $totalpayableamount = $todomodel->total;
                                $senderbalance = Users::getbalance($todomodel->user_id);

                                if ($totalpayableamount > $senderbalance) {
                                    $transaction->rollBack();
                                    return array('status' => 0, 'message' => 'You don`t have enough balance.Please recharge your wallet.');

                                }
                                if (!empty($todoitems)) {
                                    $totalamount = $amount;
                                    $totalamountafterdiscount = $totalamount - $discount - $coins_savings;


                                    $transactionmodel = new Transactions();
                                    if ($todomodel->pay_from == 'Tenant') {
                                        $transactionmodel->user_id = $todomodel->user_id;

                                    } else {
                                        $transactionmodel->landlord_id = $todomodel->user_id;

                                    }
                                    $transactionmodel->property_id = $todomodel->property_id;
                                    $transactionmodel->todo_id = $todo_id;
                                    $transactionmodel->payment_id=$payment_id;
                                    $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                    $transactionmodel->amount = $totalamount;
                                    $transactionmodel->discount = $discount;
                                    $transactionmodel->coins = $goldcoins;
                                    $transactionmodel->coins_savings = $coins_savings;
                                    $transactionmodel->total_amount = $totalamountafterdiscount;
                                    $transactionmodel->type = 'Payment';
                                    $transactionmodel->reftype = 'Defect Report';
                                    $transactionmodel->status = 'Completed';
                                    $transactionmodel->created_at = date('Y-m-d H:i:s');
                                    if ($transactionmodel->save()) {
                                        $flag = false;
                                        $lastid = $transactionmodel->id;
                                        $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                        $transactionmodel->reference_no = $reference_no;
                                        $transactionmodel->save(false);
                                        if (!empty($todoitems)) {
                                            $totalplatform_deductible = 0;
                                            $totaldeductfromuser = 0;
                                            $receiverbalance = Users::getbalance($systemaccount->id);
                                            foreach ($todoitems as $todoitem) {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                if ($todomodel->pay_from == 'Tenant') {
                                                    $transactionitemmodel->sender_id = $todomodel->user_id;

                                                } else {
                                                    $transactionitemmodel->sender_id = $todomodel->landlord_id;
                                                }

                                                $transactionitemmodel->receiver_id = $systemaccount->id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }

                                            }
                                            if ($flag) {
                                                if($goldcoins>0) {
                                                    Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                                }
                                                $gold_coins = $totalamountafterdiscount*1.5;
                                                Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                                //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, ($todomodel->pay_from == 'Tenant') ? $todomodel->user_id : $todomodel->user_id);
                                                $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                                if ($updatereceiverbalance) {
                                                    $todomodel->updated_by = $todomodel->user_id;
                                                    $todomodel->status = 'In Progress';
                                                    $todomodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }
                                            } else {
                                                $transaction->rollBack();

                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        }

                                    } else {
                                        $transaction->rollBack();

                                        return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                    }

                                }


                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Closed';
                            $todomodel->updated_by = $todomodel->user_id;
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected payment successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    }elseif($todomodel->status=='Pending'){
                        if ($status == 'Accepted') {
                            $todomodel->status = 'In Progress';
                            $todomodel->updated_at = date('Y-m-d H:i:s');
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have accepted request successfully.');



                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Closed';
                            if ($todomodel->save()) {
                                $transaction->commit();
                                return array('status' => 1, 'message' => 'You have rejected defect report successfully.');

                            } else {
                                $transaction->rollBack();
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }

                    }else{
                        return array('status' => 0, 'message' => 'Data not found.');

                    }
                } catch (Exception $e) {
                    // # if error occurs then rollback all transactions
                    $transaction->rollBack();
                }

                break;
            case "Appointment";
                if ($status == 'Completed') {
                    $todomodel->status = 'Completed';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        return array('status' => 1, 'message' => 'You have completed appointment successfully.');

                    }
                } else if ($status == 'Cancelled') {
                    $todomodel->status = 'Cancelled';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        return array('status' => 1, 'message' => 'You have cancelled appointment successfully.');

                    }
                }
                break;
            case "Renovation Quote";
                if ($status == 'Accepted') {
                    $todomodel->status = 'Approved';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        $todomodel->renovationquote->status = 'Approved';
                        $todomodel->renovationquote->save(false);
                        return array('status' => 1, 'message' => 'You have accepted renovation quote successfully.');

                    }
                } else if ($status == 'Rejected') {
                    $todomodel->status = 'Rejected';
                    $todomodel->updated_at = date("Y-m-d H:i:s");
                    if ($todomodel->save(false)) {
                        $todomodel->renovationquote->status = 'Rejected';
                        $todomodel->renovationquote->save(false);
                        return array('status' => 1, 'message' => 'You have Rejected renovation quote successfully.');

                    }
                }
                break;
            case "Service";
                if (($todomodel->service_type == 'Handyman' || $todomodel->service_type == 'Mover') && $todomodel->status == 'Pending') {

                    if ($status == 'Accepted') {
                        $todomodel->status = 'Accepted';
                        $todomodel->updated_at = date("Y-m-d H:i:s");
                        if ($todomodel->save(false)) {
                            $todomodel->servicerequest->status = 'Accepted';
                            $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->servicerequest->save(false)) {
                                return array('status' => 1, 'message' => 'You have accepted request successfully.');

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else {
                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        }

                    } else if ($status == 'Rejected') {
                        $todomodel->status = 'Rejected';
                        $todomodel->updated_at = date("Y-m-d H:i:s");
                        if ($todomodel->save(false)) {
                            $todomodel->servicerequest->status = 'Rejected';
                            $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->servicerequest->save(false)) {
                                $vendor = Users::findOne($todomodel->vendor_id);
                                $vendor->current_status = 'Free';
                                $vendor->save(false);
                                return array('status' => 1, 'message' => 'You have rejected request successfully.');

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        } else {
                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        }
                    }
                } else if (($todomodel->service_type == 'Handyman' || $todomodel->service_type == 'Mover') && $todomodel->status == 'Unpaid') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);
                            $totalpayableamount = $todomodel->total;
                            $sst = $todomodel->sst;

                            $senderbalance = Users::getbalance($todomodel->user_id);
//                            if ($totalpayableamount > $senderbalance) {
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please topup your wallet.');
//
//                            }
                            if (!empty($todoitems)) {
                                $totalamount = $amount;
                                $totalamountafterdiscount = $totalamount - $discount - $coins_savings;
                                $receiverbalance = Users::getbalance($systemaccount->id);
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $todomodel->user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->payment_id=$payment_id;
                                $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                $transactionmodel->amount = $totalamount;
                                $transactionmodel->sst = $sst;
                                $transactionmodel->discount = $discount;
                                $transactionmodel->coins = $goldcoins;
                                $transactionmodel->coins_savings = $coins_savings;
                                $transactionmodel->total_amount = $totalamountafterdiscount;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->reftype = 'Service';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save()) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_deductible = 0;
                                        $totaldeductfromuser = 0;

                                        foreach ($todoitems as $todoitem) {
                                            $transactionitemmodel = new TransactionsItems();
                                            $transactionitemmodel->transaction_id = $lastid;
                                            $transactionitemmodel->sender_id = $todomodel->user_id;
                                            $transactionitemmodel->receiver_id = $systemaccount->id;
                                            $transactionitemmodel->amount = $todoitem->price;
                                            $transactionitemmodel->total_amount = $todoitem->price;
                                            $transactionitemmodel->oldsenderbalance = $senderbalance;
                                            $transactionitemmodel->newsenderbalance = $senderbalance;
                                            $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                            $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                            $transactionitemmodel->type = 'Payment';
                                            $transactionitemmodel->status = 'Completed';
                                            $transactionitemmodel->description = $todoitem->description;
                                            $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                            if (!($flag = $transactionitemmodel->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }

                                        }
                                        if ($flag) {
                                            if($goldcoins>0) {
                                                Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                            }
                                            $gold_coins = $totalamountafterdiscount*1.5;
                                            Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                            //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->user_id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totalamount, $systemaccount->id);
                                            if ($updatereceiverbalance) {
                                                $todomodel->payment_date = date('Y-m-d H:i:s');
                                                $todomodel->status = 'Confirmed';
                                                if($todomodel->save(false)){
                                                    $servicerequestmodel->status = 'Confirmed';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    if($servicerequestmodel->save(false)){
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                    }else{
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');


                                                    }
                                                }else{
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            }



                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Rejected';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Rejected';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have rejected request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }


                } else if (($todomodel->service_type == 'Cleaner' ) && $todomodel->status == 'Unpaid') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $platformfees =  Yii::$app->common->getplatformfees('Cleaner');
                            $otherfees = 100;
                            $platformfeesapplied = 0;
                            if(!empty($platformfees)){
                                $platformfeesapplied = $platformfees['platform_fees'];
                                $otherfees = 100 - $platformfeesapplied;
                            }

                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);
                            $totalpayableamount = $todomodel->total;
                            $sst = $todomodel->sst;
                            $systemaccount = Yii::$app->common->getsystemaccount();
                            $systemaccountbalance = $systemaccount->wallet_balance;

                            $senderbalance = Users::getbalance($todomodel->user_id);
                            $receiverbalance = Users::getbalance($todomodel->vendor_id);

//                            if ($totalpayableamount > $senderbalance) {
//                                $transaction->rollBack();
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please topup your wallet.');
//
//                            }
                            if (!empty($todoitems)) {
                                $totalamount = $amount;
                                $totalamountafterdiscount = $totalamount - $discount - $coins_savings;
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $todomodel->user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->vendor_id = $todomodel->vendor_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->payment_id=$payment_id;
                                $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                $transactionmodel->amount = $totalamount;
                                $transactionmodel->sst = $sst;
                                $transactionmodel->discount = $discount;
                                $transactionmodel->coins = $goldcoins;
                                $transactionmodel->coins_savings = $coins_savings;
                                $transactionmodel->total_amount = $totalamountafterdiscount;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->reftype = 'Service';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save()) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_added = 0;
                                        $totaladdedtovendor = 0;

                                        foreach ($todoitems as $todoitem) {
                                            if($platformfeesapplied > 0){

                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;

                                                $transactionitemmodel1 = new TransactionsItems();
                                                $transactionitemmodel1->transaction_id = $lastid;
                                                $transactionitemmodel1->sender_id = $todomodel->user_id;
                                                $transactionitemmodel1->receiver_id = $systemaccount->id;
                                                $transactionitemmodel1->amount = $todoitem->price;
                                                $transactionitemmodel1->total_amount = ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel1->newsenderbalance = $senderbalance - ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->oldreceiverbalance = $systemaccountbalance;
                                                $transactionitemmodel1->newreceiverbalance = $systemaccountbalance + ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->type = 'Payment';
                                                $transactionitemmodel1->status = 'Completed';
                                                $transactionitemmodel1->description = $todoitem->description;
                                                $transactionitemmodel1->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel1->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totalplatform_added += $transactionitemmodel1->total_amount;

                                            }else {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;
                                            }

                                        }
                                        if ($flag) {
                                            if($goldcoins>0) {
                                                Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                            }
                                            $gold_coins = $totalamountafterdiscount*1.5;
                                            Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                            //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->user_id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaladdedtovendor, $todomodel->vendor_id);
                                            $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$totalplatform_added+$sst,$systemaccount->id);

                                            if ($updatereceiverbalance  && $updatesystemaccountbalance) {
                                                $todomodel->payment_date = date('Y-m-d H:i:s');
                                                $todomodel->status = 'In Progress';
                                                if($todomodel->save(false)){
                                                    $servicerequestmodel->status = 'In Progress';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    if($servicerequestmodel->save(false)){
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                    }else{
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');


                                                    }
                                                }else{
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            }



                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Cancelled';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Cancelled';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have rejected request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }


                }else if (($todomodel->service_type == 'Laundry' ) && $todomodel->status == 'Unpaid') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $platformfees =  Yii::$app->common->getplatformfees('Laundry');
                            $otherfees = 100;
                            $platformfeesapplied = 0;
                            if(!empty($platformfees)){
                                $platformfeesapplied = $platformfees['platform_fees'];
                                $otherfees = 100 - $platformfeesapplied;
                            }

                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);
                            $totalpayableamount = $todomodel->total;
                            $sst = $todomodel->sst;
                            $systemaccount = Yii::$app->common->getsystemaccount();
                            $systemaccountbalance = $systemaccount->wallet_balance;

                            $senderbalance = Users::getbalance($todomodel->user_id);
                            $receiverbalance = Users::getbalance($todomodel->vendor_id);

//                            if ($totalpayableamount > $senderbalance) {
//                                $transaction->rollBack();
//                                return array('status' => 0, 'message' => 'You don`t have enough balance.Please topup your wallet.');
//
//                            }
                            if (!empty($todoitems)) {
                                $totalamount = $amount;
                                $totalamountafterdiscount = $totalamount - $discount - $coins_savings;
                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $todomodel->user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->vendor_id = $todomodel->vendor_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->payment_id=$payment_id;
                                $transactionmodel->promo_code = ($promocode != '') ? $promocodedetails->id : NULL;
                                $transactionmodel->amount = $totalamount;
                                $transactionmodel->sst = $sst;
                                $transactionmodel->discount = $discount;
                                $transactionmodel->coins = $goldcoins;
                                $transactionmodel->coins_savings = $coins_savings;
                                $transactionmodel->total_amount = $totalamountafterdiscount;
                                $transactionmodel->type = 'Payment';
                                $transactionmodel->reftype = 'Service';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save()) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_added = 0;
                                        $totaladdedtovendor = 0;

                                        foreach ($todoitems as $todoitem) {
                                            if($platformfeesapplied > 0){

                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + ($todoitem->price*$otherfees/100);
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;

                                                $transactionitemmodel1 = new TransactionsItems();
                                                $transactionitemmodel1->transaction_id = $lastid;
                                                $transactionitemmodel1->sender_id = $todomodel->user_id;
                                                $transactionitemmodel1->receiver_id = $systemaccount->id;
                                                $transactionitemmodel1->amount = $todoitem->price;
                                                $transactionitemmodel1->total_amount = ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel1->newsenderbalance = $senderbalance;
                                                $transactionitemmodel1->oldreceiverbalance = $systemaccountbalance;
                                                $transactionitemmodel1->newreceiverbalance = $systemaccountbalance + ($todoitem->price*$platformfeesapplied/100);
                                                $transactionitemmodel1->type = 'Payment';
                                                $transactionitemmodel1->status = 'Completed';
                                                $transactionitemmodel1->description = $todoitem->description;
                                                $transactionitemmodel1->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel1->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totalplatform_added += $transactionitemmodel1->total_amount;

                                            }else {
                                                $transactionitemmodel = new TransactionsItems();
                                                $transactionitemmodel->transaction_id = $lastid;
                                                $transactionitemmodel->sender_id = $todomodel->user_id;
                                                $transactionitemmodel->receiver_id = $todomodel->vendor_id;
                                                $transactionitemmodel->amount = $todoitem->price;
                                                $transactionitemmodel->total_amount = $todoitem->price;
                                                $transactionitemmodel->oldsenderbalance = $senderbalance;
                                                $transactionitemmodel->newsenderbalance = $senderbalance;
                                                $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                                $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                                $transactionitemmodel->type = 'Payment';
                                                $transactionitemmodel->status = 'Completed';
                                                $transactionitemmodel->description = $todoitem->description;
                                                $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                                if (!($flag = $transactionitemmodel->save(false))) {
                                                    $transaction->rollBack();
                                                    break;
                                                }
                                                $totaladdedtovendor +=   $transactionitemmodel->total_amount;
                                            }

                                        }
                                        if ($flag) {
                                            if($goldcoins>0) {
                                                Yii::$app->common->deductgoldcoinspurchase($user_id, $goldcoins, $lastid);
                                            }
                                            $gold_coins = $totalamountafterdiscount*1.5;
                                            Yii::$app->common->addgoldcoinspurchase($user_id,$gold_coins,$lastid);
                                            //$updatesenderbalance = Users::updatebalance($senderbalance - $totalamountafterdiscount, $todomodel->user_id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaladdedtovendor, $todomodel->vendor_id);
                                            $updatesystemaccountbalance = Users::updatebalance($systemaccountbalance+$totalplatform_added+$sst,$systemaccount->id);

                                            if ($updatereceiverbalance  && $updatesystemaccountbalance) {
                                                $todomodel->payment_date = date('Y-m-d H:i:s');
                                                $todomodel->status = 'In Progress';
                                                if($todomodel->save(false)){
                                                    $servicerequestmodel->status = 'In Progress';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    if($servicerequestmodel->save(false)){
                                                        $transaction->commit();
                                                        return array('status' => 1, 'message' => 'You have completed payment successfully.');

                                                    }else{
                                                        $transaction->rollBack();
                                                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');


                                                    }
                                                }else{
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            }



                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Cancelled';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Cancelled';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have Cancelled request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }


                }else if (($todomodel->service_type == 'Handyman' || $todomodel->service_type == 'Mover') && $todomodel->status == 'Refund Requested') {
                    $transaction = Yii::$app->db->beginTransaction();

                    try {

                        if ($status == 'Accepted') {
                            $todoitems = $todomodel->todoItems;
                            $servicerequestmodel = ServiceRequests::findOne($todomodel->service_request_id);

                            if (!empty($todoitems)) {

                                $transactionmodel = new Transactions();
                                $transactionmodel->user_id = $user_id;
                                $transactionmodel->property_id = $todomodel->property_id;
                                $transactionmodel->todo_id = $todo_id;
                                $transactionmodel->amount = $todomodel->total;
                                $transactionmodel->sst = $todomodel->sst;
                                $transactionmodel->total_amount = $todomodel->total;
                                $transactionmodel->type = 'Refund';
                                $transactionmodel->reftype = 'Cancellation Refund';
                                $transactionmodel->status = 'Completed';
                                $transactionmodel->created_at = date('Y-m-d H:i:s');
                                if ($transactionmodel->save(false)) {
                                    $flag = false;
                                    $lastid = $transactionmodel->id;
                                    $reference_no = "TR" . Yii::$app->common->generatereferencenumber($lastid);
                                    $transactionmodel->reference_no = $reference_no;
                                    $transactionmodel->save(false);
                                    if (!empty($todoitems)) {
                                        $totalplatform_deductible = 0;
                                        $totaldeductfromuser = 0;
                                        $receiverbalance = Users::getbalance($user_id);
                                        $systemaccount = Yii::$app->common->getsystemaccount();
                                        $senderbalance = Users::getbalance($systemaccount->id);
                                        foreach ($todoitems as $todoitem) {

                                            $totaldeductfromuser += $todoitem->price;
                                            $transactionitemmodel = new TransactionsItems();
                                            $transactionitemmodel->transaction_id = $lastid;
                                            $transactionitemmodel->sender_id = $systemaccount->id;
                                            $transactionitemmodel->receiver_id = $user_id;
                                            $transactionitemmodel->amount = $todoitem->price;
                                            $transactionitemmodel->total_amount = $todoitem->price;

                                            $transactionitemmodel->oldsenderbalance = $senderbalance;
                                            $transactionitemmodel->newsenderbalance = $senderbalance - $todoitem->price;
                                            $transactionitemmodel->oldreceiverbalance = $receiverbalance;
                                            $transactionitemmodel->newreceiverbalance = $receiverbalance + $todoitem->price;
                                            $transactionitemmodel->type = 'Refund';
                                            $transactionitemmodel->status = 'Completed';
                                            $transactionitemmodel->description = $todoitem->description;
                                            $transactionitemmodel->created_at = date('Y-m-d H:i:s');
                                            if (!($flag = $transactionitemmodel->save(false))) {
                                                $transaction->rollBack();
                                                break;
                                            }




                                        }
                                        if ($flag) {
                                            $updatesenderbalance = Users::updatebalance($systemaccount->wallet_balance - $todomodel->sst - $totaldeductfromuser, $systemaccount->id);
                                            $updatereceiverbalance = Users::updatebalance($receiverbalance + $totaldeductfromuser + $totalplatform_deductible + $todomodel->sst, $user_id);
                                            if ($updatereceiverbalance && $updatesenderbalance) {
                                                $todomodel->status = 'Refunded';
                                                if ($todomodel->save(false)) {
                                                    $servicerequestmodel->status = 'Refunded';
                                                    $servicerequestmodel->updated_at = date('Y-m-d H:i:s');
                                                    $servicerequestmodel->save(false);
                                                    $transaction->commit();
                                                    return array('status' => 1, 'message' => 'You have accepted refund request successfully.');

                                                } else {
                                                    $transaction->rollBack();
                                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                                }

                                            } else {
                                                $transaction->rollBack();
                                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                            }
                                        } else {
                                            $transaction->rollBack();

                                            return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                        }
                                    }

                                } else {
                                    $transaction->rollBack();
                                    return array('status' => 0, 'message' => $transactionmodel->getErrors());

                                }

                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }


                        } else if ($status == 'Rejected') {
                            $todomodel->status = 'Refund Rejected';
                            $todomodel->updated_at = date("Y-m-d H:i:s");
                            if ($todomodel->save(false)) {
                                $todomodel->servicerequest->status = 'Refund Rejected';
                                $todomodel->servicerequest->updated_at = date("Y-m-d H:i:s");
                                if ($todomodel->servicerequest->save(false)) {
                                    $vendor = Users::findOne($todomodel->vendor_id);
                                    $vendor->current_status = 'Free';
                                    $vendor->save(false);
                                    $transaction->commit();
                                    return array('status' => 1, 'message' => 'You have Rejected Refund request successfully.');

                                } else {
                                    return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                                }
                            } else {
                                return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();

                        return array('status' => 0, 'message' => 'Something went wrong.Please try after sometimes.');

                        // # if error occurs then rollback all transactions
                    }

                }else{
                    return array('status' => 0, 'message' => 'Data not found.');

                }
                break;

        }
    }

}