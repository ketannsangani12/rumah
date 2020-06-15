<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use dlds\metronic\helpers\Layout;
use dlds\metronic\Metronic;

$asset = Metronic::registerThemeAsset($this);
//echo "<pre>";print_r($asset);exit;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl($asset->sourcePath);
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

?>
<?php $this->beginPage() ?>
    <!--[if IE 8]> <html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
    <!--[if IE 9]> <html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
    <!--[if !IE]><!-->
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <!--<![endif]-->
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?php
    //print_r(Yii::$app->controller);exit;
    if ((Yii::$app->controller->id=='site' && Yii::$app->controller->action->id === 'index') || Yii::$app->controller->id=='') {
        $class = 'page-container-bg-solid';

    }else{
        $class = '';
    }?>
    <body <?= Layout::getHtmlOptions('body',['class'=>'page-header-fixed page-sidebar-closed-hide-logo '.$class.' page-content-white'],true) ?>>
    <?php $this->beginBody() ?>
    <div class="page-wrapper">
        <?php
        \dlds\metronic\widgets\NavBar::begin(
            [
                'brandLabel' => 'My Company',
                'brandLogoUrl' => Metronic::getAssetsUrl($this) . '/pages/img/logo.png',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => Layout::getHtmlOptions('header', false),
            ]
        );
        ?>
    <?= $this->render('parts/header.php', ['directoryAsset' => $directoryAsset]) ?>

    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <?= $this->render('parts/sidebar.php', ['directoryAsset' => $directoryAsset]) ?>

        <?= $this->render('parts/content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]) ?>
    </div>
    <?= $this->render('parts/footer.php', ['directoryAsset' => $directoryAsset]) ?>
    </div>
    <?php $this->endBody() ?>

    <?php
    $this->registerCssFile("/web/css/site.css");


    $this->registerJs('
$(document).ready(function()  {
            $("#role").change(function()  {
                var val = $("#role option:selected").val();
                if(val=="Cleaner" || val=="Mover"){
                    $("#companydetails").show();
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
