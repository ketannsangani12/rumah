<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
<div class="transactions-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?php
        $properties = \app\models\Users::find()->where(['in','role',['User']])->asArray()->all();
        //print_r($properties);exit;
        if(!empty($properties)){
            foreach ($properties as $property){
                $data1[$property['id']] = $property['full_name'];
            }
        }else{
            $data1 = array();
        }
        ?>
        <?= $form->field($model, 'user_id')->widget(\kartik\select2\Select2::classname(), [
            'data' => $data1,
            'options' => ['placeholder' => 'Select a User ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>


        <?= $form->field($model, 'amount')->textInput() ?>


    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>