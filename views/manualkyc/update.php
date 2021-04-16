<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ManualKyc */

$this->title = 'Update Manual Kyc: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Manual Kycs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="manual-kyc-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
