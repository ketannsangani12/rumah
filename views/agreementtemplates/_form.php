<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use phpnt\summernote\SummernoteWidget;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementTemplates */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-11">
<div class="agreement-templates-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'document')->widget(SummernoteWidget::class,[

            'i18n' => true,             // переводить на другие языки
            'codemirror' => true,       // использовать CodeMirror (оформленный редактор кода)
            'emoji' => false,             // включить эмоджи
            'widgetOptions' => [
                /* Настройка панели */
                'placeholder' => Yii::t('app', 'Enter Content of Template'),
                'height' => 800,
                'tabsize' => 2,
                'minHeight' => 800,
                'maxHeight' => 800,
                'focus' => true,
                /* Панель управления */
                'toolbar' => [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['paragraph']],
                    ['height', ['height']],
                    ['misc', ['codeview']],
                ],
            ],
        ]); ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
    </div>