<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Images */

$this->title = ($reassign)?'Reassign':'Assign' .'Vendor Service Request : ' . $merchantmodel->reference_no." ( ".$merchantmodel->reftype." )" ;
?>
<div class="images-update">

    <?= $this->render('_formassignvendor', [
        //'model' => $model,
        'propertymodel'=>$merchantmodel,
        'reassign' => $reassign
    ]) ?>

</div>
