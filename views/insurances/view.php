<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'View Insurance - '.$model->property->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo "View Insurance"." - ".$model->property->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-8">
            <h4>Insurance Details</h4>
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
                        'label'=>'Landlord',

                        'value'=>function($model){
                            return (isset($model->landlord->full_name))?$model->landlord->full_name:'';
                        }
                    ],
                    [
                        'label'=>'Email',

                        'value'=>function($model){
                            return (isset($model->landlord->email))?$model->landlord->email:'';
                        }
                    ],
                    [
                        'label'=>'Contact No.',

                        'value'=>function($model){
                            return (isset($model->landlord->contact_no))?$model->landlord->contact_no:'';
                        }
                    ],
                    'remarks',
                    'status',
                    'created_at:datetime',

                ],
            ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>Insurance Quote Detail</h4>
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
                <h4>Insurance Documents</h4>
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
