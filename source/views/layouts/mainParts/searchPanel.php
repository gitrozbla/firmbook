<div class="search">
    <div class="row">        
        <div class="span3 search-buttons-type">
	<?php // if (Yii::app()->session['choose-context-shown']) : ?>
            <!--<div>-->
        <?php // else: ?>
            <?php // Yii::app()->session['choose-context-shown'] = true; ?>
            <div class="choose-context-wrapper">
                <div class="choose-context">
                    <?php echo Yii::t('site', 'Choose context'); ?>:
                </div>
        <?php // endif; ?>

				<?php 
					$action = $search->action;
					$type = $search->type;
				?>
				<?php $this->widget(
					'bootstrap.widgets.TbButtonGroup',
					array(
						'buttons' => array(
							array(
								'label' => Yii::t('search', 'Companies'), 
// 								'url' => $search->createUrl(null, array('context'=>$search->getContextOption($action, 'company'))),
// 								'url' => $search->createUrl(null, array('companies_context'=>Search::contextToActionTypeContext(Search::getContextOption($action,$type)))),
								'url' => $search->createUrl(null, array('companies_context'=>Search::getContextUrlAction($action,'company')), true),
								'type' => $type === 'company' ? 'primary' : 'normal',
								),
							array(
								'label' => Yii::t('search', 'Products'), 
// 								'url' => $search->createUrl(null, array('context'=>$search->getContextOption($action, 'product'))),
								'url' => $search->createUrl(null, array('products_context'=>Search::getContextUrlAction($action,'product')), true),
								'type' => $type === 'product' ? 'primary' : 'normal',
								),
							array(
								'label' => Yii::t('search', 'Services'), 
// 								'url' => $search->createUrl(null, array('context'=>$search->getContextOption($action, 'service'))),
								'url' => $search->createUrl(null, array('services_context'=>Search::getContextUrlAction($action,'service')), true),									
								'type' => $type === 'service' ? 'primary' : 'normal',
								),
						),
					)
				); ?>
			</div>
        </div>        
        <div class="span4 search-buttons-action">            
            <?php $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'buttons' => array(                        
                        array(
                            'label' => Yii::t('search', $search->getContextLabel('sell', $type)), 
//                             'url' => $search->createUrl(null, array('context'=>$search->getContextOption('sell', $type))),
//                         	'url' => $search->createUrl(null, array(Search::getContextUrlType($type).'_context'=>Yii::t('url', Search::getContextUrlAction('sell',$type)))),
                        		'url' => $search->createUrl(null, array(Search::getContextUrlType($type).'_context'=>Search::getContextUrlAction('sell',$type)), true),
                            'type' => $action === 'sell' ? 'primary' : 'normal',
                        	'htmlOptions' => array('style'=>'padding-left:60px; padding-right:60px')	
                            ),
                    	array(
                    		'label' => Yii::t('search',$search->getContextLabel('buy', $type)),
//                     		'url' => $search->createUrl(null, array('context'=>$search->getContextOption('buy', $type))),
                    		'url' => $search->createUrl(null, array(Search::getContextUrlType($type).'_context'=>Search::getContextUrlAction('buy',$type)), true),
                    		'type' => $action === 'buy' ? 'primary' : 'normal',
                    		),
                    ),
                )
            ); ?>
        </div>
        
		<?php /*
		    $class = ucfirst($type);
		    switch($type) {
		        case 'product': 
		            $controller = 'products';
		            break;
		        case 'service': 
		            $controller = 'services';
		            break;
		        case 'company': 
		            $controller = 'companies';
		            break;
		    }
		?>
		<?php if (Yii::app()->user->checkAccess(ucfirst($controller).'.add')) : ?>
		<div class="span2 search-buttons-type">    
		    <?php $this->widget(
		        'Button',
		        array(
		            'label' => Yii::t($type, 'Add '.$type),
		            'type' => 'success',
		            'icon' => 'fa fa-plus',
		        	'url' => $this->createUrl($controller.'/add'),            
		            //'disabled' => $addButtonDisabled,
		        )
		    ); ?> 
		</div>   
		<?php endif; */?>
		
		<?php /*   
		    $this->widget(
				'bootstrap.widgets.TbButtonGroup',
					array(
						'type' => 'success',
						// '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
						'buttons' => array(
							array('label' => Yii::t('companies','Add'), 'url' => $this->createUrl('/admin/packages')),
							array(
								'items' => array(
									//array('label' => 'Usługi w pakietach', 'url' => $this->createUrl('/admin/packagespackageservices')),
									array('label' => 'Usługi', 'url' => $this->createUrl('/admin/packagesservices')),
							    	array('label' => 'Okresy', 'url' => $this->createUrl('/admin/packagesperiods')),
									array('label' => 'Historia', 'url' => $this->createUrl('/admin/packagespurchases')),
									'---',
									array('label' => 'Separate link', 'url' => '#'),
								)
							),
						),
					)
			);*/ ?>
			
		<?php if (!$search->isAdvanced) : ?>    
		<div class="span5 search-simple" style="margin-bottom: 30px">            
                <?php require 'searchSimple.php'; ?>            
        </div>
		<?php endif; ?> 
        <?php // if(!Yii::app()->user->isGuest) : ?>        
            <?php // require 'modalButtons.php'; ?>    
        <?php // endif; ?>     
		
		<?php /*  
        <div class="span4 search-simple">
            <?php if (!$search->isAdvanced) : ?>
                <?php require 'searchSimple.php'; ?>
            <?php endif; ?>
        </div> */?>
    </div>
    
    <div class="search-advanced">
        <?php if ($search->isAdvanced) : ?>
            <?php require 'searchAdvanced.php'; ?>
        <?php endif; ?>
    </div>
</div>

			    
