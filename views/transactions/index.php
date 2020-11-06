<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'reference_no',
                [
                    // the attribute
                    'attribute' => 'created_at',
                    'vAlign'=>'middle',
                    //    'filterType'=> \kartik\grid\GridView::FILTER_DATE_RANGE,

                    //'width'=>'20%',
                    'noWrap'=>true,

                    // format the value
                    'value' => function ($model) {

                        return date('M d,Y h:i:s a', strtotime($model->created_at));

                    },
                    // some styling?


                    'filter' => false
                    // here we render the widget

                    //'format' => ['datetime', Yii::$app->formatter->datetimeFormat]
                ],
                [
                    'attribute' => 'user_id',

                    'value' => function($model){
                        return (isset($model->user->full_name))?$model->user->full_name:'';
                    },
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select User'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                        ],
                    ],

                    //'filter'=>false
                ],
                [
                    'attribute' => 'landlord_id',

                    'value' => function($model){
                        return (isset($model->landlord->full_name))?$model->landlord->full_name:'';
                    },
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select Landlord'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                            //'width'=>'resolve'
                        ],
                    ],
                    //'filter'=>false
                ],
                [
                    'attribute' => 'vendor_id',

                    'value' => function($model){
                        return (isset($model->vendor->full_name))?$model->vendor->full_name:'';
                    },
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['Cleaner','Mover','Laundry','Handyman']])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select Vendor'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                            //'width'=>'resolve'
                        ],
                    ],
                    //'filter'=>false
                ],
                [
                    'attribute' => 'property_id',

                    'value' => function($model){
                        return (isset($model->property->property_no))?$model->property->property_no." - ".$model->property->title:'';
                    },
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->asArray()->all(), 'id', function($model) {
                        return $model['property_no']." - ".$model['title'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select Property'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                            //'width'=>'resolve'
                        ],
                    ],
                    //'filter'=>false
                ],
                //'property_id',
                // 'vendor_id',
                // 'promo_code',
                // 'request_id',
                // 'renovation_quote_id',
                // 'topup_id',
                // 'withdrawal_id',
                // 'package_id',
                // 'todo_id',
                 'amount',
                [
                    'attribute' => 'sst',

                    'value' => function($model){
                        return (isset($model->sst) && $model->sst!='')?$model->sst:'';
                    },
                ],
                [
                    'attribute' => 'discount',

                    'value' => function($model){
                        return (isset($model->discount) && $model->discount!='')?$model->discount:'';
                    },
                ],
                [
                    'attribute' => 'coins',

                    'value' => function($model){
                        return (isset($model->coins) && $model->coins!='')?$model->coins:'';
                    },
                ],
                //'coins',
                 //'coins_savings',
                 'total_amount',
                // 'olduserbalance',
                // 'oldlandlordbalance',
                // 'oldagentbalance',
                // 'oldvendorbalance',
                // 'newuserbalance',
                // 'newlandlordbalance',
                // 'newagentbalance',
                // 'newvendorcbalance',
                // 'type',
                [
                    'attribute' => 'reftype',

                    'value' => function($model){
                        return $model->reftype;
                    },
                    'filter'=>array("Monthly Rental"=>"Monthly Rental","Booking Payment"=>"Booking Payment","Moveout Refund"=>"Moveout Refund","Renovation Payment"=>"Renovation Payment","Insurance"=>"Insurance","Defect Report"=>"Defect Report","Cancellation Refund"=>"Cancellation Refund","Service"=>'Service',"Agent Commision"=>"Agent Commision","Withdrawal"=>"Withdrawal","General"=>"General","Package Purchase"=>"Package Purchase"),
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'prompt' => 'All'
                    ],
                    //'filter'=>false
                ],
                 //'reftype',
                // 'status',
                // 'created_at',
                // 'updated_at',

            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
