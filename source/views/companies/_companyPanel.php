<?php 
/*
 * Panel właścieciela firmy
 */
//$item = $company->item;
?>
<?php if ($this->editorEnabled) : ?>
<?php //if (isset($company) && $this->editorEnabled) : ?>
<div class="span12">
<div class="user-navigation">
    <?php $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => Yii::t('navigation', 'Company Panel'),
			'brandUrl' => false,
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'Menu',
                    'encodeLabel' => false,                	
                    'items' => array(                      
						array(
                            'label' => Yii::t('navigation', 'Products'), 
                            'icon' => 'fa fa-shopping-cart',
							//'active' => true,
                            'badge' => array(
                                'label' => Product::model()->with('item')->count(
									    		'company_id=:company_id',
									    		array(':company_id'=>$company->item_id)
									    	),
                                'type' => 'info',
                                ),
                                
                            /*'url' => $this->createUrl('/companies/offer/', array(
								        'name' => $item->alias,
                                        'type' => 'product')),*/
                            'items' => array(
                                array(
	                                'label' => Yii::t('navigation', 'Products'),
	                                'url' => $this->createGlobalRouteUrl('/companies/offer/', array(
								        'name' => $item->alias,
                                        'type' => 'product')
	                                ),
                            	),
                                array(
	                                'label' => Yii::t('navigation', 'Add new product'),
	                                'url' => $this->createGlobalRouteUrl('/products/add',
        								array('company'=>$company->item_id)),
	                                ),
                            	),
                            ),
                        array(
                            'label' => Yii::t('navigation', 'Services'), 
                            'icon' => 'fa fa-truck',
                            'badge' => array(
                                'label' => Service::model()->with('item')->count(
									    		'company_id=:company_id',
									    		array(':company_id'=>$company->item_id)
									    	),
                                'type' => 'info',
                                ),                            
                            'items' => array(
                                array(
	                                'label' => Yii::t('navigation', 'Services'),
	                                'url' => $this->createGlobalRouteUrl('/companies/offer/', array(
								        'name' => $item->alias,
                                        'type' => 'service')
	                                ),
                            	),
                                array(
	                                'label' => Yii::t('navigation', 'Add new service'),
	                                'url' => $this->createGlobalRouteUrl('/services/add',
        								array('company'=>$company->item_id)),
	                                ),
                            	),
                            ),
                        array(
                            'label' => Yii::t('navigation', 'News'), 
                            'icon' => 'fa fa-file-text-o',
                            'badge' => array(
                                'label' => News::model()->count(
									    		'item_id=:item_id',
									    		array(':item_id'=>$company->item_id)
									    	),
                                'type' => 'info',
                                ),                            
                            'items' => array(
                                array(
	                                'label' => Yii::t('navigation', 'News'),
	                                'url' => $this->createGlobalRouteUrl('/news/list/', array(
								        'name' => $item->alias)
	                                ),
                            	),
                                array(
	                                'label' => Yii::t('navigation', 'Add new article'),
	                                'url' => $this->createGlobalRouteUrl('/news/add',
        								array('company'=>$company->item_id)),
	                                ),
                            	),
                            ),
                    		array(
                    			'label' => Yii::t('navigation', 'Movies'),
                    			'icon' => 'fa fa-film',
                    			'badge' => array(
                    					'label' => Movie::model()->with('item')->count(
                    							'item_id=:item_id',
                    							array(':item_id'=>$company->item_id)
                    					),
                    					'type' => 'info',
                    			),
                    			'items' => array(
                    					array(
                    							'label' => Yii::t('navigation', 'Movies'),
                    							'url' => $this->createGlobalRouteUrl('/movies/list', array(
                    									'name' => $item->alias)                    									
                    							),
                    					),
                    					array(
                    							'label' => Yii::t('navigation', 'Add new movie'),
                    							'url' => $this->createGlobalRouteUrl('/movies/add',
                    									array('name'=>$company->item_id)),
                    					),
                    			),
                    		),
                    		array(
                    			'label' => Yii::t ('attachment', 'Files'),
                    			'icon' => 'fa fa-file',
                    			'badge' => array(
                    				'label' => Attachment::model()->with('item')->count(
                    						'item_id=:item_id',
                    						array(':item_id'=>$company->item_id)
                    				),
                    				'type' => 'info',
                    			),
                    			'items' => array(                    				
                    				array(
                    						'label' => Yii::t('attachment', 'Add attachment'),
                    						'url' => $this->createGlobalRouteUrl('attachments/add', array('name'=>$item->alias)),
                    				),
                    			),
                    		),
                       	array(
                            'label' => Yii::t('navigation', 'Edit company data'), 
                            'icon' => 'fa fa-wrench',                            
                            'url' => $this->createGlobalRouteUrl('companies/update', array('id'=>$item->id)),                         
                            
                            ),
                    )
                )
            )
        )
    ); ?>
</div>

</div>            	
<?php endif; ?>