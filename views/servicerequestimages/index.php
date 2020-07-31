<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ImagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service Request Images - '.$merchantmodel->reference_no;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <strong class="card-title"><?php echo $this->title;?></strong>

        <?= Html::a('Add Images', ['create','id'=>$merchantmodel->id], ['class' => 'btn btn-primary btn-flat pull-right']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                //'property_id',
                [
                    'attribute' => 'image',
                    'format' => 'html',
                    'value' => function ($data) {
                        return Html::img(Yii::getAlias('@web').'/uploads/servicerequestimages/'. $data['image'],
                            ['width' => '70px','height'=>'70px']);
                    },
                    'filter'=>false
                ],
                'created_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
