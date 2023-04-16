<div class="user-navigation">
    <?php 
        $location = $this->id.'/'.$this->action->id; 
        $search = Search::model()->getFromSession();
        $username = Yii::app()->user->name;
    ?>
    <?php $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => Yii::t('navigation', 'User Control Panel'),
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'encodeLabel' => false,
                    'items' => array(
                        array(
                            'label' => '<i class="icon-desktop"></i> '.Yii::t('navigation', 'Desktop'), 
                            'url' => $this->createUrl('/user/desktop'), 
                            'active' => $location == 'user/desktop' ? true : false,
                            ),
                        array(
                            'label' => '<i class="icon-shopping-cart"></i> '.Yii::t('navigation', 'My products'), 
                            'url' => $this->createUrl('/categories/show/', array(
                                'context' => Search::getContextOption($search->action, 'product'),
                                'username' => $username,
                                )),
                            'active' => $location == 'user/products' ? true : false,
                            
                            ),
                        array(
                            'label' => '<i class="icon-truck"></i> '.Yii::t('navigation', 'My services'), 
                            'url' => $this->createUrl('/categories/show/', array(
                                'context' => Search::getContextOption($search->action, 'service'),
                                'username' => $username,
                                )),
                            'active' => $location == 'user/services' ? true : false,
                            ),
                        array(
                            'label' => '<i class="icon-building"></i> '.Yii::t('navigation', 'My companies'), 
                            'url' => $this->createUrl('/categories/show/', array(
                                'context' => Search::getContextOption($search->action, 'company'),
                                'username' => $username,
                                )),
                            'active' => $location == 'user/companies' ? true : false,
                            ),
                        /*array(
                            'label' => Yii::t('navigation', 'News'), 
                            'url' => $this->createUrl('/user/news'),
                            'active' => $location == 'user/news' ? true : false,
                            ),
                        array(
                            'label' => Yii::t('navigation', 'Advertisement'), 
                            'url' => $this->createUrl('/user/advertisement'),
                            'active' => $location == 'user/advertisement' ? true : false,
                            ),
                        array(
                            'label' => Yii::t('navigation', 'Messages'), 
                            'url' => $this->createUrl('/user/messages'),
                            'active' => $location == 'user/messages' ? true : false,
                            ),*/
                        array(
                            'label' => '<i class="icon-user"></i> '.Yii::t('navigation', 'Account'), 
                            'url' => $this->createUrl('/user/profile'),
                            'active' => $location == 'user/profile' ? true : false,
                            ),
                        /*array(
                            'label' => Yii::t('navigation', 'Invoices'), 
                            'url' => $this->createUrl('/user/invoices'),
                            'active' => $location == 'user/invoices' ? true : false,
                            ),*/
                    )
                )
            )
        )
    ); ?>
</div>