<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$now = date('d/m/Y');
if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <?php $this->head() ?>
        <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    </head>
    <body class="sidebar-mini skin-red-light nimbus-is-editor">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    <?php
    $this->registerCssFile("@web/web/css/site.css");
    $this->registerJs('
        $(document).ready(function()  {
            $("#role").change(function()  {
                var val = $("#role option:selected").val();
                if(val=="Cleaner" || val=="Mover" || val=="Laundry" || val=="Handyman"){
                    $("#companydetails").show();
                    if(val=="Cleaner" || val=="Laundry"){
                    $("#location").show();
                    }else{
                    $("#location").hide();
                    }
                }else{
                    $("#companydetails").hide();
                }
            });
           
        });
    ');
    ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
