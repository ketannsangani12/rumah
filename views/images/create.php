<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Images */

$this->title = 'Upload Images - '.$merchantmodel->title;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

<div class="images-create">

    <?= $this->render('_form', [
    'model' => $model,
    'images' => $images,
     'propertymodel'=>$merchantmodel
    ]) ?>

</div>
</div>
    </div>