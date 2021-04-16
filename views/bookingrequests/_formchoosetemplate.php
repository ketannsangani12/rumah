<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-11">
        <div class="booking-requests-form box box-primary">
            <div class="box-header with-border">
                <h4>Choose Agreement Template</h4>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="request_id" value="<?php echo $model->id;?>">
                        <?= $form->field($model, 'template_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\AgreementTemplates::find()->all(),'id','name'),['prompt'=>'Select Template','id'=>'template']); ?>
                        <?= $form->field($model, 'document_content')->widget(\phpnt\summernote\SummernoteWidget::class,[
    'options' => [
                    'id' => 'summernote'
                ],
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
                        <?= $form->field($model, 'stamp_duty')->textInput(); ?>
                        <div class="row">
                            <h4>For Tenant</h4>
                            <div class="col-md-3">
                                <?= $form->field($model, 'tenantx1')->textInput() ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'tenanty1')->textInput() ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'tenantx2')->textInput() ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'tenanty2')->textInput() ?>

                            </div>
                        </div>
                        <div class="row">
                            <h4>For Landlord</h4>
                            <div class="col-md-3">
                                <?= $form->field($model, 'landlordx1')->textInput() ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'landlordy1')->textInput() ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'landlordx2')->textInput() ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'landlordy2')->textInput() ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'tenantpageno')->textInput() ?>

                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'landlordpageno')->textInput() ?>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="box-footer">
                <?= Html::a('Print', \yii\helpers\Url::to([Yii::$app->controller->id.'/printagreement', 'id' => $model->id]),['class' => 'btn bg-orange btn-flat']) ?>

                <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>

                <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php

$this->registerJs('
        $(document).ready(function()  {
            $("#template").change(function()  {
                var val = $("#template option:selected").val();
                var request_id = $("#request_id").val();
               $.ajax({
                url:"'.\yii\helpers\Url::to(['bookingrequests/content']).'",
                data:{"template":val,"request_id":request_id},
                method:"POST",
                success: function(result){
                var json = $.parseJSON(result);
                    $("#summernote").summernote("code", json.content);
                }
            });
            });
           
        });
    ');

?>