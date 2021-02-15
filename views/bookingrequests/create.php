<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Create Booking Requests';
$this->params['breadcrumbs'][] = ['label' => 'Booking Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-requests-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
