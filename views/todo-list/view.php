<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TodoList */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Todo Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-list-view box box-primary">
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
                'title',
                'request_id',
                'renovation_quote_id',
                'property_id',
                'user_id',
                'landlord_id',
                'vendor_id',
                'document',
                'reftype',
                'status',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
