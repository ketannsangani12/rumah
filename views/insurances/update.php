<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Update Insurance Quote';

?>
<div class="booking-requests-update">

    <?= $this->render('_form', [
        //'model' => $model,
        'modelCustomer' => $modelCustomer,
        'modelsAddress' => $modelsAddress
    ]) ?>

</div>
