<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'View Cancel Booking Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo $this->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-6">
                <h4>Cancel Booking  Detail</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label'=>'Property',

                            'value'=>function($model){
                                return (isset($model->property->title))?$model->property->title:'';
                            }
                        ],
                        [
                            'label'=>'Tenant',

                            'value'=>function($model){
                                return (isset($model->user->full_name))?$model->user->full_name:'';
                            }
                        ],

                        [
                            'attribute' => 'status',
                            'label' => 'Status',
                            'value' => function ($model) {
                                return Yii::$app->common->getStatus($model->status);
                            },
                            'format' => 'raw',
                        ],
                         'created_at:datetime',
                        //'updated_at:datetime',
                    ],
                ]); ?>
            </div>
            <div class="col-md-6">
                <h4>Cancel Booking Quote Detail</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'description',
                        'price:currency',
                        'platform_deductible:currency'
                        //'created_at:date',
                        // 'updated_at',


                    ],
                ]);

                ?>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
