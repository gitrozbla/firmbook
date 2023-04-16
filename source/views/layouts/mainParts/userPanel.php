<div class="user-navigation">
    <?php 
        $location = $this->id.'/'.$this->action->id; 
        $search = Search::model()->getFromSession();
        $username = Yii::app()->user->name;
        $ownContent = $location == 'categories/show' 
                && $search->username == $username;
        $user = Yii::app()->user;
        $request = Yii::app()->request;
    ?>
    <?php $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => Yii::t('navigation', 'User Control Panel'),
			'brandUrl' => false,
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'Menu',
                    'encodeLabel' => false,
                    'items' => array(
                    	array(
                    		'label' => Yii::t('navigation', 'Add'),
                    		'icon' => 'fa fa-plus',
                    		//'url' => $this->createUrl('/packages/comparison'),
                    		'active' => ($location == 'companies/add' || $location == 'products/add'
                    				|| $location == 'services/add') ? true : false,
                    		'items' => array(
                    			array(
                    				'label' => Yii::t('navigation', 'Add new company'),
                    				'url' => $this->createUrl('/companies/add'),
                    			),
                    			array(
                    				'label' => Yii::t('navigation', 'Add new product'),
                    				'url' => $this->createUrl('/products/add'),
                    			),
                    			array(
                    				'label' => Yii::t('navigation', 'Add new service'),
                    				'url' => $this->createUrl('/services/add'),
                    			))
                    		),
                        array(
                            'label' => Yii::t('navigation', 'Desktop'), 
                            'icon' => 'fa fa-desktop',
                            'url' => $this->createUrl('/user/desktop'), 
                            'active' => $location == 'user/desktop' ? true : false,
                            ),
                    	array(
                    		'label' => Yii::t('navigation', 'My companies'),
                    		'icon' => 'fa fa-building-o',
                    		'badge' => array(
                    				'label' => Item::model()->count(
                    						"cache_type='c' AND user_id=:user_id",
                    						array(':user_id'=>$user->id)),
                    				'type' => 'info',
                    		),
                    		'url' => $this->createUrl('/user/items/', array(
                    				'type' => 'companies',
                    				//'username' => $username,
                    		)),
                    		'active' => ($location == 'user/items' && 
                    				($request->getParam('username') == null 
                    				|| $request->getParam('username') == $user->name) && 
                    				$request->getParam('type') == 'companies'
                    				) ? true : false,
                    		'items' => array(
                    				array(
                    					'label' => Yii::t('companies', 'Companies'),
                    					'url' => $this->createUrl('/user/items/', array(
                    							'type' => 'companies',                    									
                    					)),
                    				),
                    				array(
                    					'label' => Yii::t('navigation', 'Add new company'),
                    					'url' => $this->createUrl('/companies/add'),
                    				))
                    		),
                        array(
                            'label' => Yii::t('navigation', 'My products'), 
                            'icon' => 'fa fa-shopping-cart',
                            'badge' => array(
                                'label' => Item::model()->count(
                                        "cache_type='p' AND user_id=:user_id", 
                                        array(':user_id'=>$user->id)),
                                'type' => 'info',
                                ),
                            
                            'active' => ($location == 'user/items' &&
                                    ($request->getParam('username') == null
                                    || $request->getParam('username') == $user->name) && 
                            		$request->getParam('type') == 'products'
                                    ) ? true : false,
                        	'items' => array(
                        			array(
                        				'label' => Yii::t('navigation', 'Products'),
                        				'url' => $this->createUrl('/user/items/', array(
					                               'type' => 'products',
					                               //'username' => $username,
					                       	)),
                        			),
                        			array(
                        				'label' => Yii::t('navigation', 'Add new product'),
                    					'url' => $this->createUrl('/products/add'),
                        			))
                            ),
                    	/*array(
                            'label' => Yii::t('navigation', 'My products'), 
                            'icon' => 'fa fa-shopping-cart',
                            'badge' => array(
                                'label' => Item::model()->count(
                                        "active=1 AND cache_type='p' AND user_id=:user_id", 
                                        array(':user_id'=>$user->id)),
                                'type' => 'info',
                                ),
                            'url' => $this->createUrl('/categories/show/', array(
                                'context' => Search::getContextOption($search->action, 'product'),
                                'username' => $username,
                                )),
                            'active' => ($ownContent 
                                    && $search->type == 'product') ? true : false,
                            
                            ),*/	
                        array(
                            'label' => Yii::t('navigation', 'My services'), 
                            'icon' => 'fa fa-truck',
                            'badge' => array(
                                'label' => Item::model()->count(
                                        "cache_type='s' AND user_id=:user_id", 
                                        array(':user_id'=>$user->id)),
                                'type' => 'info',
                                ),
                            'url' => $this->createUrl('/user/items/', array(
                                'type' => 'services',
                                //'username' => $username,
                                )),
                            'active' => ($location == 'user/items' &&
                                    ($request->getParam('username') == null
                                    || $request->getParam('username') == $user->name) && 
                            		$request->getParam('type') == 'services'
                                    ) ? true : false,
                        	'items' => array(
                        		array(
                        				'label' => Yii::t('navigation', 'Services'),
                        				'url' => $this->createUrl('/user/items/', array(
                        						'type' => 'services',                        						
                        				)),
                        		),
                        		array(
                        				'label' => Yii::t('navigation', 'Add new service'),
                        				'url' => $this->createUrl('/services/add'),
                        		))
                            ),
                    	/*array(
                            'label' => Yii::t('navigation', 'My services'), 
                            'icon' => 'fa fa-truck',
                            'badge' => array(
                                'label' => Item::model()->count(
                                        "active=1 AND cache_type='s' AND user_id=:user_id", 
                                        array(':user_id'=>$user->id)),
                                'type' => 'info',
                                ),
                            'url' => $this->createUrl('/categories/show/', array(
                                'context' => Search::getContextOption($search->action, 'service'),
                                'username' => $username,
                                )),
                            'active' => ($ownContent
                                    && $search->type == 'service') ? true : false,
                            ),*/                        
                    	/*array(
                            'label' => Yii::t('navigation', 'My companies'), 
                            'icon' => 'fa fa-building-o', 
                            'badge' => array(
                                'label' => Item::model()->count(
                                        "active=1 AND cache_type='c' AND user_id=:user_id", 
                                        array(':user_id'=>$user->id)),
                                'type' => 'info',
                                ),
                            'url' => $this->createUrl('/categories/show/', array(
                                'context' => Search::getContextOption($search->action, 'company'),
                                'username' => $username,
                                )),
                            'active' => ($ownContent
                                    && $search->type == 'company') ? true : false,
                            ),*/	
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
                            'label' => Yii::t('navigation', 'Account'), 
                            'icon' => 'fa fa-user',
                            'url' => $this->createUrl('/user/profile'),
                            'active' => ($location == 'user/profile' &&
                                    ($request->getParam('username') == null
                                    || $request->getParam('username') == $user->name)
                                    ) ? true : false,
                            ),
                    	array(
                    		'label' => Yii::t('navigation', 'Packages'),
                    		'icon' => 'fa fa-credit-card',
                    		//'url' => $this->createUrl('/packages/comparison'),
                    		'active' => ($location == 'packages/comparison' || $location == 'packages/history') ? true : false,
                    		'items' => array(
                    			array(
                    				'label' => Yii::t('navigation', 'Package change'),
                    				'url' => $this->createUrl('/packages/comparison'),
                    			),
                    			array(
                    				'label' => Yii::t('navigation', 'Payments'),
                    				'url' => $this->createUrl('/packages/history'),
                    			))
                    		),
                    	array(
                    		'label' => Yii::t('navigation', 'Ad'),
                    		'icon' => 'fa fa-credit-card',
                    		//'url' => $this->createUrl('/packages/comparison'),
                    		'active' => ($location == 'promotion/offer' || $location == 'promotion/history') ? true : false,
                    		'items' => array(
                    			array(
                    				'label' => Yii::t('navigation', 'Offer'),
                    				'url' => $this->createUrl('/promotion/offer'),
                    			),
                    			array(
                    				'label' => Yii::t('navigation', 'Orders'),
                    				'url' => $this->createUrl('/promotion/history'),
                    			))
                    		),
                        /*array(
                            'label' => Yii::t('navigation', 'Packages'), 
                            'icon' => 'fa fa-exchange',
                            'url' => $this->createUrl('/packages/comparison'),
                        	'active' => ($location == 'packages/comparison') ? true : false,                            
                            ),
                        array(
                            'label' => Yii::t('navigation', 'Payments'), 
                            //'icon' => 'fa fa-user',
                            'icon' => 'fa fa-credit-card',
                            'url' => $this->createUrl('/packages/history'),
                        	'active' => ($location == 'packages/history') ? true : false,                            
                            ),*/    
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