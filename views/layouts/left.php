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
                <p><?php echo $userdetails->full_name;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->

        <!-- /.search form -->
        <?php
        $item = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
?>
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
                        'visible'=>true,
                        'items' => [
                            ['label' => 'Agreement Templates', 'icon' => ' fa-adjust', 'url' => ['/agreementtemplates']],
                            ['label' => 'Packages', 'icon' => ' fa-adjust', 'url' => ['/packages']],
                            ['label' => 'Platform Fees', 'icon' => ' fa-adjust', 'url' => ['/platformfees']],
//                            ['label' => 'Platform Fees', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/platformfees']],
//                            ['label' => 'Withdrawal Fees', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/withdrawalfees']],
//
//                            ['label' => 'Customer Referral Points', 'icon' => ' fa-adjust', 'url' => ['/customerreffererpoints/']],
//                            ['label' => 'Merchant Referral Points', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/merchantreffererpoints']],
//                            ['label' => 'Cashback Percentage', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/cashback']],
//                            ['label' => 'Referral Transfer Percentage', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/refferaltransfer']],
//                            //['label' => 'Cashback Percentage', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/cashback']],
//                            ['label' => 'In-App Push Notifications ',
//                              'icon' => 'circle-o',
//                                'url' => '#',
//                              'items' => [
//                                    ['label' => 'Global Announcements', 'icon' => 'circle-o', 'url' => '/announcements/create',],
//                                  ['label' => 'Announcement History', 'icon' => 'circle-o', 'url' => '/announcements',],
//
//                                ],
//                            ],
//                            ['label' => 'TLS Banners', 'icon' => ' fa-adjust', 'url' => ['/banners'],],
//                            ['label' => 'TLS CMS', 'icon' => ' fa-adjust', 'url' => ['/cms'],],
//                            ['label' => 'Product Categories', 'icon' => ' fa-adjust', 'url' => ['/categories'],],
//                            ['label' => 'Category Row Control', 'icon' => ' fa-adjust', 'url' => ['/topupwallet/categorycontrol'],],
//                            ['label' => 'Locations', 'icon' => ' fa-adjust', 'url' => ['/locations'],],
//                            ['label' => 'Management',
//                                'icon' => 'circle-o',
//                                'url' => '#',
//                                'items' => [
//                                    ['label' => 'Account List', 'icon' => ' fa-user', 'url' => '/admins',],
//                                    ['label' => 'User Roles', 'icon' => ' fa-user', 'url' => '/rbac/role',],
//                                    ['label' => 'Email Templates', 'icon' => 'circle-o', 'url' => '/emailtemplates',],
//
//                                ],
//                            ]
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
                    ['label' => 'Users', 'icon' => ' fa-user', 'url' => ['/users'],'active'=>($item == 'users')
                    ],

                    ['label' => 'Packages', 'icon' => ' fa-cube', 'url' => ['/packages'],'visible'=>false],
                    ['label' => 'Properties', 'icon' => ' fa-home', 'url' => ['/properties'],'active'=>($item == 'properties' || ($item=='images' && $action=='create'))],
                    ['label' => 'Managed Properties', 'icon' => ' fa-cube', 'url' => ['/managedproperties'],'active'=>($item == 'managedproperties' || ($item=='images' && $action='add'))],
                    ['label' => 'Booking Requests', 'icon' => '  fa-database', 'url' => ['/bookingrequests'],'active'=>($item == 'bookingrequests')],
                    ['label' => 'Renovation Quotes', 'icon' => ' fa-recycle', 'url' => ['/renovationquotes'],'active'=>($item == 'renovationquotes')],
                    ['label' => 'Insurances', 'icon' => ' fa-shield', 'url' => ['/insurances'],'active'=>($item == 'insurances')],
                    ['label' => 'Defect Reports', 'icon' => ' fa-bug', 'url' => ['/defectreports'],'active'=>($item == 'defectreports')],
                    ['label' => 'Auto Rental Collection', 'icon' => '  fa-ticket', 'url' => ['/autorentalcollections'],'active'=>($item == 'autorentalcollections')],
                    ['label' => 'General Invoices', 'icon' => '   fa-money', 'url' => ['/invoices'],'active'=>($item == 'invoices')],
                    ['label' => 'Service Requests', 'icon' => '   fa-gear', 'url' => ['/servicerequests'],'active'=>($item == 'servicerequests')],
                    ['label' => 'Promo Codes', 'icon' => '    fa-tags', 'url' => ['/promocodes'],'active'=>($item == 'promocodes')],
                    ['label' => 'Gold Coins', 'icon' => '     fa-sun-o', 'url' => ['/goldcoins'],'active'=>($item == 'goldcoins')],



                ],
            ]
        ) ?>

    </section>

</aside>
