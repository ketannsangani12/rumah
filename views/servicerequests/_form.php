<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServiceRequests */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-requests-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">


        <?= $form->field($propertymodel, 'status')->dropDownList([ 'Completed' => 'Completed',  'Cancelled' => 'Cancelled',  ], ['prompt' => 'Select Status']) ?>
        <h4>
            <?php
            $type = 'OE Uploaded Photos';
            $type1 = 'oeuploadedphotos';
            echo $type;
            ?>
        </h4>
        <?php
        //For Update Form : Fetch Uploaded Images and create Array to preview
        $imagesList = array();
        $imagesListId = array();
        foreach ($images as $img) {
            if($img->reftype == $type1) {
                $imagesList[] = \yii\helpers\Url::base(TRUE) . '/' . $img->image;
                $imagesListId[]['key'] = $img->id;
            }
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
                'deleteUrl' => \yii\helpers\Url::to(['servicerequestimages/delete-image']),
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'uploadUrl' => \yii\helpers\Url::to(['/servicerequestimages/upload']),
                'uploadExtraData' => [
                    'property_id' => $propertymodel->id,
                    'type' => $type1,
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
                    'showRemove' => false,
                    'showUpload' => false,
                ],
                'validateInitialCount' => true,
                'maxFileCount' => 10
            ],
            'pluginEvents' => [
                'filebatchselected' => 'function(event, files) {
              $(this).fileinput("upload");

              }',
                'filepredelete' => 'function(event, files) {
                //var abort = true;
                var index = uploaded_images.indexOf(files);
                if (index !== -1) uploaded_images.splice(index, 1);
                 $("#productsmaster-images_array").val(uploaded_images);
               //return abort;   
            }',
                'fileuploaded' => 'function(event, data, previewId, index){
               //alert( data.response.initialPreviewConfig[0].key);
          uploaded_images.push(data.response.initialPreviewConfig[0].key);
            
            $("#productsmaster-images_array").val(uploaded_images);
          }',

            ],
        ]);
        ?>

        <?= $form->field($model, 'images_array')->hiddenInput()->label(false) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

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