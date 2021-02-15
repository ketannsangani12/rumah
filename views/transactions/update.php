<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */

$this->title = 'Update Transactions: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transactions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
