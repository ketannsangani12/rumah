<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\BookingRequests;
use app\models\Cronjobs;
use app\models\Properties;
use app\models\TodoList;
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

        $properties = Properties::find()->where(['digital_tenancy'=>0,'status'=>'Active'])->andWhere(['>=','DATE(created_at)', $days_ago])->andWhere(['<=','DATE(created_at)', $todaydate])->all();
        if(!empty($properties)){
            foreach ($properties as $property){
                $property->status = 'Inactive';
                $property->updated_at = date('Y-m-d H:i:s');
                $property->save(false);
            }

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

}
