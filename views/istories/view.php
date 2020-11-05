<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Istories */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Istories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">

<div class="istories-view box box-primary">
    <div class="box-header">

    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                //'id',
                'title',
                [
                    'attribute' => 'image',
                    'value' => function ($model) {
                        return Html::img(Yii::$app->homeUrl. $model->image,['width'=>'50','height'=>'50']);
                    },
                    'format' => 'raw',
                ],
                'link',
                'description:ntext',
                'created_at:datetime',
            ],
        ]) ?>
    </div>
    <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

</div>
</div>
    </div>