<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Withdrawals */

$this->title = 'Create Withdrawals';
$this->params['breadcrumbs'][] = ['label' => 'Withdrawals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="withdrawals-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
