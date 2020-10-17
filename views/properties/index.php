<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="properties-index box box-primary">
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
               'property_no',
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),

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
                 //'availability',
                // 'bedroom',
                // 'bathroom',
                // 'carparks',
                // 'furnished_status',
                // 'size_of_area',
                // 'price',
                // 'amenities:ntext',
                // 'commute',
                // 'digital_tenancy',
                // 'auto_rental',
                // 'insurance',
                [
                    'attribute'=>'status',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getStatus($model->status);
                    },
                    'filter'=>array("Active"=>"Active","Inactive"=>"Inactive","Rented"=>"Rented","Suspended"=>"Suspended"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],
                //'status',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {managedlisting} {gallery} {update} {delete}',
                    'visibleButtons' => [
                        'managedlisting' => function ($model) use ($managedlisting) {
                            return $managedlisting==false;
                        },

                    ],
                    'buttons'=>[
//
                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                                'title' => 'View',
                                'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                            ]);

                        },

                        'managedlisting' => function ($url, $model,$managedlisting) {

                            return Html::a('<i class="fa fa-plus" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/addtomanagelisting', 'id' => $model->id])], [

                                'title' => 'Add to Managed Listing',
                                'class'=>'btn btn-sm bg-navy datatable-operation-btn'
                            ]);

                        },
                        'gallery' => function ($url, $model) {

                            return Html::a('<i class="fa fa-picture-o" aria-hidden="true"></i>', ['images/create','id'=>$model->id], [

                                'title' => 'Images',
                                'class'=>'btn btn-sm bg-blue datatable-operation-btn'

                            ]);

                        },
                        'update' => function ($url, $model) {

                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                                'title' => 'Update',
                                'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                            ]);

                        },
                        'ratings' => function ($url, $model) {


                            return Html::a('<i class="fa fa-star" aria-hidden="true"></i>', [\yii\helpers\Url::to(['propetyratings/index', 'id' => $model->id])], [

                                'title' => 'Ratings',
                                'class' =>'btn btn-sm bg-olive datatable-operation-btn'

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
