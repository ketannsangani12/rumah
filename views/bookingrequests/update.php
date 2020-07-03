<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Update Booking Requests: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Booking Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="booking-requests-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
