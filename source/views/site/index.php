<?php 
/**
 * Strona główna (index).
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php
    $search = Search::model()->getFromSession();
    $action = $search->action;
    $type = $search->type;
    $editor = Yii::app()->session['editor'];
    
//    $articleLandingPage = Article::model()->find(
//    		'alias="landing-page"'
//    );    
?>

<div class="row">
    <div class="span3">   

    	<?php if($this->beginCache('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type)) { ?>
        <?php //if($this->beginCache('category-menu-'.Yii::app()->language, array('duration'=>3600))) { ?>        
            <?php $this->widget(
                'bootstrap.widgets.TbMenu',//'application.components.widgets.EditableMenu', //'bootstrap.widgets.TbMenu',
                array(
					'id' => 'main-menu',
                    'type' => 'list',
                    'items' => array_merge(
                        array(
                            array(
                                'label' => Yii::t('categories', 'Categories'),
                                'itemOptions' => array('class' => 'nav-header')
                            ),
                        ),
                        Category::getMenuItems()
                    ),
                    'encodeLabel' => false,
                    'htmlOptions' => array(
                        'class' => 'category-menu well bg-transparent box',
                    ),
                )
            ); ?>
        <?php $this->endCache(); } ?>
		
		<?php $this->widget('AdBox', array(
            'groupId'=>'right-1',
        )); ?>
        
        <?php /*
         * NEWSLETTER
         */ ?>
        <?php /*<div class="well box bg-transparent">
            <?php if (empty($newsletterReader)) : ?>
                <?php $this->widget(
                        'Button',
                        array(
                            'label' => Yii::t('site', 'You are subscribed to newsletter'),
                            'url' => array('newsletter/subscribe'),
                            'type' => 'success',
                            'disabled' => true,
                        )
                    ); ?>
            <?php else : ?>
                <?php $form = $this->beginWidget(
                    'ActiveForm',
                    array(
                        'id' => 'newsletter-form',
                        'type' => 'search',
                        //'action' => $this->createUrl('newsletter/subscribe'),
                    )
                ); ?>
                    <?php if (Yii::app()->user->isGuest) : ?>
                        <?php echo Yii::t('site', 'Enter your email address:'); ?>
                        <?php echo $form->textFieldRow(
                            $newsletterReader,
                            'email'
                        ); ?>
                    <?php else: ?>
                        <?php echo $form->hiddenField(
                            $newsletterReader,
                            'email',
                            array(
                                'value' => Yii::app()->user->getModel()->email,
                            )
                        ); ?>
                    <?php endif; ?>
                    <?php echo $form->errorSummary($newsletterReader, ''); ?>
                    <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'label' => Yii::t('site', 'Subscribe newsletter'),
                            'url' => array('newsletter/subscribe'),
                            'buttonType' => 'submit',
                            'type' => 'primary',
                            'htmlOptions' => array(
                                'class' => 'primary full-width strong top-10',
                            )
                        )
                    ); ?>
                <?php $this->endWidget(); ?>
            <?php endif; ?>
            
            <?php if (Yii::app()->user->isAdmin) : ?>
                <hr />
                <?php $this->widget('bootstrap.widgets.TbMenu',
                    array(
                        'type' => 'list',
                        'items' => array(
                            array(
                                'label' => '<i class="fa fa-pencil"></i>&nbsp;'
                                    .Yii::t('newsletter', 'Posts'),
                                'url' => $this->createUrl('newsletter/posts'),
                            ),
                            array(
                                'label' => '<i class="fa fa-envelope-o"></i>&nbsp;'
                                    .Yii::t('newsletter', 'Readers'),
                                'url' => $this->createUrl('newsletter/readers'),
                            ),
                        ),
                        'encodeLabel' => false,
                    )
                ); ?>
            <?php endif; ?>
        </div>*/ ?>
        <?php /*
         * END NEWSLETTER
         */ ?>
    </div>
    
    <div class="span9">
        <?php if(Yii::app()->user->isGuest) : ?>
<!--            <div class="home-landing-page">
            <div class="well landing-1">
            <?php //echo CHtml::image('images/slider/1-1.jpg', '', array('style'=>'margin-left: 10px;')); ?>
            </div>
            </div>    -->
            <?php $this->widget('LandingPage', array(
				'articlesGroup' => 'landing-page',				
			)); ?>
        <?php endif; ?>
    	<?php if(Yii::app()->user->isGuest && false) : ?>
			<?php $this->widget('HomeSlider', array(
				'articlesGroup' => 'slider-page',
				'height' => '600px'
			)); ?>
    	<?php /*$this->beginWidget(
    		'bootstrap.widgets.TbHeroUnit',
    		array(
    			//'heading' => Yii::t('article.title', $articleLandingPage->title, array(), 'dbMessages').
    			//'heading' => '<img src="images/branding/email-logo.png" alt="'.Yii::app()->params['branding']['title'].'"/>',
    			'encodeHeading' => false,
    			//'heading' => 'Witaj na Firmbook-u!',
    			'htmlOptions' => array(
    				//'class' => 'text-center',	
    				'style' => 'background: #E6F3FC url(images/slider/1.jpg); 
    					padding-top:200px; 
    					background-position: 50% 0;
    					background-repeat: no-repeat;',
    				//background-repeat: no-repeat;	
    				//'style' => 'background-color: rgba(0,165,0,0.1); cursor: pointer',
    				//'style' => 'background-color: rgba(92,170,229,0.1); cursor: pointer',
    				//'onclick' => 'document.location.href="http://onet.pl"'
    				'onclick' => 'document.location.href="'.$this->createAbsoluteUrl('account/register').'"'	
    							
    			)			
    		)			
    	); ?>    	
    		<?php echo Yii::t('article.content', '{'.$articleLandingPage->alias.'}', array('{'.$articleLandingPage->alias.'}'=>$articleLandingPage->content), 'dbMessages'); ?>
    		<?php //<p class="lead primary">Firmbook - pierwsza platforma business community. Nie zwlekaj - utwórz darmowe konto i zacznij korzystać z narzędzi, możliwości i środków udostępnianych na firmbooku.</p>?>
			<p class="text-center">
				<?php $this->widget(
						'bootstrap.widgets.TbButton',
						array(
							'label' => Yii::t('site', 'Add product/service/company'),
							'url' => array('account/register'),
							'type' => 'primary',
							'size' => 'large',	
							'htmlOptions' => array(
								'class' => 'top-10',
							),
						)
					); ?>
				<?php $this->widget(
						'bootstrap.widgets.TbButton',
						array(
							'label' => Yii::t('site', 'Join For Free!'),
							'url' => array('account/register'),
							'type' => 'primary',
							'size' => 'large',	
							'htmlOptions' => array(
								'class' => 'top-10',
								'style' => 'margin-left: 10px'	
							),
						)
                ); ?>    
			</p>
    	<?php $this->endWidget();*/ ?>
    	<?php endif; ?>
        <?php /*$this->widget('AdBox', array(
            'groupId'=>'content-1',
            //'type'=>'carousel',
        ));*/ ?>
        
		<div class="row">
			    <div class="span6">
				<?php if($this->beginCache('home-carousels-'.$action.'-'.$type.
						'-'.Yii::app()->language, array('duration'=>60))) { ?>
					<?php
						// newest
						switch ($action.' '.$type) {
							case 'buy product': 
								$newItemsTitle = 'New products to buy'; 
								$promotedItemsTitle = 'Promoted products to buy';
								break;
							case 'sell product':  
								$newItemsTitle = 'New products to sell'; 
								$promotedItemsTitle = 'Promoted products to sell'; 
								break;
							case 'buy service': 
								$newItemsTitle = 'New services requests'; 
								$promotedItemsTitle = 'Promoted services requests';
								break;
							case 'sell service': 
								$newItemsTitle = 'New services to offer'; 
								$promotedItemsTitle = 'Promoted services to offer';
								break;
							case 'buy company': 
								$newItemsTitle = 'New buying companies'; 
								$promotedItemsTitle = 'Promoted buying companies'; 
								break;
							case 'sell company': 
								$newItemsTitle = 'New selling companies'; 
								$promotedItemsTitle = 'Promoted selling companies'; 
								break;
						};
						switch ($type) {
							case 'product': $itemsController = 'products'; $itemsIcon = 'fa fa-shopping-cart'; break;
							case 'service': $itemsController = 'services'; $itemsIcon = 'fa fa-truck'; break;
							case 'company': $itemsController = 'companies'; $itemsIcon = 'fa fa-building-o'; break;           
						} 
					?>
				
					<?php $this->widget(
						'CarouselListView',
						array(
							'dataProvider' => Item::model()->newestDataProvider(
									$type,
									$action
									),
							'template' => "{carouselPager} {title}\n{items}",
							'itemView' => '_listItem',
							'viewData' => compact('itemsController', 'itemsIcon'),

							'carousel' => true,
							'carouselOptions' => array(
								'direction' => 'left',
								'width' => 'auto',
								'height' => '270px',
								'scroll' => array(
									'pauseOnHover' => true		
								),
                                'auto' => array(
                                    'timeoutDuration' => 7000,
                                ),
							),

							'title' => Yii::t('site', $newItemsTitle),

							'htmlOptions' => array(
								'class' => 'home-carousel',
							)
						)
					); ?>
				
					<?php $this->widget(
						'CarouselListView',
						array(
							'dataProvider' => Item::model()->promotedDataProvider(
									$type,
									$action
									),
							'template' => "{carouselPager} {title}\n{items}",
							'itemView' => '_listItem',
							'viewData' => compact('itemsController', 'itemsIcon'),

							'carousel' => true,
							'carouselOptions' => array(
								'direction' => 'left',
								'width' => 'auto',
								'height' => '270px',
								'scroll' => array(
									'pauseOnHover' => true
								),
                                'auto' => array(
                                    'timeoutDuration' => 7000,
                                ),
							),

							'title' => Yii::t('site', $promotedItemsTitle),

							'htmlOptions' => array(
								'class' => 'home-carousel',
							)
						)
					); ?>
				
				<?php $this->endCache(); } ?>
			</div>
			
			<div class="span3">
				<?php if (Yii::app()->user->isGuest) : ?>
					<?php /*<div class="well box bg-transparent text-center">
						<strong><?php echo Yii::t('site', 'Welcome to {name}', 
								array('{name}'=>Yii::app()->name)); ?></strong>
						<br />

						<strong><?php $this->widget(
							'bootstrap.widgets.TbButton',
							array(
								'label' => Yii::t('site', 'Join For Free!'),
								'url' => array('account/register'),
								'type' => 'primary',
								'htmlOptions' => array(
									'class' => 'block top-10',
								),
							)
						); ?></strong>
						<strong><?php $this->widget(
							'bootstrap.widgets.TbButton',
							array(
								'label' => Yii::t('site', 'Log in'),
								'url' => array('account/login'),
								'htmlOptions' => array(
									'class' => 'block top-10',
								),
							)
						); ?></strong>
					</div>*/ ?>
				<?php else: ?>
					<div class="well box bg-transparent ">
						<div class="text-center"><?php echo Yii::t('site', 'Welcome'); ?><br />
							<strong>
								<?php $this->widget('application.components.widgets.TextOverflowScroll', array(
									'text' => Yii::app()->user->name
								)); ?>
							</strong>
						</div>
						<?php /*<br />
						<?php echo Yii::t('site', 'Your package:'); ?>
						
						<strong>$this->widget(
							'bootstrap.widgets.TbButton',
							array(
								'label' => Yii::t('site', 'Increase Package'),
								'htmlOptions' => array(
									'class' => 'block top-10',
								),
							)
						); ?></strong>*/ ?>
						
						<hr />
						<p>
						<?php echo Yii::t('packages', 'Your package:');?>
						<?php echo Yii::app()->user->getModel()->badge(true); ?>
						</p>

                        <?php $package = Yii::app()->user->getModel()->package;
                        if ($package && $package->id != 4 && Package::canTestPackage(Yii::app()->user->id)) : ?>
                        <strong><?php $this->widget(
		                    'bootstrap.widgets.TbButton',
		                    array(
		                        'label' => Yii::t('site', 'Test the best package for 14 days for free!'),
		                        'url' => array('packages/change', 'package'=>4),
		                        'type' => 'primary',
		                        'htmlOptions' => array(
		                            'class' => 'block top-10',
		                        ),
		                    )
		                ); ?></strong><br />
                        <?php endif; ?>

		                <?php /*<?php echo Html::link(Yii::t('site', 'Increase Package'), array('e_services/index')); ?><br /> */ ?>
		                <?php /*echo Html::link(Yii::t('site', 'Check offers'), array('offers/index')); ?><br /> */?>
						<?php echo Html::link(Yii::t('site', 'My profile'), array('user/profile')); ?><br />
						<?php echo Html::link(Yii::t('navigation', 'Packages'), array('packages/comparison')); ?><br />
						<?php echo Html::link(Yii::t('navigation', 'Payments'), array('packages/history')); ?><br />
						<?php /*<?php echo Html::link(Yii::t('site', 'E-services'), array('e_services/index')); ?>*/ ?>
						
					</div>
				<?php endif; ?>
				
				<?php $this->widget('AdBox', array(
					'groupId'=>'right-4',
				)); ?>
				
				<?php $this->widget('AdBox', array(
					'groupId'=>'right-2',
				)); ?>
				
				<?php $this->widget('AdBox', array(
					'groupId'=>'right-3',
				)); ?>       
			</div>
		</div>
    </div>
</div>

<div class="row">
    <div class="span3">
        <?php $this->widget('AdBox', array(
            'groupId'=>'bottom-1',
        )); ?>
    </div>
    
    <div class="span3">
        <?php $this->widget('AdBox', array(
            'groupId'=>'bottom-2',
        )); ?>
    </div>
    
    <div class="span3">
        <?php $this->widget('AdBox', array(
            'groupId'=>'bottom-3',
        )); ?>
    </div>
    
    <div class="span3">
        <?php $this->widget('AdBox', array(
            'groupId'=>'bottom-4',
        )); ?>
    </div>
</div>