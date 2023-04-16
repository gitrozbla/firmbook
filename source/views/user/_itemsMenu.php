<?php 
/*
 * box z menu dodanych obietków użytkownika
 */

if(!isset($companyCount))
	$companyCount = Company::model()->with('item')->count(
			'user_id=:user_id and active=1',
			array(':user_id'=>$user->id)
	);
if(!isset($productCount))
	$productCount = Product::model()->with('item')->count(
			'user_id=:user_id and active=1',
			array(':user_id'=>$user->id)
	);
if(!isset($serviceCount))
	$serviceCount = Service::model()->with('item')->count(
			'user_id=:user_id and active=1',
			array(':user_id'=>$user->id)
	);

?>
		<?php
				$menuItems = array(); 
				$badge = $this->widget(
						'bootstrap.widgets.TbBadge',
						array(
								'type' => 'info',
								'label' => $companyCount,
								'htmlOptions' => array('style' => 'margin-left: 10px;'),
						),
						true
				);
				$menuItems[] = array(
								'label' => '<i class="fa fa-shopping-cart"></i> '
								.Yii::t('user', 'Companies').' '.$badge,
								'url' => $this->createUrl('/user/items/', array(
										'type' => 'companies',
										'username' => $user->username,
									)),						
								);
				
				$badge = $this->widget(
				    'bootstrap.widgets.TbBadge',
				    array(
					    'type' => 'info',					
					    'label' => $productCount,
				    	'htmlOptions' => array('style' => 'margin-left: 10px;'),				    
					), 
					true
				);
				$menuItems[] = array(                            	
                                'label' => '<i class="fa fa-shopping-cart"></i> '
                                        .Yii::t('user', 'Products').' '.$badge,
                                'url' => $this->createUrl('/user/items/', array(
                                		'type' => 'products',
								        'username' => $user->username,
                                    )),                                        
                            	);
								  
                $badge = $this->widget(
				    'bootstrap.widgets.TbBadge',
				    array(
					    'type' => 'info',					
					    'label' => $serviceCount,
				    	'htmlOptions' => array('style' => 'margin-left: 10px;'),				    
					), 
					true
				);                                    
				$menuItems[] = array(
                                'label' => '<i class="fa fa-truck"></i> '
                                        .Yii::t('user', 'Services').' '.$badge,
								'url' => $this->createUrl('/user/items/', array(
										'type' => 'services',
										'username' => $user->username,
									)),                                
                            	);  
                
		?>
		<?php $this->widget(
            'bootstrap.widgets.TbBox',
            array(
                'title' => Yii::t('user', 'User items'),
                'htmlOptions' => array(
                		'class' => 'fixed-menu',
                    	//'class' => 'widget-box fixed-menu',
                ),
                'content' => $this->widget(
                    			'bootstrap.widgets.TbMenu',
			                    array(
			                        'encodeLabel' => false,
			                        'items' => $menuItems,                    	   	
			                    ),
			                    true
			                ),
                'htmlHeaderOptions' => array('class' => $user->getPackageItemClass()),
                'headerIcon' => 'fa fa-shopping-cart',
            )
        ); ?>
        