<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PromoCodes */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">

    <div class="promo-codes-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'promo_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList([ 'Fixed' => 'Fixed', 'Percentage' => 'Percentage', ], ['prompt' => '']) ?>

        <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'expiry_date')->widget(
            \kartik\date\DatePicker::className(), [
            // inline too, not bad
            // modify template for custom rendering
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ]); ?>


    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    </div>