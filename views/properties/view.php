<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Properties */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="properties-view box box-primary">
    <div class="box-header">
       <h3>Property Details - <?php echo $model->title;?></h3>
    </div>
    <div class="box-body table-responsive">
        <div class="row">
            <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'Landlord Or Agent',

                    'value'=>function($model){
                        return (isset($model->user->full_name))?$model->user->full_name:'';
                    }
                ],
                ///'user_id',
                //'pe_userid',
                'title:ntext',
                'description:ntext',
                'location:ntext',
                'latitude',
                'longitude',
                'property_type',
                'room_type',
                'preference',
                'availability:date',

            ],
        ]) ?>
                </div>
            <div class="col-md-6">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'bedroom',
                        'bathroom',
                        'carparks',
                        'furnished_status',
                        'size_of_area',
                        'price:currency',
                        'amenities:ntext',
                        'commute',
                        [
                            'label'=>'Digital Tenancy',

                            'value'=>function($model){
                                return ($model->digital_tenancy==1)?"Yes":"No";
                            }
                        ],
                        [
                            'label'=>'Auto Rental',

                            'value'=>function($model){
                                return ($model->auto_rental==1)?"Yes":"No";
                            }
                        ],
                        [
                            'label'=>'Insurance',

                            'value'=>function($model){
                                return ($model->insurance==1)?"Yes":"No";
                            }
                        ],
                        'status',
                        //'created_at:datetime',
                        //'updated_at:datetime',
                    ],
                ]) ?>
            </div>
            <br>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>

        </div>
    </div>
</div>
