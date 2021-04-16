<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Stamp Duty Certificate';

?>
<div class="booking-requests-update">

    <?= $this->render('_formagreement', [
        'model' => $model,
    ]) ?>

</div>
