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
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                 'reference_no',
                [
                    'attribute' => 'property_id',

                    'value' => 'property.title',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->where(['digital_tenancy'=>1])->asArray()->all(), 'id', function($model) {
                        return $model['property_no']." - ".$model['title'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


                    //'filter'=>false
                ],
                //'user_id',
                [
                    'attribute' => 'landlord_id',

                    'value' => 'landlord.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                //'landlord_id',
                //'template_id',
                'credit_score',
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
                    'filter'=>array("New"=>"New","Pending"=>"Pending","Declined"=>"Declined","Approved"=>"Approved","Agreement Processed"=>"Agreement Processed","Terminated"=>"Terminated","Cancelled"=>"Cancelled","Payment Requested"=>"Payment Requested","Rented"=>"Rented"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],
                 //'status',
                // 'security_deposit',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:18%'],
                    'template'=>'{view} {update} {choosetemplate} {uploadagreement} {uploadmoveout} {moveoutinvoice} {cancel}',
                    'visibleButtons' => [
                        'choosetemplate' => function ($model) {
                            return ($model->status=='Approved' || $model->status=='Agreement Processed');
                        },
                        'uploadagreement' => function ($model) {
                            return ($model->status=='Agreement Processed' || $model->status=='Payment Requested' || $model->status=='Rented');
                        },
                        'uploadmoveout' => function ($model) {
                            return ($model->status=='Rented');
                        },
                        'moveoutinvoice' => function ($model){
                            return ($model->status=='Rented' && $model->moveout_document!='');
                        },
                        'cancel' => function ($model){
                            return ($model->status=='Agreement Processed' || $model->status=='Cancelled');
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
                        'uploadagreement' => function ($url, $model) {

                            return Html::a('<i class="fa fa-file" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadagreement', 'id' => $model->id])], [

                                'title' => 'Upload Agreement',
                                'class' =>'btn btn-sm bg-purple datatable-operation-btn'

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
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
