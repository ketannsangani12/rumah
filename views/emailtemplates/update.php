<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EmailTemplates */

$this->title = 'Update Email Templates: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Email Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="email-templates-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
