<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Milestone Documents - '.$model->property->title;

?>
<div class="booking-requests-update">

    <?= $this->render('_formuploadmilestonedocument', [
        'model' => $model,
        'modelCustomer' => $modelCustomer,
        'modelsAddress' => $modelsAddress
    ]) ?>

</div>
