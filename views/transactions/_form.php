<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'reference_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'property_id')->textInput() ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'landlord_id')->textInput() ?>

        <?= $form->field($model, 'promo_code')->textInput() ?>

        <?= $form->field($model, 'request_id')->textInput() ?>

        <?= $form->field($model, 'amount')->textInput() ?>

        <?= $form->field($model, 'discount')->textInput() ?>

        <?= $form->field($model, 'coins')->textInput() ?>

        <?= $form->field($model, 'total_amount')->textInput() ?>

        <?= $form->field($model, 'olduserbalance')->textInput() ?>

        <?= $form->field($model, 'oldlandlordbalance')->textInput() ?>

        <?= $form->field($model, 'oldvendorbalance')->textInput() ?>

        <?= $form->field($model, 'newuserbalance')->textInput() ?>

        <?= $form->field($model, 'newlandlordbalance')->textInput() ?>

        <?= $form->field($model, 'newvendorcbalance')->textInput() ?>

        <?= $form->field($model, 'reftype')->dropDownList([ 'Monthly Rental' => 'Monthly Rental', 'Booking Payment' => 'Booking Payment', 'Moveout Refund' => 'Moveout Refund', 'Renovation Payment' => 'Renovation Payment', 'Insurance' => 'Insurance', 'Defect Report' => 'Defect Report', 'Cancellation Refund' => 'Cancellation Refund', 'Service' => 'Service', 'Other' => 'Other', 'Agent Commision' => 'Agent Commision', 'Topup' => 'Topup', 'Withdrawal' => 'Withdrawal', 'General' => 'General', 'Package Purchase' => 'Package Purchase', ], ['prompt' => '']) ?>

        <?= $form->field($model, 'status')->dropDownList([ 'Pending' => 'Pending', 'Completed' => 'Completed', 'Failed' => 'Failed', 'Declined' => 'Declined', ], ['prompt' => '']) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
