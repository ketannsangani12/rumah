<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TodoListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auto Rental Collections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-list-index box box-primary">

    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo $this->title;?>
        </h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <?= Html::beginForm(['/autorentalcollections/index'], 'POST'); ?>
            <div class="col-lg-2 col-xs-6">


                <?php

                $ranges = new \yii\web\JsExpression("

{
                   
					'Today'        : [Date.today(), Date.today()],
					'Yesterday'    : [Date.today().add({ days: -1 }), Date.today().add({ days: -1 })],
					'Last 7 Days'  : [Date.today().add({ days: -6 }), Date.today()],
					'Last 30 Days' : [Date.today().add({ days: -29 }), Date.today()],
					'This Month'   : [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					'This Year'    : [Date.today().moveToMonth(0,-1).moveToFirstDayOfMonth(), Date.today()],
					'Last Month'   : [Date.today().moveToFirstDayOfMonth().add({ months: -1 }), Date.today().moveToFirstDayOfMonth().add({ days: -1 })],
					// 'All Time'     : ['', Date.today()],
				}");

                // Define empty callback fust for fun
                $url = \yii\helpers\Url::to(['site/daterange']);;



                // Provide required parameters and render the widget
                echo \bburim\daterangepicker\DateRangePicker::widget([
                    'options'  => [
                        'ranges' => $ranges,
                        'format' =>'DD/MM/YYYY',
                        'locale' => [

                            'firstDay' => 1,

                        ]
                    ],
                    'htmlOptions' => [
                        'name'        => 'daterange',
                        'id'=>'daterange',
                        'class'       => 'form-control',
                        'placeholder' => 'Select Date Range',
                        'style'       => 'width:190px;',
                        'value'=>$daterange
                        //'required'=>'required'
                    ]
                ]);
                ?>
            </div>
            <div class="col-lg-1 col-xs-1">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']); ?>

            </div>
            <?= Html::endForm(); ?>

        </div>
        <br><br>
        <?php
        $gridColumns = [
            ['class' => 'yii\grid\SerialColumn'],
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
            [
                'attribute' => 'landlord_id',

                'value' => 'landlord.full_name',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['Agent','User']])->asArray()->all(), 'id', function($model) {
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
            [
                'label' => 'Month',
                'value' => function($model){
                    return date('M-Y',strtotime($model->rent_startdate));
                }
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
                'filter'=>array("Unpaid"=>"Unpaid","Paid"=>"Paid","Completed"=>"Completed"),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
                'visibleButtons' => [
                    'update' => function ($model) {
                        return ($model->status=='Unpaid');
                    },

                ],
                'buttons'=>[
                    'view' => function ($url, $model) {

                        return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                            'title' => 'View Insurance Detail',
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
        ];
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'exportConfig' => [
                ExportMenu::FORMAT_CSV => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_TEXT => false,
            ]
        ]);

        // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => $gridColumns,
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
