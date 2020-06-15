<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="display: none;">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/home-yellow.png" class="img-circle" alt="User Image"/>
            </div>
            <?php
            $userdetails = app\models\Users::find()
                ->where('id = :userid', [':userid' => Yii::$app->user->id])
                ->one();
            ?>
            <div class="pull-left info">
                <p><?php echo $userdetails->first_name;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->

        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    //['label' => 'Menu Yii2', 'options' => ['class' => 'header']],

                    ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/']],
                    [
                        'label' => 'Settings',
                        'icon' => ' fa-life-buoy',
                        'url' => '#',
                        'visible'=>false,
                        'items' => [
                            ['label' => 'Topup Wallet', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/create']],
                            ['label' => 'Platform Fees', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/platformfees']],
                            ['label' => 'Withdrawal Fees', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/withdrawalfees']],

                            ['label' => 'Customer Referral Points', 'icon' => ' fa-adjust', 'url' => ['/customerreffererpoints/']],
                            ['label' => 'Merchant Referral Points', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/merchantreffererpoints']],
                            ['label' => 'Cashback Percentage', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/cashback']],
                            ['label' => 'Referral Transfer Percentage', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/refferaltransfer']],
                            //['label' => 'Cashback Percentage', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/cashback']],
                            ['label' => 'In-App Push Notifications ',
                              'icon' => 'circle-o',
                                'url' => '#',
                              'items' => [
                                    ['label' => 'Global Announcements', 'icon' => 'circle-o', 'url' => '/announcements/create',],
                                  ['label' => 'Announcement History', 'icon' => 'circle-o', 'url' => '/announcements',],

                                ],
                            ],
                            ['label' => 'TLS Banners', 'icon' => ' fa-adjust', 'url' => ['/banners'],],
                            ['label' => 'TLS CMS', 'icon' => ' fa-adjust', 'url' => ['/cms'],],
                            ['label' => 'Product Categories', 'icon' => ' fa-adjust', 'url' => ['/categories'],],
                            ['label' => 'Category Row Control', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/categorycontrol'],],
                            ['label' => 'Locations', 'icon' => ' fa-adjust', 'url' => ['/locations'],],
                            ['label' => 'Management',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Account List', 'icon' => ' fa-user', 'url' => '/admins',],
                                    ['label' => 'User Roles', 'icon' => ' fa-user', 'url' => '/rbac/role',],
                                    ['label' => 'Email Templates', 'icon' => 'circle-o', 'url' => '/emailtemplates',],

                                ],
                            ]
                            ]
                        ],
//                    [
//                        'label' => 'Merchant Management',
//                        'icon' => ' fa-users',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => 'Listings', 'icon' => ' fa-adjust', 'url' => ['/merchants'],],
//                            ['label' => 'Approval History', 'icon' => ' fa-adjust', 'url' => ['/approvalhistory'],],
//                            ['label' => 'Merchant Groups', 'icon' => ' fa-adjust', 'url' => ['/merchantgroups'],],
//
//                        ]
//                    ],
                    ['label' => 'Users', 'icon' => ' fa-user', 'url' => ['/users']],

                    ['label' => 'Packages', 'icon' => ' fa-cube', 'url' => ['/packages'],'visible'=>false],

                ],
            ]
        ) ?>

    </section>

</aside>
