<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AgreementTemplates */

$this->title = 'Create Agreement Templates';
$this->params['breadcrumbs'][] = ['label' => 'Agreement Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-templates-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
