<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booking-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'property_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'landlord_id') ?>

    <?= $form->field($model, 'template_id') ?>

    <?php // echo $form->field($model, 'credit_score') ?>

    <?php // echo $form->field($model, 'booking_fees') ?>

    <?php // echo $form->field($model, 'tenancy_fees') ?>

    <?php // echo $form->field($model, 'stamp_duty') ?>

    <?php // echo $form->field($model, 'fees') ?>

    <?php // echo $form->field($model, 'sst') ?>

    <?php // echo $form->field($model, 'rental_deposit') ?>

    <?php // echo $form->field($model, 'utilities_deposit') ?>

    <?php // echo $form->field($model, 'commencement_date') ?>

    <?php // echo $form->field($model, 'tenancy_period') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'security_deposit') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
