<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ServiceRequests */

$this->title = 'Create Cleaning Order';
$this->params['breadcrumbs'][] = ['label' => 'Service Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-requests-create">

    <?= $this->render('_formcleaningorder', [
    'model' => $model,
    ]) ?>

</div>
