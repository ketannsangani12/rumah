<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EmailTemplates */

$this->title = 'Create Email Templates';
$this->params['breadcrumbs'][] = ['label' => 'Email Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-templates-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
