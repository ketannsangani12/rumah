<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RenovationQuotes */

$this->title = 'Update Renovation Quotes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Renovation Quotes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="renovation-quotes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
