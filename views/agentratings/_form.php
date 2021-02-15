<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AgentRatings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agent-ratings-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'request_id')->textInput() ?>

        <?= $form->field($model, 'property_id')->textInput() ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'agent_id')->textInput() ?>

        <?= $form->field($model, 'appearance')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'attitude')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'knowledge')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
