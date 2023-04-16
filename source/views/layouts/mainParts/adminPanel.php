<div class="user-navigation">
    <?php 
        $location = $this->id.'/'.$this->action->id; 
        $search = Search::model()->getFromSession();
        $username = Yii::app()->user->name;
    ?>
    <?php $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => Yii::t('navigation', 'Admin Control Panel'),
            'brandUrl' => false,
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'Menu',
                    'encodeLabel' => false,
                    'items' => array(
                        array(
                            'label' => Yii::t('admin', 'Statistics'), 
                            'icon' => 'fa fa-bar-chart-o',
                            'url' => $this->createUrl('/admin/statistics/'),
                            'active' => $location == 'admin/statistics' ? true : false,
                            ),
                        array(
                            'label' => Yii::t('products', 'Products'), 
                            'icon' => 'fa fa-shopping-cart',
                            'badge' => array(
                                'label' => Item::model()->count("active=1 AND cache_type='p'"),
                                'type' => 'info',
                            ),
//                            'url' => $this->createUrl('/categories/show/', array(
//                                'context' => Search::getContextOption($search->action, 'product'),
//                                )),
                            'url' => $this->createUrl('/admin/products'),
                            'active' => ($location == 'categories/show' 
                                    && $search->type == 'product')  ? true : false,
                            ),
                        array(
                            'label' => Yii::t('services', 'Services'), 
                            'icon' => 'fa fa-truck',
                            'badge' => array(
                                'label' => Item::model()->count("active=1 AND cache_type='s'"),
                                'type' => 'info',
                            ),
//                            'url' => $this->createUrl('/categories/show/', array(
//                                'context' => Search::getContextOption($search->action, 'service'),
//                                )),
                            'url' => $this->createUrl('/admin/services'),
                            'active' => ($location == 'categories/show' 
                                    && $search->type == 'service')  ? true : false,
                            ),
                        array(
                            'label' => Yii::t('companies', 'Companies'), 
                            'icon' => 'fa fa-building-o',
                            'badge' => array(
                                'label' => Item::model()->count("active=1 AND cache_type='c'"),
                                'type' => 'info',
                            ),
//                            'url' => $this->createUrl('/categories/show/', array(
//                                'context' => Search::getContextOption($search->action, 'company'),
//                                )),
//                            'url' => $search->createUrl(null, array('companies_context'=>Search::getContextUrlAction($search->action,'company')), true),
                            'url' => $this->createUrl('/admin/companies'),
                            'active' => ($location == 'categories/show' 
                                    && $search->type == 'company')  ? true : false,
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
                            'label' => Yii::t('admin', 'Users'), 
                            'icon' => 'fa fa-users',
                            'badge' => array(
                            	'label' => User::model()->count('register_source='.User::REGISTER_SOURCE_FIRMBOOK),
                                //'label' => User::model()->count('active=1'),
                                'type' => 'info',
                                ),
                            'url' => $this->createUrl('/admin/users'),
                            'active' => $location == 'admin/users' ? true : false,
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
    
<?php 
	if (Yii::app()->user->isAdmin) {		
	   
    $this->widget(
		'bootstrap.widgets.TbButtonGroup',
			array(
				//'context' => 'default',
				// '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
				'buttons' => array(
					array('label' => Yii::t('packages','Packages'), 'url' => $this->createUrl('/admin/packages')),
					array(
						'items' => array(
							//array('label' => 'Usługi w pakietach', 'url' => $this->createUrl('/admin/packagespackageservices')),
							array('label' => 'Usługi', 'url' => $this->createUrl('/admin/packagesservices')),
					    	array('label' => 'Okresy', 'url' => $this->createUrl('/admin/packagesperiods')),
							array('label' => 'Historia', 'url' => $this->createUrl('/admin/packagespurchases')),
							/*'---',
							array('label' => 'Separate link', 'url' => '#'),*/
						)
					),
				),
			)
	);  echo '&nbsp;&nbsp;&nbsp;';
    $this->widget(
    		'bootstrap.widgets.TbButtonGroup',
    		array(    				
    			'buttons' => array(
    				array('label' => 'Reklamy', 'url' => $this->createUrl('/admin/ads')),
    				array(
    					'items' => array(
    						//array('label' => 'Usługi w pakietach', 'url' => $this->createUrl('/admin/packagespackageservices')),
    						array('label' => 'Boxy', 'url' => $this->createUrl('/admin/adsboxes')),
    				    	array('label' => 'Zamówienia', 'url' => $this->createUrl('/admin/adsorders')),
    						array('label' => 'Banery', 'url' => $this->createUrl('/admin/ads')),
    			
    					)
    				),
    			),
    		)
    );  echo '&nbsp;&nbsp;&nbsp;';
    
    $this->widget(
    		'bootstrap.widgets.TbButton',
    		array(
    				'label' => Yii::t('categories', 'Categories'),
    				'url' => $this->createUrl('/admin/categories'),
    		)
    );  echo '&nbsp;&nbsp;&nbsp;';
    
	}
	
	$this->widget(
		'bootstrap.widgets.TbButton',
			array(				
				'label' => Yii::t('companies', 'Companies'), 
				'url' => $this->createUrl('/admin/companies'),				
			)
	);  echo '&nbsp;&nbsp;&nbsp;';
	
	if (Yii::app()->user->isAdmin) {
	
	$this->widget(
		'bootstrap.widgets.TbButton',
			array(				
				'label' => 'Artykuły', 
				'url' => $this->createUrl('/admin/articles'),				
			)
	);  echo '&nbsp;&nbsp;&nbsp;';
	$this->widget(
		'bootstrap.widgets.TbButton',
			array(				
				'label' => 'Uprawnienia', 
				'url' => $this->createUrl('/rights'),	
				'type' => 'danger',			
			)
	);  echo '&nbsp;&nbsp;&nbsp;';
//	$this->widget(
//		'bootstrap.widgets.TbButton',
//			array(				
//				'label' => 'Powiadom o wygaśnięciu', 
//				'url' => $this->createUrl('/packages/notifypackageexpireafter'),	
//				'type' => 'warning',			
//			)
//	);  echo '&nbsp;&nbsp;&nbsp;';
//	$this->widget(
//		'bootstrap.widgets.TbButton',
//			array(				
//				'label' => 'Wygaś pakiety', 
//				'url' => $this->createUrl('/packages/disablepurchasedpackages'),	
//				'type' => 'warning',			
//			)
//	);
	
	}
	
 ?>
 
    <?php   /*  $this->widget(
    'bootstrap.widgets.TbNavbar',
    	array(
		    'brand' => 'Słowniki',
		    'fixed' => false,
		    'fluid' => true,
		    'items' => array(
			    array(
				    'class' => 'booster.widgets.TbMenu',
				    'type' => 'navbar',
				    'items' => array(
					    array(
					    	'label' => Yii::t('packages','Packages'), 
					    	'url' => $this->createUrl('/packages/adminpackages'), 
					    	'active' => true,
					    	'items' => array(
					    		array('label' => 'Pakiety', 'url' => $this->createUrl('/packages/adminservices')),
					    		array('label' => 'Okresy', 'url' => $this->createUrl('/packages/adminperiods'))
					    	)
					    ),
					    array('label' => 'Link', 'url' => '#'),
					    array(
						    'label' => 'Dropdown',
						    'items' => array(
					    		array('label' => 'Item1', 'url' => '#')
					    	)
					    ),
				    )
			    )
		    )
	    )
    ); 
    
   */ ?>
</div>