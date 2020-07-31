<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ServiceRequests */

$this->title = 'Create Service Requests';
$this->params['breadcrumbs'][] = ['label' => 'Service Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-requests-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
