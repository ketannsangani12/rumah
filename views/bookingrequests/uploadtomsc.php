<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload To MSC Trustgate';

?>
<div class="booking-requests-update">

    <?= $this->render('_formuploadtomsc', [
        'model' => $model,
    ]) ?>

</div>
