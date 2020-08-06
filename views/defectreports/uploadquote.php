<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Quotation - '.$model->property->title;

?>
<div class="booking-requests-update">

    <?= $this->render('_formquote', [
        'model' => $model,
    ]) ?>

</div>
