<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AgentRatings */

$this->title = 'Create Agent Ratings';
$this->params['breadcrumbs'][] = ['label' => 'Agent Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-ratings-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
