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
                <h4>Upload Move Out Checklist</h4>
            </div>

            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-md-10">
                        <?= $form->field($model, 'moveout')->fileInput() ?>
                        <?php
                        if($model->moveout_date!=''){
                            $model->moveout_date = date('d-m-Y',strtotime($model->moveout_date));
                        }
                        ?>
                        <?= $form->field($model, 'moveout_date')->widget(
                            \kartik\date\DatePicker::className(), [
                            // inline too, not bad
                            // modify template for custom rendering
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true
                            ]
                        ]);?>
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
