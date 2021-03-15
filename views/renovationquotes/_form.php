<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RenovationQuotes */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">

<div class="renovation-quotes-form box box-primary">
    <div class="box-header with-border">
<h3>Add Renovation Quote</h3>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
<?php
$properties = \app\models\Properties::find()->asArray()->all();
//print_r($properties);exit;
if(!empty($properties)){
    foreach ($properties as $property){
        $data[$property['id']] = $property['property_no']." - ".$property['title'];
    }
}
?>
        <?= $form->field($model, 'property_id')->widget(\kartik\select2\Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => 'Select a Property ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>


        <?= $form->field($model, 'document')->fileInput(['maxlength' => true]) ?>


    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    </div>