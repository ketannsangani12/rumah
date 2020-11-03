<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Renovation Quotes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('Add Renovation Quote', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php
        $gridColumns = [

                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'property_id',

                    'value' => 'property.title',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->asArray()->all(), 'id', function($model) {
                        return $model['title'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                [
                    'attribute' => 'landlord_id',

                    'value' => 'landlord.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                [
                    'label' => 'Email',

                    'value' => 'landlord.email',
                    'filter'=>false,

                    //'filter'=>false
                ],
                [
                    'label' => 'Phone No.',

                    'value' => 'landlord.contact_no',
                    'filter'=>false,

                    //'filter'=>false
                ],
                // 'id',
                //'property_id',
                //'landlord_id',
                //'quote_document',
                [
                    'attribute' => 'status',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getStatus($model->status);
                    },
                    'filter' => array('Pending'=>'Pending','Approved'=>'Approved','Rejected'=>'Rejected','Work In Progress'=>'Work In Progress','Completed'=>'Completed'),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


                ],
                //'status',
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return date('d-m-Y',strtotime($model->created_at));
                    },
                    'filter' => false
                ],
                //'created_at:date',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {update} {addmilestone} {delete}',
                    'visibleButtons' => [
                        'addmilestone' => function ($model) {
                            return ($model->status=='Approved' || $model->status=='Work In Progress' || $model->status=='Completed');
                        },
                        'delete' =>function ($model) {
                            return ($model->status=='Approved' || $model->status=='Work In Progress');
                        },
                    ],
                    'buttons'=>[
                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                                'title' => 'View',
                                'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                            ]);

                        },
                        'update' => function ($url, $model) {

                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                                'title' => 'Update',
                                'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                            ]);

                        },
                        'addmilestone' => function ($url, $model) {
                            $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/milestones', 'id' => $model->id]);

                            return Html::a('<i class="fa fa-money" aria-hidden="true"></i>', [$url], [

                                'title' => 'Payment Requests',
                                'class' =>'btn btn-sm bg-blue datatable-operation-btn'

                            ]);

                        },
                        'delete' => function ($url, $model) {

                            return Html::a('<i class="fa fa-times-circle" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/delete', 'id' => $model->id])], [

                                'title' => 'Close Renovation',
                                'class' =>'btn btn-sm btn-danger datatable-operation-btn',
                                'data-confirm' => \Yii::t('yii', 'Are you sure you want to Close this Renovation?'),
                                'data-method'  => 'post',

                            ]);

                        },



                    ],
                ],

        ];
        echo \kartik\export\ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,

        ]);
        ?><?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => $gridColumns,
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
