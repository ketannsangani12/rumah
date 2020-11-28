<?php

namespace app\commands;

use app\models\Announcements;
use app\models\AwardsSettings;
use app\models\Cashbacks;
use app\models\Devices;
use app\models\EmailTemplates;
use app\models\Merchants;
use app\models\PackageVouchers;
use app\models\Payments;
use app\models\Topups;
use app\models\Transactions;
use app\models\Transfers;
use app\models\UserPackages;
use app\models\Users;
use app\models\VouchercreditTransactions;
use app\models\VouchersTransactions;
use paragraph1\phpFCM\Recipient\Device;
use yii\console\Controller;
use app\models\Cronjobs;
use app\models\Packages;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Exception;
use yii\web\Response;

/**
 * BranchesController implements the CRUD actions for Branches model.
 */
class CronjobsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

}
