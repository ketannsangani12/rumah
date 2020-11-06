<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'reference_no') ?>

    <?= $form->field($model, 'property_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'landlord_id') ?>

    <?php // echo $form->field($model, 'vendor_id') ?>

    <?php // echo $form->field($model, 'promo_code') ?>

    <?php // echo $form->field($model, 'request_id') ?>

    <?php // echo $form->field($model, 'renovation_quote_id') ?>

    <?php // echo $form->field($model, 'topup_id') ?>

    <?php // echo $form->field($model, 'withdrawal_id') ?>

    <?php // echo $form->field($model, 'package_id') ?>

    <?php // echo $form->field($model, 'todo_id') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'sst') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'coins') ?>

    <?php // echo $form->field($model, 'coins_savings') ?>

    <?php // echo $form->field($model, 'total_amount') ?>

    <?php // echo $form->field($model, 'olduserbalance') ?>

    <?php // echo $form->field($model, 'oldlandlordbalance') ?>

    <?php // echo $form->field($model, 'oldagentbalance') ?>

    <?php // echo $form->field($model, 'oldvendorbalance') ?>

    <?php // echo $form->field($model, 'newuserbalance') ?>

    <?php // echo $form->field($model, 'newlandlordbalance') ?>

    <?php // echo $form->field($model, 'newagentbalance') ?>

    <?php // echo $form->field($model, 'newvendorcbalance') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'reftype') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
