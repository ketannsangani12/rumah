<?php
namespace app\components;


use app\models\GoldTransactions;
use app\models\PlatformFees;
use app\models\Users;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
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
            case "Refund Requested";
                return "<span class='btn bg-orange btn-xs'>Refund Requested</span>";
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
        $total_fees = number_format($amount * 0 / 100, 2, '.', '');
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
        if($type=='Onboarding'){
            $usercoinsbalance = Users::getcoinsbalance($user_id);
            if($reffer_id!='') {
                $usercoinsbalance1 = Users::getcoinsbalance($reffer_id);
            }
            $goldtransaction = new GoldTransactions();
            $goldtransaction->user_id = $user_id;
            if($reffer_id!='') {
                $goldtransaction->refferer_id = $reffer_id;
            }
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
                if($reffer_id!='') {
                    $update = Users::updatecoinsbalance($usercoinsbalance1 + $goldcoins, $reffer_id);
                }

                if ($update) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }elseif($type=='1st Property Listed'){
            $usercoinsbalance1 = Users::getcoinsbalance($reffer_id);
            $goldtransaction = new GoldTransactions();
            $goldtransaction->user_id = $user_id;
            $goldtransaction->refferer_id = $reffer_id;
            $goldtransaction->gold_coins = $goldcoins;
            $goldtransaction->olduserbalance = $usercoinsbalance1;
            $goldtransaction->newuserbalance = $usercoinsbalance1 + $goldcoins;
            $goldtransaction->incoming = 1;
            $goldtransaction->reftype = '1st Property Listed';
            $goldtransaction->status = 'Completed';
            $goldtransaction->created_at = date('Y-m-d H:i:s');
            if ($goldtransaction->save(false)){
                $update = Users::updatecoinsbalance($usercoinsbalance1 + $goldcoins, $reffer_id);

                if ($update) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }else {
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
        $usercoinsbalance = Users::getcoinsbalance($user_id);
        $goldtransaction = new GoldTransactions();
        $goldtransaction->user_id = $user_id;
        $goldtransaction->gold_coins = $goldcoins;
        $goldtransaction->transaction_id = $transaction_id;
        $goldtransaction->olduserbalance =$usercoinsbalance;
        $goldtransaction->newuserbalance = $usercoinsbalance-$goldcoins;
        $goldtransaction->incoming = 1;
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

}