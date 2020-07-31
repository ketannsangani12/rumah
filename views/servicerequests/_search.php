<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceRequestsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-requests-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'property_id') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'todo_id') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'hours') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'pickup_location') ?>

    <?php // echo $form->field($model, 'dropoff_location') ?>

    <?php // echo $form->field($model, 'truck_size') ?>

    <?php // echo $form->field($model, 'document') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'sst') ?>

    <?php // echo $form->field($model, 'total_amount') ?>

    <?php // echo $form->field($model, 'reftype') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'booked_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
