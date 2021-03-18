<style>
    .info-box {
        display: block;
        min-height: 63px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
        border-radius: 2px;
        margin-bottom: 15px;
    }
    .info-box-icon {
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        display: block;
        float: left;
        height: 63px;
        width: 50px;
        text-align: center;
        font-size: 0px;
        line-height: 84px;
        background: rgba(0, 0, 0, 0.2);
    }
    .info-box-content {
        padding: 5px 10px;
        margin-left: 50px;
    }
    @media (min-width: 992px) {
    .test {
        width: 33% !important;
    }
    }
    .icon-font-size{
        font-size: 30px;
    }
    .result-font{
        font-size: 16px;
    }
    .info-box-text{
        font-size: 13px !important;
    }
</style>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use bburim\daterangepicker\DateRangePicker as DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PackagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 style="margin-top: -20px;">Dashboard</h1>
<div class="row upper-part" style="margin-top: 35px;">
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size  fa fa-user"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Active Users">Active Users</span>
                <span class="info-box-number result-font"><?php echo $active_user; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Active property agents">Active property agents</span>
                <span class="info-box-number result-font"><?php echo $active_property_agents; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->

    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size  fa fa-list-alt"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Active listing">Active listing</span>
                <span class="info-box-number result-font"><?php echo $active_listing; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-houzz"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Managed listing">Managed listing</span>
                <span class="info-box-number result-font"><?php echo $managed_listing; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa fa-eye"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Active listing viewed">Active listing viewed</span>
                <span class="info-box-number result-font"><?php echo $active_listing_viewed; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size fa fa-pencil"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Purchased listing package (active)">Purchased listing package (active)</span>
                <span class="info-box-number result-font"><?php echo $purchased_listing_package_active; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size fa fa-puzzle-piece"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Purchased tenancy service (active)">Purchased tenancy service (active)</span>
                <span class="info-box-number result-font"><?php echo $purchased_tenancy_service_active; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->

    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-circle-o-notch"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Subscribed auto rental collection service (active)">Subscribed auto rental collection service (active)</span>
                <span class="info-box-number result-font"><?php echo $subscribed_auto_rental_collection_service_active; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa fa-heartbeat"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Purchased insurance policy (active)">Purchased insurance policy (active)</span>
                <span class="info-box-number result-font"><?php echo $purchased_insurance_policy_active; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>



<div class="row bottom-part" style="margin-top: 30px;">
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size fa fa-home"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Purchased tenancy service">Purchased tenancy service</span>
                <span class="info-box-number result-font"><?php echo $purchased_tenancy_service; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size fa fa-tag  "></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="promo code used">promo code used</span>
                <span class="info-box-number result-font"><?php echo $promo_code_used; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->

    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-money"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Gross amount of collected payment (RM)">Gross amount of collected payment (RM)</span>
                <span class="info-box-number result-font" > <?php if(isset($gross_amount_of_collected_payment)) { echo $gross_amount_of_collected_payment; }else{ echo '0';} ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa fa-futbol-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Aggregate amount of purchased package (RM)">Aggregate amount of purchased package (RM)</span>
                <span class="info-box-number result-font"><?php if(isset($aggregate_amount_of_purchased_package)) { echo $aggregate_amount_of_purchased_package; }else{ echo '0';} ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size fa  fa-hand-rock-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Aggregate amount of collected rental (RM)">Aggregate amount of collected rental (RM)</span>
                <span class="info-box-number result-font"><?php if(isset($aggregate_amount_of_collected_rental)) { echo $aggregate_amount_of_collected_rental; }else{ echo '0';} ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size fa fa-adjust custom"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Aggregate amount of collected deposits (RM)">Aggregate amount of collected deposits (RM)</span>
                <span class="info-box-number result-font"><?php if(isset($aggregate_amount_of_collected_deposits)) { echo $aggregate_amount_of_collected_deposits; }else{ echo '0';} ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-square"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Aggregate amount of collected agent commission (RM)">Aggregate amount of collected agent commission (RM)</span>
                <span class="info-box-number result-font"><?php if(isset($aggregate_amount_of_collected_agent_commission)) { echo $aggregate_amount_of_collected_agent_commission; }else{ echo '0';} ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->

    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa fa fa-credit-card"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Aggregate amount of renovation payment (RM)">Aggregate amount of renovation payment (RM)</span>
                <span class="info-box-number result-font"><?php if(isset($aggregate_amount_of_renovation_payment)) { echo $aggregate_amount_of_renovation_payment; }else{ echo '0';} ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-sm-6 col-xs-12 test">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size fa fa fa-credit-card"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Aggregate amount of service payment (RM)">Aggregate amount of service payment (RM)</span>
                <span class="info-box-number result-font"><?php if(isset($aggregate_amount_of_service_payment)) { echo $aggregate_amount_of_service_payment; }else{ echo '0';} ?></span>
        </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<label><h4><b>Booking</b></h4></label>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="New booking request">New booking request</span>
                <span class="info-box-number result-font"><?php echo $new_booking_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-check"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Completed booking request">Completed booking request </span>
                <span class="info-box-number result-font"><?php echo $completed_booking_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa fa-circle"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Declared booking request">Declared booking request</span>
                <span class="info-box-number result-font"><?php echo $declared_booking_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size fa fa-times-circle-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Cancelled booking request">Cancelled booking request</span>
                <span class="info-box-number result-font"><?php echo $cancelled_booking_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>

<label><h4><b>Renovation</b></h4></label>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size fa fa-delicious"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Approved renovation quote">approved renovation quote</span>
                <span class="info-box-number result-font"><?php echo $approved_renovation_quote; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-check-square-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Completed renovation quote">Completed renovation quote </span>
                <span class="info-box-number result-font"><?php echo $completed_renovation_quote; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<label><h4><b>Insurance</b></h4></label>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa  fa-stethoscope"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Completed insurance">completed insurance</span>
                <span class="info-box-number result-font"><?php echo $completed_insurance; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<label><h4><b>Defect</b></h4></label>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size  fa fa-bug"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Completed defect case">completed defect case</span>
                <span class="info-box-number result-font"><?php echo $completed_defect_case; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<label><h4><b>Auto rental collection</b></h4></label>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="icon-font-size fa fa-ticket"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Completed rental collection">completed rental collection</span>
                <span class="info-box-number result-font"><?php echo $completed_rental_collection; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<label><h4><b>Service</b></h4></label>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="icon-font-size fa fa-paper-plane"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="New service request">new service request</span>
                <span class="info-box-number result-font"><?php echo $new_service_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="icon-font-size fa fa-spinner"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Pending service request">Pending service request</span>
                <span class="info-box-number result-font"><?php echo $pending_service_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="icon-font-size fa fa-check-square"></i></span>

            <div class="info-box-content">
                <span class="info-box-text" title="Completed service request">Completed service request</span>
                <span class="info-box-number result-font"><?php echo $completed_service_request; ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>