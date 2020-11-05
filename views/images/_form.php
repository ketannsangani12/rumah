<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Images */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="images-form box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $this->title;?></h3>
    </div>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php //$form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <?php
        //For Update Form : Fetch Uploaded Images and create Array to preview
        $imagesList = array();
        $imagesListId = array();
        foreach ($images as $img) {
            $imagesList[] = \yii\helpers\Url::base(TRUE) . '/' . $img->image;
            $imagesListId[]['key'] = $img->id;
        }
        ?>

        <?php echo '<label class="control-label">Choose an Image file(.png, .jpg , .jpeg)</label>'; ?>

        <?=
         \kartik\file\FileInput::widget([
             'model' => $model,
             'attribute' => 'images[]',
             'name' => 'images[]',
             'options' => ['accept' => 'image/*','multiple' => true,'id' => 'products_image_id'
             ],
             'pluginOptions' => [
                 'initialPreview' => $imagesList,
                 'initialPreviewConfig' => $imagesListId,
                 'deleteUrl' => \yii\helpers\Url::to(['images/delete-image']),
                 'showCaption' => false,
                 'showRemove' => false,
                 'showUpload' => false,
                 'uploadUrl' => \yii\helpers\Url::to(['/images/upload']),
                 'uploadExtraData' => [
                     'property_id' => $propertymodel->id,
                     'is_post'=>'update'
                 ],

                 'allowedFileExtensions' => ['jpg', 'png','jpeg'],
                 'browseClass' => 'btn btn-primary col-lg-2 col-md-4 col-sm-8 col-xs-6',
                 'browseIcon' => '<i class="glyphicon glyphicon-plus-sign"></i> ',
                 'browseLabel' => 'Upload Image',
                 //'allowedFileExtensions' => ['jpg', 'png'],
                 'previewFileType' => ['jpg', 'png','jpeg'],
                 'initialPreviewAsData' => true,
                 'overwriteInitial' => false,
                 'msgUploadBegin' => Yii::t('app', 'Please wait, system is uploading the files'),
                 'msgUploadThreshold' => Yii::t('app', 'Please wait, system is uploading the files'),
                 'msgUploadEnd' => Yii::t('app', 'Done'),
                 'msgFilesTooMany' => 'Maximum 10 property Images are allowed to be uploaded.',
                 'dropZoneClickTitle' => '',
                 "uploadAsync" => true,
                 "browseOnZoneClick" => true,
                // "dropZoneTitle" => '<img src=' . $empty_image . ' />',
                 'fileActionSettings' => [
                     'showZoom' => true,
                     'showRemove' => true,
                     'showUpload' => false,
                 ],
                 'validateInitialCount' => true,
                 'maxFileCount' => 10
             ],
             'pluginEvents' => [
                 'filebatchselected' => 'function(event, files) {
              $(this).fileinput("upload");

              }',
                 /* 'uploadExtraData' => 'function() {
                   var out = {}, key, i = 0;
                   $(".kv-input:visible").each(function() {
                   $el = $(this);
                   key = $el.hasClass("kv-new") ? "new_" + i : "init_" + i;
                   out[key] = $el.val();
                   i++;
                   });

                   return out;
                   }', */
                 'filepredelete' => 'function(event, files) {
                //var abort = true;
                var index = uploaded_images.indexOf(files);
                if (index !== -1) uploaded_images.splice(index, 1);
                 console.log(uploaded_images);
                 $("#productsmaster-images_array").val(uploaded_images);
               //return abort;   
            }',
                 'fileuploaded' => 'function(event, data, previewId, index){
               //alert( data.response.initialPreviewConfig[0].key);
          uploaded_images.push(data.response.initialPreviewConfig[0].key);
            console.log(uploaded_images);
            $("#productsmaster-images_array").val(uploaded_images);
          }',
                 /* 'filepreupload' => 'function(event, data, previewId, index){
                   var form = data.form, files = data.files, extra = data.extra,
                   response = data.response, reader = data.reader;
                   console.log(data.jqXHR);
                   console.log("File pre upload triggered");
                   }', */
             ],
        ]);

//            $form->field($model, 'images')->widget(\kartik\file\FileInput::classname(), [
//            'options' => ['accept' => 'image/*','multiple' => true
//            ],
//            'pluginOptions' => [
//                'uploadUrl' => \yii\helpers\Url::to(['/images/create','id'=>$propertymodel->id]),
//                'uploadExtraData' => [
//                    'property_id' => 20
//                ],
//                'maxFileCount' => 10
//            ]
//        ]); ?>

        <?= $form->field($model, 'images_array')->hiddenInput()->label(false) ?>

    </div>
    <div class="box-footer">

                <?php //Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('Back', ['properties/index'], ['class' => 'btn btn-warning btn-flat']) ?>

    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<< JS
   // initialize array    
   var uploaded_images = [];  
JS;
$this->registerJs($script);
?>