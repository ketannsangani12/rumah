<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ilifestyle */

$this->title = 'Update Ilifestyle: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Ilifestyles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ilifestyle-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
