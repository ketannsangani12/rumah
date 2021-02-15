<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Istories */

$this->title = 'Update Istories: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Istories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="istories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
