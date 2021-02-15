<?php
use yii\helpers\Html;
use dlds\metronic\helpers\Layout;
use dlds\metronic\widgets\Menu;
use dlds\metronic\widgets\NavBar;
use dlds\metronic\widgets\Nav;
use dlds\metronic\widgets\Breadcrumbs;
use dlds\metronic\widgets\Button;
use dlds\metronic\widgets\HorizontalMenu;
use dlds\metronic\Metronic;
use dlds\metronic\widgets\Badge;
?>
<div class="footer">
    <div class="footer-inner">
        <?= date('Y') ?> &copy; YiiMetronic.
    </div>
    <div class="footer-tools">
                <span class="go-top">
                    <i class="fa fa-angle-up"></i>
                </span>
    </div>
</div>
<?= (Metronic::getComponent() && Metronic::getComponent()->layoutOption == Metronic::LAYOUT_BOXED) ? Html::endTag('div') : ''; ?>