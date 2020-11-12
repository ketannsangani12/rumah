<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-8">
        <div class="booking-requests-form box box-primary">
            <div class="box-header with-border">
                <h3>Upload PDF to MSC For Signing</h3>
            </div>

            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-md-8">
                    <?= $form->field($model, 'pdf')->fileInput() ?>
                    </div>
                </div>
                <div class="row">
                    <h4>For Tenant</h4>
                    <div class="col-md-3">
                        <?= $form->field($model, 'tenantx1')->textInput() ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'tenanty1')->textInput() ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'tenantx2')->textInput() ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'tenanty2')->textInput() ?>

                    </div>
                </div>
                <div class="row">
                    <h4>For Landlord</h4>
                    <div class="col-md-3">
                        <?= $form->field($model, 'landlordx1')->textInput() ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'landlordy1')->textInput() ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'landlordx2')->textInput() ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'landlordy2')->textInput() ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'tenantpageno')->textInput() ?>

                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'landlordpageno')->textInput() ?>

                    </div>

                </div>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
