<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Istories */

$this->title = 'Create Istories';
$this->params['breadcrumbs'][] = ['label' => 'Istories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istories-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
