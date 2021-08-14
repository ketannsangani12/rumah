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
                        return ($model->full_name!='')?$model->full_name:$model->user->full_name;
                    }
                ],
                //'user_id',
                'type',
                'document_no',
                'reason',
                [
                    'attribute' => 'document',
                    'format'    => 'raw',
                    'value'     => function ( $model ) {
                        return $this->render( '_image', [
                            'src'   => 'data:image/jpeg;base64,' . $model->document,
                        ]);
                    }
                   // 'value' => ,
                    //'format' => ['image', ['width' => '600', 'height' => '500','style'=>'object-fit: cover;']],
                    //'options'=>[ 'style'=>'object-fit: cover;' ]
                ],
                [
                    'attribute' => 'selfie',
                    'format'    => 'raw',
                    'value'     => function ( $model ) {
                        return $this->render( '_image', [
                            'src'   => 'data:image/jpeg;base64,' . $model->selfie,
                        ]);
                    }
//                    'value' => 'data:image/jpeg;base64,' . $model->selfie,
//                    'format' => ['image', ['width' => '600', 'height' => '500','style'=>'object-fit: cover;']],

                ],
                [
                    'attribute' => 'document_back',
                    'format'    => 'raw',
                    'value'     => function ( $model ) {
                        return ($model->document_back!='')?$this->render( '_image', [
                            'src'   => 'data:image/jpeg;base64,' . $model->document_back,
                        ]):'';
                    },
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
