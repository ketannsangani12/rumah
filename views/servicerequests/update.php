<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Images */

$this->title = 'Update Status Service Request : ' . $merchantmodel->reference_no." ( ".$merchantmodel->reftype." )" ;
?>
<div class="images-update">

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
        'propertymodel'=>$merchantmodel
    ]) ?>

</div>
