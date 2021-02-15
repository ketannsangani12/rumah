<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Properties */

$this->title = 'Create Properties';
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="properties-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
