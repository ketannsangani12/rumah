<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Quotation - '.$model->reference_no. " ( ".$model->reftype. " ) ";

?>
<div class="booking-requests-update">

    <?= $this->render('_formquote', [
        'model' => $model,
    ]) ?>

</div>
