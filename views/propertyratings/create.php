<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyRatings */

$this->title = 'Create Property Ratings';
$this->params['breadcrumbs'][] = ['label' => 'Property Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-ratings-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
