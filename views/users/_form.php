<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
<div class="users-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive ">
        <div class="row">
         <div class="col-md-8">
        <?= $form->field($model, 'role')->dropDownList(['Superadmin'=>'Superadmin','PE'=>'PE','FE'=>'FE','OE'=>'OE','Cleaner'=>'Cleaner','Mover'=>'Mover','Laundry'=>'Laundry','Handyman'=>'Handyman'], ['id'=>'role']); ?>

        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <div id="companydetails" style="<?php echo ($model->role=='Cleaner' || $model->role=='Mover' || $model->role=='Handyman' || $model->role=='Laundry')?"display: block;":"display: none;"?>">
            <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'company_address')->textarea() ?>
            <?= $form->field($model, 'company_state')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'bank_account_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'bank_account_no')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
        </div>
             <div id="location" style="<?php echo ($model->role=='Cleaner'  || $model->role=='Laundry')?"display: block;":"display: none;"?>">
                 <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>
                 <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>
             </div>
         </div>
</div>
        </div>
    <div class="box-footer">
        <div class="row">
        <div class="col-md-8">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
            </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    </div>