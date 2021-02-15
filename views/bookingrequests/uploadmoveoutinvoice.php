<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = ($type=='moveoutinvoice')?'Upload Move Out Checklist - '.$model->reference_no:'Cancel Booking - '.$model->reference_no;

?>
<div class="booking-requests-update">

    <?= $this->render('_formmoveoutinvoice', [
        'model' => $model,
        'modelCustomer' => $modelCustomer,
        'modelsAddress' => $modelsAddress
    ]) ?>

</div>
