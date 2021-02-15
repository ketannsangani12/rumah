<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VendorRatings */

$this->title = 'Create Vendor Ratings';
$this->params['breadcrumbs'][] = ['label' => 'Vendor Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-ratings-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
