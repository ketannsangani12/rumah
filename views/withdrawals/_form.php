<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Withdrawals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="withdrawals-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'status')->dropDownList([ 'Completed' => 'Approved', 'Declined' => 'Declined', ], ['prompt' => '']) ?>
        <?= $form->field($model, 'remarks')->textarea() ?>
        <?= $form->field($model, 'proof')->fileInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>
