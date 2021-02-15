<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Upload Move Out Checklist';

?>
<div class="booking-requests-update">

    <?= $this->render('_formmoveout', [
        'model' => $model,
    ]) ?>

</div>
