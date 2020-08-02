<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PromoCodes */

$this->title = "Promo Code Detail - ".$model->promo_code;
$this->params['breadcrumbs'][] = ['label' => 'Promo Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-6">
<div class="promo-codes-view box box-primary">
    <div class="box-header">
<h3><?php echo  $this->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                'promo_code',
                'type',
                'discount',
                'expiry_date',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
</div>
    </div>