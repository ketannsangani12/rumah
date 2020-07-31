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
        <h4>
            <?php
            $type = '';
            switch ($propertymodel->reftype){
                case "Cleaner";
                    $type='checkinphoto';
                    echo "Check-In Photos";
                    break;
                case "Handyman";
                    $type = 'useruploadedphotos';
                    echo "User Uploaded Photos";
                    break;
                case "Laundry";
                    $type = 'pickupphoto';
                    echo "Pickup Photos";
                    break;
            }
            ?>
        </h4>
        <?php
        //For Update Form : Fetch Uploaded Images and create Array to preview
        $imagesList = array();
        $imagesListId = array();
        foreach ($images as $img) {
            if($img->reftype == $type) {
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
                     'type' => $type,
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
        <br><br>
        <h4>
            <?php
            $type = '';
            switch ($propertymodel->reftype){
                case "Cleaner";
                    $type='checkoutphoto';
                    echo "Check-Out Photos";
                    break;

                case "Laundry";
                    $type = 'deliveryphoto';
                    echo "Delivery Photos";
                    break;
            }
            ?>
        </h4>
        <?php
        if($propertymodel->reftype=='Cleaner' || $propertymodel->reftype=='Laundry') {
            //For Update Form : Fetch Uploaded Images and create Array to preview
            $imagesList = array();
            $imagesListId = array();
            foreach ($images as $img) {
                if ($img->reftype == $type) {

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
                'options' => ['accept' => 'image/*', 'multiple' => true, 'id' => 'products_image_id1'
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
                        'type' => $type,
                        'is_post' => 'update'
                    ],

                    'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
                    'browseClass' => 'btn btn-primary col-lg-2 col-md-4 col-sm-8 col-xs-6',
                    'browseIcon' => '<i class="glyphicon glyphicon-plus-sign"></i> ',
                    'browseLabel' => 'Upload Image',
                    //'allowedFileExtensions' => ['jpg', 'png'],
                    'previewFileType' => ['jpg', 'png', 'jpeg'],
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
        }
        ?>

    </div>
    <div class="box-footer">

                <?php //Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>

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