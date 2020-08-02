<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AgentratingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agent Ratings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-ratings-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
      <h3><?php echo $this->title;?></h3>
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
                [
                    'attribute' => 'request_id',

                    'value' => 'request.reference_no',
                    'filter'=>false
                ],
                //'request_id',
                [
                    'attribute' => 'property_id',

                    'value' => 'property.title',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Properties::find()->where(['digital_tenancy'=>1])->asArray()->all(), 'id', function($model) {
                        return $model['property_no']." - ".$model['title'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],

                    //'filter'=>false
                ],
                [
                    'attribute' => 'user_id',

                    'value' => 'user.full_name',
                    'filter'=>\yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>'Tenant'])->asArray()->all(), 'id', function($model) {
                        return $model['full_name'];
                    }),
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All'],


                    //'filter'=>false
                ],
                //'agent_id',
                 'appearance',
                 'attitude',
                 'knowledge',
                 'message:ntext',
                // 'created_at',
                // 'updated_at',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
