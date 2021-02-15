<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = $type.' - '.$model->reference_no." ( ".$model->reftype." ) ";

?>
<div class="booking-requests-update">

    <?= $this->render('_formissueinvoice', [
        'model' => $model,
        'modelCustomer' => $modelCustomer,
        'modelsAddress' => $modelsAddress
    ]) ?>

</div>
