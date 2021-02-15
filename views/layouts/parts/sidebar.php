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
<?=
Menu::widget(
    [
        'visible' => true,
        'items' => [
            // Important: you need to specify url as 'controller/action',
            // not just as 'controller' even if default action is used.
            ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['site/index']],
            // 'Products' menu item will be selected as long as the route is 'product/index'
//            [
//                'icon' => 'fa fa-cogs',
//                'badge' => Badge::widget(['label' => 'New', 'round' => false, 'type' => Badge::TYPE_SUCCESS]),
//                'label' => 'Products',
//                'url' => '#',
//                'items' => [
//                    ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
//                    [
//                        'label' => 'Home',
//                        'url' => '#',
//                        'items' => [
//                            [
//                                'icon' => 'fa fa-cogs',
//                                'label' => 'Products',
//                                'url' => ['site/jk'],
//                                'badge' => Badge::widget(
//                                    ['label' => 'New', 'round' => false, 'type' => Badge::TYPE_SUCCESS]
//                                ),
//                            ],
//                        ]
//                    ],
//                ]
//            ],
//            [
//                'icon' => 'fa fa-bookmark-o',
//                'label' => 'UI Features',
//                'url' => '#',
//                'items' => [
//                    [
//                        'label' => 'Buttons & Icons',
//                        'url' => ['site/'],
//                    ],
//                ],
//            ],
            [
                'icon' => 'fa fa-users',
                'label' => 'Users',
                'url' => ['users/index'],
                //'visible' => Yii::$app->user->isGuest
            ]
        ],
    ]
);
?>