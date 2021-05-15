<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ManualKyc */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Manual Kycs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manual-kyc-view box box-primary">
    <div class="box-header">

    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                [
                    'label'=>'Booking Request',

                    'value'=>function($model){
                        return (isset($model->request->reference_no))?$model->request->reference_no:'';
                    }
                ],
                [
                    'label'=>'User',

                    'value'=>function($model){
                        return (isset($model->user->full_name))?$model->user->full_name:'';
                    }
                ],
                //'user_id',
                'type',
                'document_no',
                'reason',
                [
                    'attribute' => 'document',
                    'value' => 'data:image/jpeg;base64,' . $model->document,
                    'format' => ['image', ['width' => '500', 'height' => '500']]
                ],
                [
                    'attribute' => 'selfie',
                    'value' => 'data:image/jpeg;base64,' . $model->selfie,
                    'format' => ['image', ['width' => '500', 'height' => '500']]
                ],
                [
                    'attribute' => 'document_back',
                    'value' => ($model->document_back!='')?'data:image/jpeg;base64,' . $model->document_back:'',
                    'format' => ($model->document_back!='')?['image', ['width' => '500', 'height' => '500']]:'',
                    'visible'=>($model->document_back!='')
                ],
                [
                    'attribute' => 'pdf',
                    'label' => 'PDF',
                    'value' => function ($model) {
                        return ($model->pdf!='')?Html::a('Print', Yii::$app->homeUrl.'uploads/creditscorereports/'.$model->pdf):'Not Uploaded';
                    },
                    'format' => 'raw',
                ],
                //'selfie:ntext',
                'status',
                'created_at:datetime',
                //'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
