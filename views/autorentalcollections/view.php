<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RenovationQuotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'View Auto Rental Details - '.$model->property->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">

    <div class="renovation-quotes-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <h3><?php echo "View Auto Rental "." - ".$model->property->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                <?= \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label'=>'Property No.',

                            'value'=>function($model){
                                return (isset($model->property->property_no))?$model->property->property_no:'';
                            }
                        ],
                        [
                            'label'=>'Property',

                            'value'=>function($model){
                                return (isset($model->property->title))?$model->property->title:'';
                            }
                        ],
                        [
                            'label'=>'Landlord Or Agent',

                            'value'=>function($model){
                                return (isset($model->landlord->full_name))?$model->landlord->full_name:'';
                            }
                        ],
                        [
                            'label'=>'Tenant',

                            'value'=>function($model){
                                return (isset($model->user->full_name))?$model->user->full_name:'';
                            }
                        ],
                        [
                            'label'=>'Monthly Rental',

                            'value'=>function($model){
                                return (isset($model->request->monthly_rental))?$model->request->monthly_rental:'';
                            }
                        ],
                        [
                            'label'=>'Month',

                            'value'=>function($model){
                                return date('M-Y',strtotime($model->rent_startdate));
                            }
                        ],
                        [
                            'label'=>'Status',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Yii::$app->common->getStatus($model->status);                            }
                        ],
                        //'image',
                        //'created_at',
                        //'updated_at',
                    ],
                ])  ?>
            </div>

        </div>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
    <?php Pjax::end(); ?>
</div>
</div>
    </div>