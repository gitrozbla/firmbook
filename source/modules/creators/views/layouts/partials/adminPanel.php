<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<br />
<?php
$location = $this->id.'/'.$this->action->id;
$search = Search::model()->getFromSession();
$username = Yii::app()->user->name;
?>
    <?php $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => 'CRM - '.Yii::t('navigation', 'Admin Control Panel'),
            'brandUrl' => false,
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'Menu',
                    'encodeLabel' => false,
                    'items' => array(                       
                        array(
                        	'label' => Yii::t('admin', 'Users'), 
                            'icon' => 'fa fa-users',
                            'badge' => array(
                            	'label' => User::model()->count('register_source='.User::REGISTER_SOURCE_CREATORS),
                                //'label' => User::model()->count('active=1'),
                                'type' => 'info',
                                ),
                        	'url' => $this->createUrl('/admin/users'),
                            //'url' => $this->createFirmbookUrl('/admin/users'),
                            'active' => $location == 'admin/users' ? true : false,                       		
                        ),
						array(
							'label' =>  Yii::t('categories', 'Categories'),
							'icon' => 'fa fa-list',
							'url' => $this->createGlobalRouteUrl('/admin/categories'),
								//'url' => $this->createFirmbookUrl('/admin/users'),
								'active' => $location == 'admin/users' ? true : false,
						),
                    	array(
                    		'label' => Yii::t('packages', 'Packages'),
												'icon' => 'fa fa-tags',
                    		//'url' => $this->createUrl('/admin/packages'),
                    		'active' => ($location == 'admin/packages'
                    			|| $location == 'admin/packagesservices'
                    			|| $location == 'admin/packagesperiods'
                    			|| $location == 'admin/packagespurchases') ? true : false,
                    		'items' => array(
                    			array(
									'label' => Yii::t('packages', 'Packages'),
									'url' => $this->createUrl('/admin/packages')
								),
                    			array(
									'label' => Yii::t('packages', 'Services'),
									'url' => $this->createUrl('/admin/packagesservices')
								),
                    			array(
									'label' => Yii::t('packages', 'Periods'),
									'url' => $this->createUrl('/admin/packagesperiods')
								),
                    			array(
									'label' => Yii::t('packages', 'History'),
									'url' => $this->createUrl('/admin/packagespurchases')
								),
                    		)
                    	),
                    	array(
                    		'label' => Yii::t('admin', 'Articles'),
							'icon' => 'fa fa-pencil',							
                    		'url' => $this->createUrl('/admin/articles'),                    		
                    		'active' => $location == 'admin/articles' ? true : false,
                    	),
						array(
							'label' => Yii::t('admin', 'Calendar'),
							'icon' => 'fa fa-calendar',
							'url' => $this->createUrl('/admin/calendar'),
							'active' => $location == 'admin/calendar' ? true : false
						)
                    )
                )
            )
        )
    ); ?>
    

 
    
