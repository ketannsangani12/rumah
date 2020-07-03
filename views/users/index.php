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
<div class="users-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'tableOptions' => [
                'id' => 'theDatatable',
                'class'=>'table table-bordered table-striped table-condensed flip-content'
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute'=>'role',

                    'filter'=>array('Superadmin'=>'Superadmin','PE'=>'PE','FE'=>'FE','OE'=>'OE','Cleaner'=>'Cleaner','Mover'=>'Mover'),

                ],
                'full_name',
                'company_name',
                'registration_no',
                // 'wallet_balance',
                // 'contact_no',
                // 'email:email',
                // 'company_name',
                // 'hp_no',
                // 'company_address:ntext',
                // 'company_state',
                // 'registration_no',
                // 'bank_account_name:ntext',
                // 'bank_account_no',
                // 'bank_name',
                // 'image',
                // 'password',
                // 'secondary_password',
                // 'token',
                // 'verify_token',
                // 'reset_token',
                // 'firebase_token',
                // 'device_token',
                // 'referred_by',
                // 'status',
                // 'created_at',
                // 'updated_at',
                ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{view} {update} {delete}',

                    'buttons'=>[

                        'view' => function ($url, $model) {

                            return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>', [\yii\helpers\Url::to([Yii::$app->controller->id.'/view', 'id' => $model->id])], [

                                'title' => 'View',
                                'class'=>'btn btn-sm bg-purple datatable-operation-btn'

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
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
