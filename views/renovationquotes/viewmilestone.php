<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'View Milestone - '.$model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo $model->title." - ".$model->property->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
         <div class="col-md-6">
             <h4>Milestone Quote Detail</h4>
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
                <h4>Milestone Documents</h4>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider2,
                    //'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'description',
                        [
                            'attribute' => 'document',
                            'label' => 'Document',
                            'value' => function ($model) {
                                return Html::a('Download', Yii::$app->homeUrl.'uploads/tododocuments/'.$model->document);
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
