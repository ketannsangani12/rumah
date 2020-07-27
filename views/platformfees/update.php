<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlatformFees */

$this->title = 'Update Platform Fees: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Platform Fees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="platform-fees-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
