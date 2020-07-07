<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RenovationQuotes */

$this->title = 'Create Renovation Quotes';
$this->params['breadcrumbs'][] = ['label' => 'Renovation Quotes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="renovation-quotes-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
