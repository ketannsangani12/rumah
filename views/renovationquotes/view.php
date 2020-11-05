<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RenovationQuotes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Renovation Quotes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6">

<div class="renovation-quotes-view box box-primary">
    <div class="box-header">
       <h4>View Renovation Quote</h4>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
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
                //'quote_document',
                'status',
                'created_at:datetime',
                //'updated_at:datetime',
            ],
        ]) ?>
        <br>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
</div>
</div>
