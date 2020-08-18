<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Defect Report Details - '.$model->property->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo "Defect Report Details"." - ".$model->property->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="rown">
            <div class="col-md-6">
                <h4>Defect Report Details</h4>

                <?= \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label'=>'Landlord Or Agent',

                            'value'=>function($model){
                                return (isset($model->property->landlord->full_name))?$model->property->landlord->full_name:'';
                            }
                        ],
                        [
                            'label'=>'Tenant',

                            'value'=>function($model){
                                return (isset($model->user->full_name))?$model->user->full_name:'';
                            }
                        ],
                        ///'user_id',
                        //'pe_userid',
                        //'title:ntext',
                        'description:ntext',
                        //'location:ntext',
                        //'latitude',
                        //'longitude',
                        //'property_type',
                        'status',
                        //'room_type',
                        //'preference',
                        'created_at:date',

                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>Defect Report Quote Details</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'description',
                        'price:currency'
                        //'created_at:date',
                        // 'updated_at',


                    ],
                ]); ?>
            </div>
            <div class="col-md-6">
                <h4>Defect Report Documents</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider2,
                    //'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        //'description',
                        [
                            'attribute' => 'document',
                            'label' => 'Document',
                            'value' => function ($model) {
                                return Html::a('Download', Yii::$app->homeUrl.'uploads/tododocuments/'.$model->document,['target'=>'_blank','data-pjax'=>"0"]);
                            },
                            'format' => 'raw',
                        ],
                        //  'price:currency'
                        //'created_at:date',
                        // 'updated_at',


                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
