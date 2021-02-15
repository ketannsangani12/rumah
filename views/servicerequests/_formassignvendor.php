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

            <?php
            $properties = \yii\helpers\ArrayHelper::map(\app\models\Users::find()->where(['role'=>$propertymodel->reftype,'current_status'=>'Free'])->asArray()->all(),'id', 'full_name');

            if(!empty($properties)){
                foreach ($properties as $property){
                    $data[] = $property;
                }
            }else{
                $data = array();
            }
            $propertymodel->vendor_id = null;
            ?>

            <?= $form->field($propertymodel, 'vendor_id')->widget(\kartik\select2\Select2::classname(), [
                'data' => $properties,
                'options' => ['placeholder' => 'Select a Vendor ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>


        </div>
        <div class="box-footer">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>