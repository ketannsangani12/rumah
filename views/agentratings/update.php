<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgentRatings */

$this->title = 'Update Agent Ratings: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agent Ratings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agent-ratings-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
