<?php
namespace app\components;


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
        $content = str_replace("#landlordname#",$model->landlord->full_name,$content);
        $content = str_replace("@property@",$model->property->title,$content);
        $content = str_replace("#landlordid#",$model->landlord->document_no,$content);
        $content = str_replace("#tenantid#",$model->user->document_no,$content);


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
                return "<span class='btn bg-orange btn-xs'>Picked Up</span>";
                break;
            case "In Progress";
                return "<span class='btn bg-orange btn-xs'>In Progress</span>";
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
            case "Payment Requested";
                return "<span class='btn bg-blue btn-xs'>Payment Requested</span>";
                break;
            case "Out For Delivey";
                return "<span class='btn bg-blue btn-xs'>Out For Delivey</span>";
                break;
            case "Rented";
                return "<span class='btn bg-green btn-xs'>Rented</span>";
                break;
            case "Terminated";
                return "<span class='btn btn-danger btn-xs'>Suspended</span>";
                break;
            case "Incompleted";
                return "<span class='btn btn-danger btn-xs'>Incompleted</span>";
                break;
        }
    }
}