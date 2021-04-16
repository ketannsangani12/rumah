<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Booking Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-requests-view box box-primary">
    <div class="box-header">

    </div>
    <div class="box-body table-responsive">
        <div class="row">
        <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                'reference_no',
                [
                    'label'=>'Property',

                    'value'=>function($model){
                        return (isset($model->property->title))?$model->property->title:'';
                    }
                ],
                [
                    'label'=>'Property Address',

                    'value'=>function($model){
                        return (isset($model->property->location))?$model->property->location:'';
                    }
                ],
                [
                    'label'=>'Tenant',

                    'value'=>function($model){
                        return (isset($model->user->full_name))?$model->user->full_name:'';
                    }
                ],
                [
                    'label'=>'Landlord Or Agent',

                    'value'=>function($model){
                        return (isset($model->landlord->full_name))?$model->landlord->full_name:'';
                    }
                ],
                [
                    'label'=>'Landlord Mobile No.',

                    'value'=>function($model){
                        return (isset($model->landlord->contact_no))?$model->landlord->contact_no:'';
                    }
                ],
                [
                    'label'=>'Tenant Mobile No.',

                    'value'=>function($model){
                        return (isset($model->user->contact_no))?$model->user->contact_no:'';
                    }
                ],
                [
                    'label'=>'Landlord Email',

                    'value'=>function($model){
                        return (isset($model->landlord->email))?$model->landlord->email:'';
                    }
                ],
                [
                    'label'=>'Tenant Email',

                    'value'=>function($model){
                        return (isset($model->user->email))?$model->user->email:'';
                    }
                ],
                [
                    'label'=>'Agreement Template',

                    'value'=>function($model){
                        return (isset($model->template->name))?$model->template->name:'';
                    }
                ],
                [
                    'label'=>'Tenant Identification',

                    'value'=>function($model){
                        return (isset($model->user->document_no))?$model->user->document_no:'';
                    }
                ],
                [
                    'label'=>'Landlord Or Agent Identification',

                    'value'=>function($model){
                        return (isset($model->landlord->document_no))?$model->landlord->document_no:'';
                    }
                ],
                //'property_id',
                //'user_id',
                //'landlord_id',
                //'template_id',
                'commencement_date:date',
                [
                    'label' => 'Tenancy End Date',
                    'value' => function ($model) {
                        $months = $model->tenancy_period;
                        $effectiveDate = date('Y-m-d', strtotime("+".$months." months", strtotime($model->commencement_date)));
                        return date('d-m-Y', strtotime("+".$months." months", strtotime($effectiveDate)));

                    },

                ],
                'tenancy_period',
                [
                    'attribute' => 'status',
                    'label' => 'Status',
                    'value' => function ($model) {
                        return $model->getStatus($model->status);
                    },
                    'format' => 'raw',
                ],
                //'status',
                'moveout_date:date',
               // 'created_at:datetime',
                //'updated_at:datetime',
            ],
        ]) ?>
            </div>
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'id',

                    'booking_fees:currency',
                    'tenancy_fees:currency',
                    'stamp_duty:currency',
                    'keycard_deposit:currency',
                    'rental_deposit:currency',
                    'utilities_deposit:currency',
                    'security_deposit:currency',
                    'sst:currency',
                    'subtotal:currency',
                    'total:currency',
                    [
                        'label' => 'Agreement document(Non - Stamped)',
                        'value' => function ($model) {
                            return ($model->document_content!='')?Html::a('Print', \yii\helpers\Url::to([Yii::$app->controller->id.'/printagreement', 'id' => $model->id])):'Not Uploaded';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'agreement_document',
                        'label' => 'Agreement document(Signed)',
                        'value' => function ($model) {
                            return ($model->signed_agreement_document!='')?Html::a('Print', Yii::$app->homeUrl.$model->signed_agreement_document):'Not Uploaded';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'stampduty_certificate',
                        'label' => 'Stamp Duty Certificate',
                        'value' => function ($model) {
                            return ($model->stampduty_certificate!='')?Html::a('Print', Yii::$app->homeUrl.'uploads/agreements/'.$model->stampduty_certificate):'Not Uploaded';
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
            </div>
        </div>
        <div class="row">
            <h4>Documents</h4>
            <div class="col-md-6">
                <?php

                ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => 'Credit Score Reports',
                            'value' => function ($model,$documentstenants) {
                                return (!empty($model) && isset($model->credit_score_report) && $model->credit_score_report!='')?Html::a('Download', Yii::$app->homeUrl.'uploads/creditscorereports/'.$model->credit_score_report):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Tenant Ekyc Document',
                            'value' => function ($model,$documentstenants) {
                                $documentstenants = \app\models\UsersDocuments::find()->where(['user_id'=>$model->user_id,'request_id'=>$model->id])->one();

                                return (!empty($documentstenants) && isset($documentstenants->ekyc_document))?Html::a('Download', Yii::$app->homeUrl.'uploads/user_documents/'.$documentstenants->ekyc_document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Tenant Supporting Document',
                            'value' => function ($model,$documentstenants) {
                                $documentstenants = \app\models\UsersDocuments::find()->where(['user_id'=>$model->user_id,'request_id'=>$model->id])->one();

                                return (!empty($documentstenants) && isset($documentstenants->supporting_document))?Html::a('Download', Yii::$app->homeUrl.'uploads/user_documents/'.$documentstenants->supporting_document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Landlord Ekyc Document',
                            'value' => function ($model,$documentslandlord) {
                                $documentslandlord = \app\models\UsersDocuments::find()->where(['user_id'=>$model->landlord_id,'request_id'=>$model->id])->one();

                                return (!empty($documentslandlord) && isset($documentslandlord->ekyc_document))?Html::a('Download', Yii::$app->homeUrl.'uploads/user_documents/'.$documentslandlord->ekyc_document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Landlord SPA',
                            'value' => function ($model,$documentslandlord) {
                                $documentslandlord = \app\models\UsersDocuments::find()->where(['user_id'=>$model->landlord_id,'request_id'=>$model->id])->one();

                                return (!empty($documentslandlord) && isset($documentslandlord->supporting_document))?Html::a('Download', Yii::$app->homeUrl.'uploads/user_documents/'.$documentslandlord->supporting_document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Move In Checklist',
                            'value' => function ($model) {
                                return (!empty($model) && $model->movein_document!='')?Html::a('Download', Yii::$app->homeUrl.'uploads/moveinout/'.$model->movein_document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Move Out Checklist',
                            'value' => function ($model) {
                                return (!empty($model) && $model->moveout_document!='')?Html::a('Download', Yii::$app->homeUrl.'uploads/moveinout/'.$model->moveout_document):'Not Uploaded';
                            },
                            'format' => 'raw',
                        ],

                    ],
                ]) ?>
                </div>
            <div class="col-md-6">

            </div>
            </div>
        <br>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
</div>
