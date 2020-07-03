<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementTemplates */

$this->title = 'Update Agreement Templates: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Agreement Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agreement-templates-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
