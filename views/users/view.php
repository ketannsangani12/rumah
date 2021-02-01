<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="properties-view box box-primary">
    <div class="box-header">
        <h1>User Details : <?= Html::encode($this->title) ?></h1>
    </div>
    <div class="box-body table-responsive">

<div class="row">
<div class="col-md-6">
    <div class="users-view">



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'username',
            'role',
            'full_name',
           // 'last_name',
            //'wallet_balance',
            'contact_no',
            'email:email',
            'company_name',
            'document_no',
            'company_address:ntext',
            'company_state',
            'bank_account_name',
            'bank_account_no',
            'bank_name',
            [
                'label' => 'Agent Card',
                'value' => function ($model) {
                    return ($model->image!='')?Html::img(Yii::$app->homeUrl. "uploads/users/".$model->image,['width'=>'50','height'=>'50']):'';
                },
                'format' => 'raw',
            ],
            //'image',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>
        <p>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

        </p>


    </div>
</div>
</div>
        </div>
    </div>