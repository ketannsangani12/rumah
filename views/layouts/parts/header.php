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


echo "<div class='top-menu'>";
echo Nav::widget(
    [
    'items' => [

          [
              'label' => Yii::$app->user->identity->username,
              'items' => [
                  ['label' => 'Change Password', 'url' => '/site/changepassword'],
                   ['label' => 'Logout', 'url' => '/site/logout'],
//                   '<li class="divider"></li>',
//                   '<li class="dropdown-header">Dropdown Header</li>',
//                   ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
              ],
          ],
      ],

]);
NavBar::end();
?>
</div>
<?=
(Metronic::getComponent() && Metronic::getComponent()->layoutOption == Metronic::LAYOUT_BOXED) ?
    Html::beginTag('div', ['class' => 'container']) : '';
?>