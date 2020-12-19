<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Managed Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="properties-index box box-primary">
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
                'property_no',
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
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
                //'user_id',
                //'pe_userid',
                'title:ntext',
                //'description:ntext',
                 'location:ntext',
                // 'latitude',
                // 'longitude',
                [
                    'attribute' => 'property_type',

                    'value' => 'property_type',
                    'filter'=> Yii::$app->common->propertytype(),

                    //'filter'=>false
                ],
                 //'property_type',
                // 'room_type',
                // 'preference',
                [
                    'attribute' => 'availability',

                    'value' => function($model){
                        return date('d-m-Y',strtotime($model->availability));
                    },
                    'filter'=>false

                    //'filter'=>false
                ],
                'status',
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {gallery} {managedlisting} {update} {delete}',

                    'buttons'=>[
                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                                'title' => 'View',
                                'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                            ]);

                        },
                        'managedlisting' => function ($url, $model,$managedlisting) {

                            return Html::a('<i class="fa fa-minus" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/removefrommanagelisting', 'id' => $model->id])], [

                                'title' => 'Remove From Managed Listing',
                                'class'=>'btn btn-sm bg-navy datatable-operation-btn'
                            ]);

                        },

                        'gallery' => function ($url, $model) {

                            return Html::a('<i class="fa fa-picture-o" aria-hidden="true"></i>', ['images/create','id'=>$model->id], [

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
