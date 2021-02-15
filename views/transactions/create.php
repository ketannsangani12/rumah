<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Transactions */

$this->title = 'Add Topup';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
