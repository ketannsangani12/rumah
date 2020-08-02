<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyRatings */

$this->title = 'Update Property Ratings: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Property Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-ratings-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
