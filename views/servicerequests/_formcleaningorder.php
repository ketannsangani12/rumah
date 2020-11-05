<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceRequests */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">

        <div class="service-requests-form box box-primary">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">
                <h3>Create Cleaning Order</h3>
                <br>
                <br>
                <?php
                $properties = \app\models\Properties::find()->asArray()->all();
                //print_r($properties);exit;
                if(!empty($properties)){
                    foreach ($properties as $property){
                        $data1[$property['id']] = $property['property_no']." - ".$property['title'];
                    }
                }else{
                    $data1 = array();
                }
                ?>

                <?= $form->field($model, 'property_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => $data1,
                    'options' => ['placeholder' => 'Select a Propety ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <div class="row">
                    <div class="col-md-10">
                        <?= $form->field($model, 'request_to')->radioList(['Tenant' => 'Tenant', 'Landlord' => 'Landlord'])->label('Payment From'); ?>

                    </div>
                </div>
                <?php
                $vendors = \app\models\Users::find()->where(['role'=>'Cleaner','current_status'=>'Free'])->asArray()->all();
                //print_r($properties);exit;
                if(!empty($vendors)){
                    foreach ($vendors as $property){
                        $data[$property['id']] = $property['full_name'];
                    }
                }else{
                    $data = array();
                }
                ?>

                <?= $form->field($model, 'vendor_id')->widget(\kartik\select2\Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => 'Select a Vendor ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field($model, 'date')->widget(
                    \kartik\date\DatePicker::className(), [
                    // inline too, not bad
                    // modify template for custom rendering
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ]); ?>
                <?= $form->field($model, 'time')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'hours')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'addons')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>