<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ServiceRequestsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-requests-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h2>Service Requests</h2>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
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
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in', 'role', ['User','Agent']])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


                    //'filter'=>false
                ],
                //'user_id',
                [
                    'attribute' => 'vendor_id',

                    'value' => 'vendor.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in', 'role', ['Cleaner','Mover','Laundry','Handyman']])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                //'property_id',
                //'vendor_id',
                //'user_id',
                //'todo_id',
                // 'date',
                // 'time',
                // 'hours',
                // 'description:ntext',
                // 'pickup_location:ntext',
                // 'dropoff_location:ntext',
                // 'truck_size',
                // 'document',
                // 'amount',
                // 'subtotal',
                // 'sst',
                // 'total_amount',
                [
                    'attribute'=>'reftype',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getReftype($model->reftype);
                    },
                    'filter'=>array("Cleaner"=>"Cleaner","Laundry"=>"Laundry","Handyman"=>"Handyman","Mover"=>"Mover"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],
                 //'reftype',
                [
                    'attribute'=>'status',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getStatus($model->status);
                    },
                    'filter'=>array("New"=>"New","Pending"=>"Pending","Accepted"=>"Accepted","Confirmed"=>"Confirmed","Cancelled"=>"Cancelled","Picked Up"=>"Picked Up","Out For Delivery"=>"Out For Delivery","Rejected"=>"Rejected"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],
                // 'status',
                'booked_at:datetime',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:18%'],
                    'template'=>'{view} {update} {gallery} {uploadquote} {issueinvoice} {refund} {cancel}',
                    'visibleButtons' => [
                        'gallery' => function ($model){
                            return ($model->reftype!='Mover');
                        },
                        'uploadquote'=> function($model){
                           return (($model->reftype=='Handyman' || $model->reftype=='Mover') && ($model->status=='New'));
                        },
                        'update' => function ($model){
                            return (($model->reftype=='Handyman' || $model->reftype=='Mover') && ($model->status!='Refund Requested' && $model->status!='Refund Rejected' && $model->status!='Refunded' && $model->status!='Completed'));//|| $model->status!='Refund Rejected' || $model->status!='Refunded' || $model->status!='Completed'));//&& ($model->status=='Confirmed')
                        },
                        'issueinvoice'=> function($model){
                            return (($model->reftype=='Handyman' || $model->reftype=='Mover') && ($model->status=='Accepted' || $model->status=='Unpaid'));
                        },
                        'refund'=> function($model){
                   return (($model->reftype=='Handyman' || $model->reftype=='Mover') && ($model->status=='Cancelled'));
             }
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
                        'gallery' => function ($url, $model) {

                            return Html::a('<i class="fa fa-picture-o" aria-hidden="true"></i>', ['servicerequestimages/create','id'=>$model->id], [

                                'title' => 'Images',
                                'class'=>'btn btn-sm bg-blue datatable-operation-btn'

                            ]);

                        },
                        'update' => function ($url, $model) {

                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/updatestatus', 'id' => $model->id])], [

                                'title' => 'Update Status',
                                'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                            ]);

                        },
                        'uploadquote' => function ($url, $model) {

                            return Html::a('<i class="fa fa-file" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadquote', 'id' => $model->id])], [

                                'title' => 'Upload Quote',
                                'class' =>'btn btn-sm bg-purple datatable-operation-btn'

                            ]);

                        },
                        'issueinvoice' => function ($url, $model) {
                            $requestexist = \app\models\TodoList::find()->where(['id'=>$model->todo_id,'reftype'=>'Service'])->one();
                            if(!empty($requestexist)){
                                $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/issueinvoiceupdate', 'id' => $model->id]);
                            }else{
                                $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/issueinvoice', 'id' => $model->id]);
                            }
                            return Html::a('<i class="fa fa-money" aria-hidden="true"></i>', [$url], [

                                'title' => 'Issue Invoice',
                                'class' =>'btn btn-sm bg-orange datatable-operation-btn'

                            ]);

                        },

                        'refund' => function ($url, $model) {
                            $requestexist = \app\models\TodoList::find()->where(['id'=>$model->todo_id,'reftype'=>'Service'])->one();
                            if(!empty($requestexist)){
                                $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/refund', 'id' => $model->id]);
                            }else{
                                $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/refund', 'id' => $model->id]);
                            }
                            return Html::a('<i class="fa fa-money" aria-hidden="true"></i>', [$url], [

                                'title' => 'Refund',
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
