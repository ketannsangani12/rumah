<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TodoListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Defect Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-list-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo $this->title;?>
        </h3>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'property_id',

                    'value' => function($model){
                       return $model->property->property_no." - ".$model->property->title;
                    },
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->where(['insurance'=>1])->asArray()->all(), 'id', function($model) {
                        return $model['property_no']." - ".$model['title'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                'description',
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['Tenant']])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                //'property_id',
                // 'user_id',
                 //'landlord_id',
                // 'vendor_id',
                // 'document',
                // 'reftype',
                [
                    'attribute'=>'status',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getStatus($model->status);
                    },
                    'filter'=>array("Pending"=>"Pending","Unpaid"=>"Unpaid","Paid"=>"Paid","Completed"=>"Completed"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],
                // 'status',
                 'created_at:datetime',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {updatequote}',
                    'visibleButtons' => [
//                        'update' => function ($model) {
//                            return ($model->status=='Unpaid');
//                        },
//                        'uploaddocument' => function ($model) {
//                            return ($model->status=='Paid' || $model->status=='Completed');
//                        }
                    ],
                    'buttons'=>[
                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                                'title' => 'View Defect Report Detail',
                                'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                            ]);

                        },

                        'updatequote' => function ($url, $model) {
                            if($model->status=='Pending'){
                                $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/createquote', 'id' => $model->id]);
                            }else{
                                $url = \yii\helpers\Url::to([Yii::$app->controller->id.'/updatequote', 'id' => $model->id]);
                            }

                            return Html::a('<i class="fa fa-money" aria-hidden="true"></i>', [$url], [

                                'title' => 'Defect Report Quote',
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
