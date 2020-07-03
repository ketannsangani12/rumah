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
}