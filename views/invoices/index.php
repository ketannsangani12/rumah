<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TodoListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-list-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo $this->title;?>
            <?= Html::a('Add Invoice', ['create'], ['class' => 'btn btn-primary btn-flat pull-right']) ?>
        </h3>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]);
        $gridColumns = [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            [
                'attribute' => 'property_id',

                'value' => function($model){
                    return $model->property->property_no." - ".$model->property->title;
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->where(['insurance'=>1])->asArray()->all(), 'id', function($model) {
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
            //'description',
            [
                'attribute' => 'landlord_id',

                'value' => 'landlord.full_name',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['User']])->asArray()->all(), 'id', function($model) {
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
            [
                'attribute' => 'user_id',

                'value' => 'user.full_name',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['User']])->asArray()->all(), 'id', function($model) {
                    return $model['full_name'];
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
            //'property_id',
            // 'user_id',
            //'landlord_id',
            // 'vendor_id',
            [
                'label'=>'Pay From',
                'attribute'=>'pay_from',
            ],
            // 'pay_from',
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
            'due_date:date',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:8%'],
                'template'=>'{view} {update}',
                'visibleButtons' => [
                    'update' => function ($model) {
                        return ($model->status=='Unpaid');
                    },
//                        'uploaddocument' => function ($model) {
//                            return ($model->status=='Paid' || $model->status=='Completed');
//                        }
                ],
                'buttons'=>[
                    'view' => function ($url, $model) {

                        return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                            'title' => 'View Invoice Detail',
                            'class'=>'btn btn-sm bg-olive datatable-operation-btn'

                        ]);

                    },

                    'update' => function ($url, $model) {

                        return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                            'title' => 'Update Invoice',
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
