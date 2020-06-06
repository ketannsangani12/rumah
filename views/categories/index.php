<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

    <h3 class="page-title"><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Categories', ['create'], ['class' => 'btn btn-circle green-meadow']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'tableOptions' => [
            'id' => 'theDatatable',
            'class'=>'table table-bordered table-striped table-condensed flip-content'
        ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
            'image',
            'order_position',
            //'is_default',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:12%'],

                'template'=>'{view} {update} {delete}',

                'buttons'=>[
                    'view' => function ($url, $model) {

                        return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                            'title' => 'View',
                            'class'=>'btn btn-circle btn-icon-only yellow'

                        ]);

                    },
                    'update' => function ($url, $model) {

                        return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                            'title' => 'Update',
                            'class' =>'btn btn-circle btn-icon-only green'

                        ]);

                    },
                    'delete' => function ($url, $model) {

                        return Html::a('<i class="fa fa-times" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/update', 'id' => $model->id])], [

                            'title' => 'Update',
                            'class' =>'btn btn-circle btn-icon-only red'

                        ]);

                    }



                ],
            ],        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
