<?php 
/**
 * Strona produktu i usługi !!!
 *
 * @category views
 * @package product
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
    $class = get_class($product);
    $source = strtolower($class);
    $controller = $source.'s'; 
    
    $item = $product->item;
    $editor = $this->editorEnabled; 
    //$editor = Yii::app()->session['editor'];
    $updateUrl = $this->createGlobalRouteUrl($controller.'/update', array('id'=>$item->id));
    //$updateUrl = $this->createGlobalRouteUrl('companies/update', array('id'=>$item->id));
    $itemUpdateUrl = $this->createGlobalRouteUrl($controller.'/partialupdate', array('model'=>'Item'));
    $fileUpdateUrl = $this->createGlobalRouteUrl($controller.'/partialupdate', array('model'=>'UserFile'));
    //$productUpdateUrl = $this->createGlobalRouteUrl($controller.'/update', array('model'=>'Product'));
    $user = $item->user;
    $company = $product->company;    
    
?>

<div class="row">
	<div class="span3">
        <?php $this->renderPartial('/user/_userBox', compact('user')); ?>
		
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>
        
        <?php if (!$this->creatorsMode) {
			 $this->renderPartial('/site/_qrCode', array(
				'data' => Yii::app()->createAbsoluteUrl('companies/show', array('name'=>$item->alias)),
				'filename' => $item->id,
				'class' => 'Item',
				'id' => $item->id
			));
		} ?>
    </div>
	
    <div class="span9">
    	<?php if($editor): ?>
    	<div class="editor-button pull-right">
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    //'type' => 'primary',
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('editor', 'Edit'),
                	'url' => $updateUrl,
                    //'url' => $this->createUrl('companies/update'),
                    //'active' => $editor,
                    'type' => 'success',                    
                )
        ); ?>
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(                    
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('product', 'Edit gallery'),
                	'url' => $this->createGlobalRouteUrl($controller.'/edit_gallery', array('name'=>$item->alias)),                    
                    'type' => 'success',                    
                )
        ); ?>
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(                    
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('attachment', 'Add attachment'),
                	'url' => $this->createGlobalRouteUrl('attachments/add', array('name'=>$item->alias)),
                	//'url' => $this->createUrl($controller.'/add_attachment', array('name'=>$item->alias)),                    
                    'type' => 'success',                    
                )
        ); ?>         
    	</div>
    	<div class="clearfix"></div>
    	<?php endif;?>
    	<div class="page-header">
			<h1><i class="fa <?php echo $source == 'product' ? 'fa-shopping-cart' : 'fa-truck'; ?>"></i> <?php echo $product->item->name; ?> <small><?php echo $product->signature; ?></small></h1>
		</div>
		<?php $box = $this->beginWidget(
		    'bootstrap.widgets.TbBox',
		    array(
		        //'title' => Yii::t('product', 'Description'),
		        'headerIcon' => 'fa fa-paperclip',
		        'htmlOptions' => array('class' => 'transparent'),
		        'htmlHeaderOptions' => array('class' => $user->getPackageItemClass(), 'style' => 'display:none;'),
		    )
		);?>
	    <?php /*$box = $this->beginWidget(
	        'bootstrap.widgets.TbBox',
	        array(
	            'title' => Html::link(
	                    $product->item->name, 
	                    $this->createUrl('companies/show', array(
	                        'name' => $product->item->alias))
	                    ),
	            'headerIcon' => 'fa fa-building-o',
	            'htmlOptions' => array('class' => 'transparent'),
	            'htmlHeaderOptions' => array('class' => $product->item->getPackageItemClass()),
	        )
	    );*/?>
    	<div class="span4 bottom-20" style="margin-left: 0">
    	<?php /*(if ($this->editorEnabled || $item->thumbnail != null) : */ ?>
    	<?php /*if ($this->editorEnabled) :*/ ?>    	
            <!-- <div class="thumbnail pull-left right-30 bottom-10"> -->
            <div class="text-center bottom-10">
                <?php $this->widget('EditableImage', array(
                    'model'     => $item,
                    'attribute' => 'thumbnail_file_id',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                	'url'       => $itemUpdateUrl,
                    //'url'       => $itemUpdateUrl,
                    'apply'     => $this->editorEnabled,
                )); ?>
                
            </div>
        <?php if (!$this->creatorsMode) : ?>
	        <hr>
	        <div class="text-center">
                <?php /*
                <div class="nowrap inline">
                    <?php echo Yii::t('item', 'likes'); ?>
                    <?php // $this->widget('likedislike.widgets.LikeDislikeButton',
//                        array('post_type'=>'item', 'post_id'=>$item->id)); ?>
                    <?php $this->widget('LikeButton',
                        array('post_type'=>'item', 'post_id'=>$item->id)); ?>
                </div>
                &nbsp;&nbsp;&nbsp;*/?>
                <div class="nowrap inline">
                    <?php echo Yii::t('item', 'views'); ?>
                    <span><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $item->view_count ?></span>
                </div>
            </div>
			
            <?php if (!empty($item->www) && PackageControl::getValue($item->cache_package_id, 'package_service_41')) {
                echo '<div class="text-center">'
                        . Html::link($item->www, $item->www, array('target' => '_blank'))
                    .'</div>';
            }; ?>
		<?php endif; ?>
        <?php /*elseif ($product->item->thumbnail_file_id): ?>
            <div class="text-center bottom-10">
                <?php echo Html::image(
                            $product->item->thumbnail->generateUrl('medium'),
                            $product->item->name); ?>
            </div>
        <?php endif;*/ ?>
        </div>
		<?php //$this->endWidget(); ?>
		
		<div class="span4">

			<?php if (!$this->creatorsMode) : ?>
				<div class="pull-right"><?php echo $item->badge2(); ?></div>

				<?php if ($item->active) : ?>
    				<?php $this->renderPartial('/site/_socialButtons', array(
                            'title' => $item->name,
    				    	'url' => Yii::app()->createAbsoluteUrl('products/show', array('name'=>$product->item->alias)),
                            'description' => $product->short_description
    			    	)); ?>
                <?php endif; ?>
                <?php if ($product->allegro) : ?>
            <br />
            <div class="text-center">
                <a href="<?php echo $product->allegro_link; ?>" target="_blank">
                    <img src="images/allegro.png" alt="Sprzedaję na Allegro">
                </a>
            </div>
        <?php endif; ?>
        <?php /*if($product->allegro): ?>
            <?php $this->widget(
                'Button',
                array(
                    'buttonType' => 'link',
                    'label' => Yii::t('product', 'Product on allegro'),
                    'url' => $this,
                    'htmlOptions' => [
                        'style' => 'color: #db3f1d; font-weight: bold',
                        'target'=>'_blank'
                    ]
                ) 
                ); ?>                  
        <?php endif;*/ ?>                
				<div class="clearfix"></div>
				<?php if(!Yii::app()->user->isGuest) : ?>
					<div class="pull-right bottom-10">
					<?php $this->renderPartial('/elists/_addToElistButtons',
						array(
							'itemId'=>$item->id,
							'recipientType'=>$item->cache_type,
							//'itemType'=>$item->cache_type,
							//'favoriteBtnStyle'=> 1 ? 'success' : '',
							'favoriteBtnStyle'=> Elist::model()->exists(
								'user_id=:user_id and item_id=:item_id and type=:type',
								array(':user_id'=>Yii::app()->user->id, ':item_id'=>$item->id, ':type'=>Elist::TYPE_FAVORITE))
								? 'success' : '',
							'elistBtnStyle'=> Elist::model()->exists(
								'user_id=:user_id and item_id=:item_id and type=:type',
								array(':user_id'=>Yii::app()->user->id, ':item_id'=>$item->id, ':type'=>Elist::TYPE_ELIST))
								? 'success' : '',
						)); ?>
					</div>
				<?php endif;	// !isGuest ?>
				<div class="clearfix"></div>
				<hr>
				<div class="pull-right bottom-10">
					<?php $this->renderPartial('/elists/_resourceOnElistsBox', array(
                    		'item' => $item,
							'itemType' => Elist::ITEM_TYPE_ITEM
                    )) ?>
				</div>
			<?php endif;	// !creatorsMode ?>
		<?php $labelW = $this->widget('bootstrap.widgets.TbLabel',
			array(
				'type' => 'warning',
				'label' => $product->price				
			), true); ?>
		<?php 
			//dane do TbDetailView   
    		$tdvAttributes = array();
    		if($product->price)
	    		$tdvAttributes[] = array(
	    			'name'=>'price', 
	    			'value'=> $this->widget('bootstrap.widgets.TbLabel',
						array(
							'type' => $product->promotion ? 'warning' : 'success',
							'label' => $product->price				
						), true).' '.(isset($product->currency->name) ? $product->currency->name : ''), 
						//.' <br/>'.Yii::t('common', 'for').($product->unit ? ' '.Yii::t('dictionary', $product->unit->name) : ''), 
					'type' => 'raw'
				);
			if($product->promotion && $product->promotion_price) {
// 			if($product->promotion && $product->promotion_price && $product->currency) {				
	    		$tdvAttributes[] = array(
	    			'name'=>'promotion_price', 
	    			'value'=>$this->widget('bootstrap.widgets.TbLabel',
						array(
							//'type' => 'success',
							'label' => $product->promotion_price				
						), true).' '.(isset($product->currency->name) ? $product->currency->name : ''),
						'type' => 'raw'
				);
	    		$tdvAttributes[] = array(
	    				'name'=>'discount',
	    				'value'=>$this->widget('bootstrap.widgets.TbLabel',
	    						array(
	    								'type' => 'success',
	    								'label' => $product->discount.'%'
	    						), true),
	    				'type' => 'raw'
	    		);
				if($product->promotion_expire)
	    			$tdvAttributes[] = array('name'=>'promotion_expire', 'value'=>$product->promotion_expire);
			}
			if($source == 'product') {
				if($product->delivery_free)
					$tdvAttributes[] = array('name'=>'delivery_free', 'value'=>Yii::t('common', 'yes'));				
				if($product->delivery_price) 
					$tdvAttributes[] = array('name'=>'delivery_price', 'value'=>$product->delivery_price.' '.$product->currency->name);	
				if($product->delivery_min) 
					$tdvAttributes[] = array('name'=>'delivery_min', 'value'=>$product->delivery_min);	
				if($product->delivery_time) 
					$tdvAttributes[] = array('name'=>'delivery_time', 'value'=>$product->delivery_time.' '.Yii::t('common', 'days'));	
			}
		?>	
    	<?php $this->widget(
		    'bootstrap.widgets.TbDetailView',
		    array(
			    'data' => $product,	    	
		    	'htmlOptions' => array(
		    		//'style' => 'width: 700px',
					'class' => $this->creatorsMode ? 'creators-table' : '',
		    	),
		    	'attributes' => $tdvAttributes	    	
		    )
    	);
		?>
        
		<?php if($product->price) :?>
		<?php $this->widget(
				    'Button',
				    array(				    	
				    	'buttonType' => 'link',
					    'label' => Yii::t('company', 'Currency converter'),
					    //'type' => 'success',
					    'url'=> 'http://www.money.pl/pieniadze/kalkulator/',
				    	'icon' => 'fa fa-bar-chart-o',
					    'htmlOptions' => array(						    
				    		'style'=>'margin:5px',
					    	'target'=>'_blank'	
				    		//'title' => Yii::t('company', 'Currency converter'), 				    			    	
				    	),				    	
				    )
			    ); ?>
		<?php $this->widget(
				    'Button',
				    array(				    	
				    	'buttonType' => 'link',
					    'label' => Yii::t('company', 'Comparison courier services'),
					    //'type' => 'success',
					    'url'=> 'http://www.znajdzkuriera.pl/',
				    	'icon' => 'fa fa-bar-chart-o',
					    'htmlOptions' => array(						    
				    		'style'=>'margin:5px',
					    	'target'=>'_blank'
				    		//'title' => Yii::t('company', 'Comparison courier services'), 				    			    	
				    	),				    	
				    )
			    ); ?>	
			    
		<?php $this->widget(
				    'Button',
				    array(				    	
				    	'buttonType' => 'link',
					    'label' => Yii::t('company', 'Exchange rate'),
					    //'type' => 'success',
					    'url'=> 'http://www.nbp.pl/home.aspx?f=%2Fkursy%2Fkursya.html',
				    	'icon' => 'fa fa-bar-chart-o',
					    'htmlOptions' => array(						    
				    		'style'=>'margin:5px',
					    	'target'=>'_blank'
				    		//'title' => Yii::t('company', 'Comparison courier services'), 				    			    	
				    	),				    	
				    )
			    ); ?>		        
    	
    	<?php endif; ?>
    	
    	</div>
    	<div class="clearfix"></div>       
				
    	<?php
            $tabs = array(
                array (
                    'label' => Yii::t ('product', 'Description'),
                    'content' => $item->description,
                    'active' => true
                )
            );

            if (!empty($item->files)) {
                $tabs []= array (
                    'label' => Yii::t ('product', 'Gallery'),
                    'content' => $this->widget('EditableGallery', array(
                            'model'     => $item,
                            'attribute' => 'files',
                            'imageSize' => 'medium',
                            'imageAlt'  => $item->name,
                            'url'       => $fileUpdateUrl,
                            'apply'     => false
                            //'apply'     => $this->editorEnabled
                        ), true)
                );
            }

            if (!empty($item->youtube)) {
                $tabs []= array(
                    'label' => Yii::t('item', 'Video'),
        			'content' => '<div class="video-container">'
//                        . '<iframe src="http://www.youtube.com/embed/'.$item->youtube.'" '
//                            . 'frameborder="0" allowfullscreen></iframe>'
                        . Html::embedYoutube($item->youtube)
                        . '</div>'
                );
            }

            if (!empty($item->attachments)) {
                $tabs []= array(
                    'label' => 	Yii::t ('attachment', 'Files'),
                    'content' => $this->renderPartial('/attachments/_attachmentList', compact('item'), true)
                );
            }

		    $this->widget(
			    'bootstrap.widgets.TbTabs',
			    array(
			    	'type' => 'tabs', // 'tabs' or 'pills'
			    	'htmlOptions' => array('style' => 'margin-top: 20px'),
			    	'tabs' => $tabs
			    )
		    );
		?>
		<?php /*echo $item->description; ?>
		<?php $this->widget('EditableGallery', array(
                    'model'     => $item,
                    'attribute' => 'files',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                    'url'       => $fileUpdateUrl,
                    'apply'     => $this->editorEnabled
                ));*/ ?>
		<?php $this->endWidget(); ?>
		
		<?php if (!$this->creatorsMode) {
			$this->renderPartial('/site/_fbComments', array(
				'url' => Yii::app()->createAbsoluteUrl($controller.'/show', array('name'=>$item->id))
			));
		} ?>
    </div>
</div>
