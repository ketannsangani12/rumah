<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceRequests */

$this->title = "Service Request Detail";
$this->params['breadcrumbs'][] = ['label' => 'Service Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-requests-view box box-primary">
    <div class="box-header">
     <h3><?php echo $this->title; ?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        //'id',
                        'reference_no',
                        [
                            'label'=>'Property',

                            'value'=>function($model){
                                return (isset($model->property->title))?$model->property->property_no." - ".$model->property->title:'';
                            }
                        ],
                        [
                            'label'=>'User',

                            'value'=>function($model){
                                return (isset($model->user->full_name))?$model->user->full_name:'';
                            }
                        ],
                        [
                            'label'=>'Vendor',

                            'value'=>function($model){
                                return (isset($model->vendor->full_name))?$model->vendor->full_name:'';
                            }
                        ],
                        [
                            'attribute' => 'reftype',
                            'label' => 'Service',
                            'value' => function ($model) {
                                return Yii::$app->common->getReftype($model->reftype);
                            },
                            'format' => 'raw',
                        ],

                        //'property_id',
                        //'user_id',
                        //'landlord_id',
                        //'template_id',
                        'date:date',
                        'time',
                        //'tenancy_period',
                        [
                            'attribute' => 'status',
                            'label' => 'Status',
                            'value' => function ($model) {
                                return Yii::$app->common->getStatus($model->status);
                            },
                            'format' => 'raw',
                        ],
                        //'status',
                        'booked_at:datetime',
                        'todo.payment_date:datetime',
                        'checkin_time:datetime',
                        'checkout_time:datetime',
                        'pickup_time:datetime',
                        [
                            'label' => 'Delivery Time',
                            'value' => function ($model) {
                                return ($model->reftype=='Laundry' && $model->status=='Completed')?date('M d,Y h:i:s a'):"";
                                },
                           // 'format' => 'raw',
                        ],
                        // 'created_at:datetime',
                        //'updated_at:datetime',
                    ],
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'pickup_location',
                        'dropoff_location',
                        'truck_size',
                        [
                            'label'=>'Subtotal',

                            'value'=>function($model){
                                return (isset($model->todo->subtotal))?$model->todo->subtotal:'';
                            }
                        ],

                        [
                            'label'=>'SST',

                            'value'=>function($model){
                                return (isset($model->todo->sst))?$model->todo->sst:'';
                            }
                        ],
                        [
                            'label'=>'Total',

                            'value'=>function($model){
                                return (isset($model->todo->total))?$model->todo->total:'';
                            }
                        ],
                        //'subtotal:currency',
                       // 'sst:currency',
                        //'total_amount:currency',
                        [
                            'label' => 'Work / Quotation Order',
                            'value' => function ($model,$documentstenants) {
                                return (!empty($model) && isset($model->todo->document) && $model->todo->document!='')?Html::a('Download', Yii::$app->homeUrl.'uploads/tododocuments/'.$model->todo->document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],

                        //'id',


                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>Invoice Detail</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'description',
                        'price:currency'
                        //'created_at:date',
                        // 'updated_at',


                    ],
                ]); ?>
            </div>
            <div class="col-md-6">
                <h4>Refund Detail</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $dataProvider1,
                    //'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'description',
                        'price:currency'
                        //'created_at:date',
                        // 'updated_at',


                    ],
                ]); ?>
            </div>
            </div>
        <br><br>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
</div>
