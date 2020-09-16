<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Properties */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
<div class="properties-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-6">
        <?= $form->field($model, 'title')->textInput(['rows' => 6]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'location')->textInput();
            //->widget(\fv\yii\geocomplete\Widget::class); ?>
        <?= $form->field($model, 'latitude')->textInput(); ?>
        <?= $form->field($model, 'longitude')->textInput(); ?>


        <?= $form->field($model, 'property_type')->dropDownList(Yii::$app->common->propertytype()) ?>

        <?= $form->field($model, 'room_type')->dropDownList(Yii::$app->common->roomtype()) ?>

        <?= $form->field($model, 'preference')->dropDownList(Yii::$app->common->preference()) ?>

        <?= $form->field($model, 'bedroom')->textInput([
            'type' => 'number',
            'min'=>0
        ]) ?>

        <?= $form->field($model, 'bathroom')->textInput([
            'type' => 'number',
            'min'=>0
        ]) ?>

        <?= $form->field($model, 'carparks')->textInput([
            'type' => 'number',
            'min'=>0
        ]) ?>


            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'type')->dropDownList(['Private' => 'Private','Shared'=>'Shared']) ?>

                <?= $form->field($model, 'furnished_status')->dropDownList(['Unfurnished' => 'Unfurnished','Furnished'=>'Furnished','Semi Furnished'=>'Semi Furnished']) ?>

                <?= $form->field($model, 'availability')->widget(DatePicker::className(),[
                    'options' => ['placeholder' => 'Select availablility date'],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true
                    ]
                ]) ?>


                <?= $form->field($model, 'size_of_area')->textInput(['placeholder'=>'Sq. Ft.']) ?>

                <?= $form->field($model, 'price')->textInput(['placeholder'=>'RM']) ?>

                <?= $form->field($model, 'amenities')->checkboxList(Yii::$app->common->amenities()) ?>

                <?= $form->field($model, 'commute')->checkboxList(Yii::$app->common->commute()) ?>
                <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive', 'Rented' => 'Rented', 'Suspended' => 'Suspended', 'Deleted' => 'Deleted', ]) ?>


                <?= $form->field($model, 'digital_tenancy')->checkbox() ?>

                <?= $form->field($model, 'auto_rental')->checkbox() ?>

                <?= $form->field($model, 'insurance')->checkbox() ?>


            </div>
            </div>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
    </div>
</div>

