<?php
use yii\helpers\Html;
use dlds\metronic\helpers\Layout;
use dlds\metronic\Metronic;

$asset = Metronic::registerThemeAsset($this);
//echo "<pre>";print_r($asset);exit;
$directoryAsset = Yii::$app->assetManager->getPublishedUrl($asset->sourcePath);
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
    <body class="login">
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>