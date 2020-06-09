<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Packages */

$this->title = 'Change Password';
//$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packages-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
