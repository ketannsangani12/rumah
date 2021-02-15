<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlatformFeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Platform Fees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="platform-fees-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Platform Fees', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                'name',
                'platform_fees',
                'other',
                //'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update}',

                    'buttons'=>[

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
</div>
