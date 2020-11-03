<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RenovationQuotes */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">

        <div class="renovation-quotes-form box box-primary">
            <div class="box-header with-border">
                <h3>Close Renovation Quote</h3>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">


                <?= $form->field($model, 'remarks')->textarea() ?>


            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>