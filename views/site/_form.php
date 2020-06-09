<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Packages */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
        <div class="categories-form box box-primary">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">
                <?= $form->field($model, 'oldpassword')->passwordInput(['maxlength' => true]) ?>


                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

            </div>


            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-circle btn-info btn-flat']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


