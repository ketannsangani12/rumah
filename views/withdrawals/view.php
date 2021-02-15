<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Withdrawals */

$this->title = 'Withdrawal Details';
$this->params['breadcrumbs'][] = ['label' => 'Withdrawals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">
<div class="withdrawals-view box box-primary">
    <div class="box-header">
        <h3><?= Html::encode($this->title) ?></h3>

    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'User',

                    'value'=>function($model){
                        return (isset($model->user->full_name))?$model->user->full_name:'';
                    }
                ],
                [
                    'label'=>'Bank',

                    'value'=>function($model){
                        return (isset($model->user->bank_account_name))?$model->user->bank_account_name:'';
                    }
                ],
                [
                    'label'=>'Account No.',

                    'value'=>function($model){
                        return (isset($model->user->bank_account_no))?$model->user->bank_account_no:'';
                    }
                ],
                'reference_no',
                'total_amount',
                'status',
                'created_at:datetime',
                'updated_at:datetime',
                [
                    'label'=>'Last Updated By',

                    'value'=>function($model){
                        return (isset($model->updatedby->full_name))?$model->updatedby->full_name:'';
                    }
                ],
            ],
        ]) ?>
        <br>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
</div>
