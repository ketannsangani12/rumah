<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingRequests */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="row">
        <div class="col-md-8">
            <div class="booking-requests-form box box-primary">
                <div class="box-header with-border">
                    <h4>Add Renovation Milestone - <?php echo $model->property->title;?></h4>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
                <div class="box-body table-responsive">
                    <div class="row">
                        <div class="col-md-10">
                            <?= $form->field($modelCustomer, 'title')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <?php \wbraganca\dynamicform\DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $modelsAddress[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'description',
                            'price'
                        ],
                    ]); ?>

                    <div class="container-items"><!-- widgetContainer -->
                        <?php
                        $total = 0;
                        foreach ($modelsAddress as $i => $modelAddress): ?>
                            <div class="item panel panel-default"><!-- widgetBody -->
                                <div class="panel-heading">
                                    <h3 class="panel-title pull-left">Renovation Quote Items</h3>
                                    <div class="pull-right">
                                        <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                        <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    // necessary for update action.
                                    if (! $modelAddress->isNewRecord) {
                                            $total += $modelAddress->price;
                                        echo Html::activeHiddenInput($modelAddress, "[{$i}]id");

                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?= $form->field($modelAddress, "[{$i}]description")->textInput(['maxlength' => true]) ?>

                                        </div>
                                        <div class="col-sm-6">
                                            <?= $form->field($modelAddress, "[{$i}]price")->textInput(['maxlength' => true,'class'=>'form-control price']) ?>
                                        </div>


                                    </div><!-- .row -->
                                    <!-- .row -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php \wbraganca\dynamicform\DynamicFormWidget::end(); ?>
                    <p  style="font-weight: bold;">Total : <span id="total"><?php echo $total; ?></span></p>

                </div>
                <div class="box-footer">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-flat']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php

$this->registerJs('
        

$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Are you sure you want to delete this item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("afterDelete", function(e) {
var sum = 0;
    $(".price").each(function(){
        sum += +$(this).val();
    });
    $("#total").text(sum);
    console.log("Deleted item!");
});

$(document).on("change", ".price", function() {
    var sum = 0;
    $(".price").each(function(){
        sum += +$(this).val();
    });
    $("#total").text(sum);
});
    ');

?>