<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-circle green-meadow']) ?>
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

            //'id',
            'email',
            //'role',
            [
                'attribute'=>'role',

                'filter'=>array('Superadmin'=>'Superadmin','PE'=>'PE','FE'=>'FE','OE'=>'OE','Cleaner'=>'Cleaner','Mover'=>'Mover'),

            ],
            'first_name',
            'last_name',
            'company_name',
            'registration_no',
            //'wallet_balance',
            //'contact_no',
            //'email:email',
            //'country_of_residence',
            //'hp_no',
            //'address:ntext',
            //'state',
            //'postcode',
            //'image',
            //'password',
            //'secondary_password',
            //'token',
            //'verify_token',
            //'reset_token',
            //'firebase_token',
            //'device_token',
            //'referred_by',
            //'status',
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

                        return Html::a('<i class="fa fa-times" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/delete', 'id' => $model->id])], [

                            'title' => 'Delete',
                            'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method'  => 'post',
                            'class' =>'btn btn-circle btn-icon-only red'

                        ]);

                    }



                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
