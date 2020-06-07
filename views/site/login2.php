<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use dlds\metronic\helpers\Layout;
use dlds\metronic\Metronic;

?>
<div class="logo">
    <a href="#">
        <img src="<?php echo Metronic::getAssetsUrl($this) . '/pages/img/logo-big-white.png' ?>" style="height: 17px;" alt=""> </a>
</div>
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <?php $form = \dlds\metronic\widgets\ActiveForm::begin([
            'class' => 'login-form',
            //'layout' => 'horizontal',

        ]); ?>

            <div class="form-title">
                <span class="form-title">Welcome.</span>
                <span class="form-subtitle">Please login.</span>
            </div>
            <div class="alert alert-danger display-hide" style="display: none;">
                <button class="close" data-close="alert"></button>
                <span> Enter any username and password. </span>
            </div>
        <?= $form->field($model, 'username')->textInput(['placeholder'=>'Username','autofocus' => true])->label(false); ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Password'])->label(false) ?>

        <div class="form-actions">
                <button type="submit" class="btn red btn-block uppercase">Login</button>
            </div>
            <div class="form-actions">
                <div class="pull-left">
                    <label class="rememberme mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="remember" value="1"> Remember me
                        <span></span>
                    </label>
                </div>

            </div>
        <?php \dlds\metronic\widgets\ActiveForm::end(); ?>
        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->

        <!-- END REGISTRATION FORM -->
    </div>
