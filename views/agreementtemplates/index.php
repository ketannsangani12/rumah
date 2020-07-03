<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AgreementTemplatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agreement Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-templates-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('Create Agreement Templates', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
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
                //'document',
                //'created_at',
                //'updated_at',

                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update}',

                    'buttons'=>[

                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-download" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/download', 'id' => $model->id])], [

                                'title' => 'Download',
                                'class'=>'btn btn-sm bg-purple datatable-operation-btn'

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
