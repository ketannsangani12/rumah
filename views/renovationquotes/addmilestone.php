<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */

$this->title = 'Add Milestone - '.$model->property->title;

?>
<div class="booking-requests-update">

    <?= $this->render('_formaddmilestone', [
        'model' => $model,
        'modelCustomer' => $modelCustomer,
        'modelsAddress' => $modelsAddress
    ]) ?>

</div>
