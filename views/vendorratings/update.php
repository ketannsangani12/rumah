<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VendorRatings */

$this->title = 'Update Vendor Ratings: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-ratings-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
