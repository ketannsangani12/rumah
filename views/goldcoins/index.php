<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GoldTransactionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gold Coins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gold-transactions-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
<h3><?php echo $this->title;?></h3>    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= \kartik\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['User']])->asArray()->all(), 'id', function($model) {
                        return $model['userid'] ." - ". $model['full_name'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select User'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                        ],
                    ],

                    //'filter'=>false
                ],
                //'user_id',
                //
                [
                    'attribute' => 'refferer_id',

                    'value' => 'reffererser.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['in','role',['User']])->asArray()->all(), 'id', function($model) {
                        return $model['userid'] ." - ". $model['full_name'];
                    }),
                    'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['prompt' => 'Select Refferer User'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'width'=>'90px'
                        ],
                    ],

                    //'filter'=>false
                ],
               // 'refferer_id',
                'gold_coins',
                // 'olduserbalance',
                // 'newuserbalance',
                // 'oldreffererbalance',
                // 'newreffererbalance',
                [
                    'attribute'=>'reftype',
                    'format'=>'raw',
                    'value'=> function($model){
                        return Yii::$app->common->getGolcointype($model->reftype);
                    },
                    'filter'=>array("Rental On Time"=>"Rental On Time","In App Purchase"=>"In App Purchase","Onboarding"=>"Onboarding","Tenancy signed"=>"Tenancy signed","1st Rent Listed"=>"1st Rent Listed"),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                ],
                 //'reftype',
                // 'status',
                [
                    // the attribute
                    'attribute' => 'created_at',
                    'noWrap'=>true,

                    // format the value
                    'value' => function ($model) {

                        return date('d-m-Y h:i:s a', strtotime($model->created_at));

                    },
                    // some styling?


                    'filter' => false
                    // here we render the widget

                    //'format' => ['datetime', Yii::$app->formatter->datetimeFormat]
                ],
                 //'created_at',
                // 'updated_at',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
