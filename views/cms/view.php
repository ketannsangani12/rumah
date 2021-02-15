<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cms */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Cms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-view box box-primary">
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
                //'id',
                'title',
                'content:html',
                [
                    'attribute'=>'image',
                    'value'=> ($model->image!='')?Yii::$app->homeUrl.'uploads/banners/'.$model->image:'',
                    'format' => ['image',['width'=>'100','height'=>'100']],
                    'visible' => $model->image != '',

                ],
                'created_at:datetime',
                //'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
