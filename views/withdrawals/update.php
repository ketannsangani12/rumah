<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Withdrawals */

$this->title = 'Update Withdrawal Status: ' . $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Withdrawals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-6">
<div class="withdrawals-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>