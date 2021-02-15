<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Agreement';

?>
<div class="booking-requests-update">

    <?= $this->render('_formagreement', [
        'model' => $model,
    ]) ?>

</div>
