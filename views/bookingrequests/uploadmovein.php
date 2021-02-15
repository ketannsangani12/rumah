<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Move In';

?>
<div class="booking-requests-update">

    <?= $this->render('_formmovein', [
        'model' => $model,
    ]) ?>

</div>
