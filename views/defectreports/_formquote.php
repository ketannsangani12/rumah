<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
        <div class="booking-requests-form box box-primary">
            <div class="box-header with-border">
                <h4>Upload Quote</h4>
            </div>

            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-md-10">
                        <?= $form->field($model, 'pay_from')->radioList(['Tenant' => 'Tenant', 'Landlord' => 'Landlord'])->label('Payment From'); ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <?= $form->field($model, 'quote')->fileInput() ?>

                    </div>
                </div>
            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
