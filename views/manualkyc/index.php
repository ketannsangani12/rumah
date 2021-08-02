<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ManualKycSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manual Kycs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-kyc-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3>Manual E-Kyc Data</h3>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'attribute' => 'request_id',

                    'value' => 'request.reference_no',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\BookingRequests::find()->where(['!=','status','Rented'])->asArray()->all(), 'id', function($model) {
                        return $model['reference_no'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select Booking Request'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                        ],
                    ],
                    //'filter'=>false
                ],
                [
                    'attribute' => 'user_id',

                    'value' => function($model){
                        if($model->full_name!=''){
                            return  $model->full_name;
                        }else{
                            $userdetails = \app\models\Users::find()->select(['full_name'])->where(['id'=>$model->user_id])->one();
                            return $userdetails->full_name;
                        }
                    },
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                        return $model['userid'] ." - ". $model['full_name'];
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
                //'user_id',
                'document_no',
                'type',
                //'document:ntext',
                // 'selfie:ntext',
                [
                    'attribute'=>'status',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getStatus($model->status);
                    },
                    'filter'=>array("Pending"=>"Pending","Rejected"=>"Rejected","Approved"=>"Approved"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],

                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {update} ',

                    'buttons'=>[

                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                                'title' => 'View',
                                'class'=>'btn btn-sm btn-primary datatable-operation-btn'

                            ]);

                        },
                        'update' => function ($url, $model) {

                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                                'title' => 'Update',
                                'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                            ]);

                        },


                    ],
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
