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
<div class="row">
<div class="col-md-6">
    <div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    </p>

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
            'registration_no',
            'company_address:ntext',
            'company_state',
            'bank_account_name',
            'bank_account_no',
            'bank_name',
            //'image',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

</div>
</div>
</div>