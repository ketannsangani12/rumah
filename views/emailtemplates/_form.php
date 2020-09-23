<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EmailTemplates */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
<div class="email-templates-form box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $this->title;?></h3>
    </div>
    <?php $form = ActiveForm::begin();
    ?>
    <div class="box-body table-responsive">
        <?php if(!$model->isNewRecord) {
        echo $form->field($model, 'name')->textInput(['maxlength' => true,'disabled'=>'disabled']);
        }else{
            echo $form->field($model, 'name')->textInput(['maxlength' => true]);
        }?>



        <?= $form->field($model, 'subject')->textInput() ?>

        <?= $form->field($model, 'body')->widget(\marqu3s\summernote\Summernote::className(), [
            'clientOptions' => [

    ]
]); ?>



    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
    </div>
</div>