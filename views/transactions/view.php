<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'reference_no',
                'property_id',
                'user_id',
                'landlord_id',
                'vendor_id',
                'promo_code',
                'request_id',
                'renovation_quote_id',
                'topup_id',
                'withdrawal_id',
                'package_id',
                'todo_id',
                'amount',
                'sst',
                'discount',
                'coins',
                'coins_savings',
                'total_amount',
                'olduserbalance',
                'oldlandlordbalance',
                'oldagentbalance',
                'oldvendorbalance',
                'newuserbalance',
                'newlandlordbalance',
                'newagentbalance',
                'newvendorcbalance',
                'type',
                'reftype',
                'status',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
