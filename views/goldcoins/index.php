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
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


                    //'filter'=>false
                ],
                //'user_id',
                //
                [
                    'attribute' => 'refferer_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


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
                 'created_at:datetime',
                // 'updated_at',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>