<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PlatformFees */

$this->title = 'Create Platform Fees';
$this->params['breadcrumbs'][] = ['label' => 'Platform Fees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="platform-fees-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
