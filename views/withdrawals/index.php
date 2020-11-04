<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\WithdrawalsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Withdrawals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdrawals-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
    </div>
    <div class="box-body table-responsive no-padding">
        <?php
        $gridColumns = [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'reference_no',
            [
                'attribute' => 'user_id',

                'value' => 'user.full_name',
                'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'User'])->asArray()->all(), 'id', function($model) {
                    return $model['full_name'];
                }),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


                //'filter'=>false
            ],
            // 'user_id',
            [
                'attribute'=>'bank_id',
                'format'=>'raw',
                'value'=> function($model){
                    return $model->user->bank_account_name;
                },
                'filter'=>false,

            ],
            [
                'label'=>'Account No.',
                'format'=>'raw',
                'value'=> function($model){
                    return $model->user->bank_account_no;
                },
                'filter'=>false,

            ],
            //'bank_id',
            'amount',
            // 'fees',
            // 'total_amount',
            // 'old_balance',
            // 'new_balance',
            [
                'attribute'=>'status',
                'format'=>'raw',
                'value'=> function($model){
                    return Yii::$app->common->getStatus($model->status);
                },
                'filter'=>array("Pending"=>"Pending","Completed"=>"Completed","Failed"=>"Declined"),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

            ],
            'created_at:datetime',
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
                'visibleButtons' => [
                    //'update' => function ($model) {
                    // return ($model->status=='Pending');
                    // },
                ],
                'buttons'=>[


                    'update' => function ($url, $model) {

                        return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                            'title' => 'Update',
                            'class' =>'btn btn-sm btn-warning datatable-operation-btn'

                        ]);

                    },




                ],
            ],
        ];
        echo \kartik\export\ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,

        ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => $gridColumns,
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
