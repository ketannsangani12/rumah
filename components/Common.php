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

        return $content;
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
            case "Completed";
                return "<span class='btn bg-green btn-xs'>Completed</span>";
                break;
            case "Agreement Processed";
                return "<span class='btn bg-orange btn-xs'>Agreement Processed</span>";
                break;
            case "Work In Progress";
                return "<span class='btn bg-orange btn-xs'>Work In Progress</span>";
                break;
            case "Declined";
                return "<span class='btn btn-danger btn-xs'>Declined</span>";
                break;
            case "Rejected";
                return "<span class='btn btn-danger btn-xs'>Rejected</span>";
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