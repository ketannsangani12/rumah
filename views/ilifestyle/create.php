<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ilifestyle */

$this->title = 'Create Ilifestyle';
$this->params['breadcrumbs'][] = ['label' => 'Ilifestyles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ilifestyle-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
