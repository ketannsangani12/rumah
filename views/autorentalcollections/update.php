<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Auto Rental Collection';

?>
<div class="booking-requests-update">

    <?= $this->render('_form', [
        //'model' => $model,
        'model' => $model,
       // 'modelsAddress' => $modelsAddress
    ]) ?>

</div>
