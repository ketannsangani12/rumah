<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Renovation Milestones - '.$model->property->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo 'Renovation Milestones - '.$model->property->title;?>
            <?= Html::a('Add Milestones', [\yii\helpers\Url::to([Yii::$app->controller->id.'/createmilestone', 'id' => $model->id])], ['class' => 'btn btn-primary btn-flat pull-right']);?>
        </h3>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [

                ['class' => 'yii\grid\SerialColumn'],
                'title',
                [
                    'attribute' => 'property_id',

                    'value' => 'property.title'

                    //'filter'=>false
                ],
                [
                    'attribute' => 'landlord_id',

                    'value' => 'landlord.full_name',


                    //'filter'=>false
                ],
                // 'id',
                //'property_id',
                //'landlord_id',
                //'quote_document',
                [
                    'attribute' => 'status',
                    'format' =>'raw',
                    'value'  => function($model){
                        return Yii::$app->common->getStatus($model->status);
                    }
                    //'filter' => array('Pending'=>'Pending','Approved'=>'Approved','Rejected'=>'Rejected','Work In Progress'=>'Work In Progress','Completed'=>'Completed')
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
                    'template'=>'{view} {update} {uploaddocument}',
                    'buttons'=>[
                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/viewmilestone', 'id' => $model->id])], [

                                'title' => 'View Milestone Detail',
                                'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                            ]);

                        },

                        'update' => function ($url, $model) {

                            return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/updatemilestone', 'id' => $model->id])], [

                                'title' => 'Update',
                                'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                            ]);

                        },
                        'uploaddocument' => function ($url, $model) {

                            return Html::a('<i class="fa fa-file" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/uploadmilestonedocument', 'id' => $model->id])], [

                                'title' => 'Upload Documents',
                                'class' =>'btn btn-sm bg-purple datatable-operation-btn'

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
