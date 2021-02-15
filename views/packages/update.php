<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Packages */

$this->title = 'Update Packages: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="packages-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
