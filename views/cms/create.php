<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cms */

$this->title = 'About Us';
$this->params['breadcrumbs'][] = ['label' => 'Cms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
