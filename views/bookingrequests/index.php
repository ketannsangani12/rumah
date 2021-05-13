<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingRequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Booking Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-requests-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
    </div>
    <div class="box-body table-responsive">
        <?php
        $gridColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            'reference_no',
            [
                'attribute' => 'property_id',

                'value' => 'property.title',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->where(['digital_tenancy'=>1])->asArray()->all(), 'id', function($model) {
                    return $model['property_no']." - ".$model['title'];
                }),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Select Property'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        //'width'=>'90px'
                    ],
                ],
                //'filter'=>false
            ],
            [
                'attribute' => 'user_id',

                'value' => 'user.full_name',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                    return $model['userid'] ." - ". $model['full_name'];
                }),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Select Tenant'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        //'width'=>'90px'
                    ],
                ],

                //'filter'=>false
            ],
            //'user_id',
            [
                'attribute' => 'landlord_id',

                'value' => 'landlord.full_name',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->asArray()->all(), 'id', function($model) {
                    return $model['userid'] ." - ". $model['full_name'];
                }),
                'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => 'Select Landlord'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        //'width'=>'90px'
                    ],
                ],
                //'filter'=>false
            ],
            //'landlord_id',
            //'template_id',
            //'credit_score',
            // 'booking_fees',
            // 'tenancy_fees',
            // 'stamp_duty',
            // 'fees',
            // 'sst',
            // 'rental_deposit',
            // 'utilities_deposit',
            'commencement_date:date',
            'tenancy_period',
            [
                'attribute'=>'status',
                'format'=>'raw',
                'value'=> function($model){
                    return Yii::$app->common->getStatus($model->status);
                },
                'filter'=>array("New"=>"New","Pending"=>"Pending","Declined"=>"Declined","Approved"=>"Approved","Processing"=>"Processing","Processed"=>"Processed","Agreement Processed"=>"Agreement Processed","Terminated"=>"Terminated","Cancelled"=>"Cancelled","Payment Requested"=>"Payment Requested","Rented"=>"Rented","Moved Out"=>"Moved Out"),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

            ],
            [
                // the attribute
                'attribute' => 'updated_at',
                'vAlign'=>'middle',
                //    'filterType'=> \kartik\grid\GridView::FILTER_DATE_RANGE,

                //'width'=>'20%',
                'noWrap'=>true,

                // format the value
                'value' => function ($model) {

                    return ($model->updated_at!='')?date('M d,Y h:i:s a', strtotime($model->updated_at)):'';

                },
                // some styling?


                'filter' => false
                // here we render the widget

                //'format' => ['datetime', Yii::$app->formatter->datetimeFormat]
            ],
            //'updated_at:datetime',
            [
                'label' => 'Last Updated By',
                'attribute' => 'updated_by',

                'value' => 'updatedby.full_name',
                'filter'=>false,
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                //'filter'=>false
            ],
            //'status',
            // 'security_deposit',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:25%'],
                'template'=>'{view} {update} {choosetemplate} {msctrustgate} {download} {uploadagreement} {uploadmovein} {uploadmoveout} {moveoutinvoice} {cancel}',
                'visibleButtons' => [
                    'update' => function ($model) {
                        return ($model->status=='Confirmed');
                    },
                    'choosetemplate' => function ($model) {
                        return ($model->status=='Processed' || $model->status=='Agreement Processing');
                    },
                    'msctrustgate' => function ($model) {
                        return ($model->status=='Agreement Processing');
                    },
                    'download' => function ($model) {
                        return ($model->status=='Agreement Processed');
                    },
                    'uploadagreement' => function ($model) {
                        return ($model->status=='Rented' && $model->signed_agreement_document!='');
                    },
                    'uploadmovein' => function ($model) {
                        $reviewexist = \app\models\PropertyRatings::find()->where(['request_id'=>$model->id,'user_id'=>$model->user_id,'property_id'=>$model->property_id])->one();

                        return ($model->status=='Rented' && empty($reviewexist));
                    },
                    'uploadmoveout' => function ($model) {
                        return ($model->status=='Rented');
                    },
                    'moveoutinvoice' => function ($model){
                        return ($model->status=='Rented' && $model->moveout_document!='');
                    },
                    'cancel' => function ($model){
                        return ($model->status=='Agreement Processed' || $model->status=='Agreement Processing' || $model->status=='Cancelled');
                    },
                ],
                'buttons'=>[
//
//                        'view' => function ($url, $model) {
//
//                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [
//
//                                'title' => 'View',
//                                'class'=>'btn btn-sm btn-primary datatable-operation-btn'
//
//                            ]);
//
//                        },

                    'view' => function ($url, $model) {

                        return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                            'title' => 'Images',
                            'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                        ]);

                    },
                    'update' => function ($url, $model) {

                        return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                            'title' => 'Update',
                            'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                        ]);

                    },
                    'choosetemplate' => function ($url, $model) {

                        return Html::a('<i class="fa fa-file-text-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/choosetemplate', 'id' => $model->id])], [

                            'title' => 'Choose Template',
                            'class' =>'btn btn-sm bg-blue datatable-operation-btn'

                        ]);

                    },
//                    'msctrustgate' => function ($url, $model) {
//
//                        return Html::a('<i class="fa fa-certificate" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadtomsc', 'id' => $model->id])], [
//
//                            'title' => 'Send Agreement For Signing',
//                            'class' =>'btn btn-sm bg-red datatable-operation-btn'
//
//                        ]);
//
//                    },
                    'download' => function ($url, $model) {

                        return Html::a('<i class="fa fa-download" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/download', 'id' => $model->id])], [
                            'data-pjax' => 0,
                            'title' => 'Download Signed Agreement',
                            'class' =>'btn btn-sm bg-green datatable-operation-btn',
                            //'target' => '_blank'

                        ]);

                    },
                    'uploadagreement' => function ($url, $model) {

                        return Html::a('<i class="fa fa-legal" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadagreement', 'id' => $model->id])], [

                            'title' => 'Upload Stamp Duty Certificate',
                            'class' =>'btn btn-sm bg-purple datatable-operation-btn'

                        ]);

                    },
                    'uploadmovein' => function ($url, $model) {

                        return Html::a('<i class="fa fa-file" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadmovein', 'id' => $model->id])], [

                            'title' => 'Upload Move In Checklist',
                            'class' =>'btn btn-sm bg-green datatable-operation-btn'

                        ]);

                    },
                    'uploadmoveout' => function ($url, $model) {

                        return Html::a('<i class="fa fa-sign-out" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadmoveout', 'id' => $model->id])], [

                            'title' => 'Upload Move Out Checklist',
                            'class' =>'btn btn-sm bg-red datatable-operation-btn'

                        ]);

                    },
                    'moveoutinvoice' => function ($url, $model) {
                        $requestexist = \app\models\TodoList::find()->where(['request_id'=>$model->id,'reftype'=>'Moveout Refund','status'=>'Pending'])->one();
                        if(!empty($requestexist)){
                            $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/moveoutinvoiceupdate', 'id' => $model->id]);
                        }else{
                            $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/moveoutinvoice', 'id' => $model->id]);
                        }
                        return Html::a('<i class="fa fa-money" aria-hidden="true"></i>', [$url], [

                            'title' => 'Move Out Invoice',
                            'class' =>'btn btn-sm bg-blue datatable-operation-btn'

                        ]);

                    },
                    'cancel' => function ($url, $model) {
                        $requestexist = \app\models\TodoList::find()->where(['request_id'=>$model->id,'reftype'=>'Cancellation Refund'])->one();
                        if(!empty($requestexist)){
                            $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/viewcancelbooking', 'id' => $requestexist->id]);
                        }else{
                            $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/cancelbooking', 'id' => $model->id]);
                        }
                        return Html::a('<i class="fa fa-times-circle" aria-hidden="true"></i>', [$url], [

                            'title' => 'Cancel Booking',
                            'class' =>'btn btn-sm bg-red datatable-operation-btn'

                        ]);

                    },
                    'delete' => function ($url, $model) {

                        return Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/delete', 'id' => $model->id])], [

                            'title' => 'Delete',
                            'class' =>'btn btn-sm btn-danger datatable-operation-btn',
                            'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method'  => 'post',

                        ]);

                    },



                ],
            ],
        ];

        echo \kartik\export\ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'exportConfig' => [

            ]
        ]);
        ?>
        <?= \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => $gridColumns,
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
