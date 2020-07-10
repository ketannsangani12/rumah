<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Insurance Covernote';

?>
<div class="booking-requests-update">

    <?= $this->render('_formuploadcovernote', [
        //'model' => $model,
        'model' => $model,
        'modeldocument'=>$modeldocument
    ]) ?>

</div>
