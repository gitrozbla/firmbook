<?php 
/*
 * lewa kolumna na podstronach firmy
 */

$itemUpdateUrl = $this->createGlobalRouteUrl('companies/partialupdate', array('model'=>'Item'));
//$fileUpdateUrl = $this->createGlobalRouteUrl('companies/partialupdate', array('model'=>'UserFile'));

if(!isset($productCount))
	$productCount = Product::model()->with('item')->count(
			'company_id=:company_id and active=1',
			array(':company_id'=>$company->item_id)
	);
if(!isset($serviceCount))
	$serviceCount = Service::model()->with('item')->count(
			'company_id=:company_id and active=1',
			array(':company_id'=>$company->item_id)
	);
if(!isset($newsCount))
	$newsCount = News::model()->count(
		'item_id=:item_id and active=1',
		array(':item_id'=>$company->item_id)
);
if(!isset($moviesCount))
	$moviesCount = Movie::model()->count(
			'item_id=:item_id',
			array(':item_id'=>$company->item_id)
	);

?>

<div class="left-frame">	    
	<div class="span3 pull-left">
	
	
		<?php if (isset($user)) : ?>
			<?php $this->renderPartial('/user/_userBox', compact('user')); ?>
		<?php endif; ?>
			
	
<?php //if ($company->item->active) : ?>
    <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
            'title' => Html::link(
                    $company->item->name, 
                    $this->createGlobalRouteUrl('companies/show', array(
                        'name' => $company->item->alias))
                    ).$this->widget('BusinessReliableCompany', array('display' => $company->business_reliable, 'asBadge'=>true), true),
//            'headerIcon' => 'fa fa-building-o',
            'headerIcon' => '',
            'htmlOptions' => array('class' => 'transparent fixed-width-icon'),
        	'htmlHeaderOptions' => array(
        			'class' => 'package-item',
        			'style' => $company->item->getItemStyle()
        	)        	
            //'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),
        )
    );?>
    	<?php /*(if ($this->editorEnabled || $item->thumbnail != null) : */ ?>
    	<?php if ($this->editorEnabled || !empty($item->files)) : ?>
            <!-- <div class="thumbnail pull-left right-30 bottom-10"> -->
            <div class="thumbnail text-center bottom-10">
                <?php $this->widget('EditableImage', array(
                    'model'     => $item,
                    'attribute' => 'thumbnail_file_id',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                    'url'       => $itemUpdateUrl,
                    'apply'     => $this->editorEnabled,
                )); ?>
                <?php /*$this->widget('EditableGallery', array(
                    'model'     => $item,
                    'attribute' => 'files',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                    'url'       => $fileUpdateUrl,
                    'apply'     => $this->editorEnabled
                ));*/ ?>
            </div>
            
        <?php /*elseif ($company->item->thumbnail_file_id): ?>
            <div class="text-center bottom-10">
                <?php echo Html::link(
                    Html::image(
                            $company->item->thumbnail->generateUrl('medium'),
                            $company->item->name), 
                    $this->createGlobalRouteUrl('companies/show', array(
                        'name' => $company->item->alias))
                    ); ?>
            </div>*/?>
        <?php endif; ?>
        <?php if ($company->short_description): ?>
            <p>
                <?php echo $company->short_description; ?>
            </p>
            
        <?php endif; ?>
        <?php if (!$this->creatorsMode) : ?>
            <hr />
            <div class="bottom-10">
                <?php /*
                <div class="nowrap inline">
                    <?php echo Html::link(
                        Yii::t('item', 'likes'),
                        $this->createUrl('/likedislike/default/inverselist', array(
                            'id' => $item->id))); 
                    ?>
                    <?php $this->widget('likedislike.widgets.LikeDislikeButton',array('post_type'=>'item', 'post_id'=>$item->id)); ?>
                </div>
                &nbsp;&nbsp;&nbsp;*/?>
                <div class="nowrap inline">                    
                    <span><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $item->view_count ?></span>
                        <?php echo Yii::t('item', 'views'); ?>
                </div>
                <?php if (!$this->creatorsMode) : ?>    	        
    	        <div>
                    <?php $this->renderPartial('/elists/_resourceOnElistsBox', array(
                    		'item' => $item,
                    		'itemType' => Elist::ITEM_TYPE_ITEM
                    )) ?>
    				
    			</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (false && !$this->creatorsMode) : ?>
            <hr />
            <div class="bottom-10 text-center">
                <?php /*
                <div class="nowrap inline">
                    <?php echo Html::link(
                        Yii::t('item', 'likes'),
                        $this->createUrl('/likedislike/default/inverselist', array(
                            'id' => $item->id))); 
                    ?>
                    <?php $this->widget('likedislike.widgets.LikeDislikeButton',array('post_type'=>'item', 'post_id'=>$item->id)); ?>
                </div>
                &nbsp;&nbsp;&nbsp;*/?>
                <div class="nowrap inline">
                    <?php echo Yii::t('item', 'views'); ?>
                    <span><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $item->view_count ?></span>
                </div>
            </div>
        <?php endif; ?>
		
		<?php if(!Yii::app()->user->isGuest && !$this->creatorsMode) : ?>
			<hr />
			<div class="bottom-10 text-center">	
			<span>
			<?php $this->renderPartial('/elists/_addToElistButtons', 
				array(
					'itemId'=>$item->id,
					//'itemType'=>$item->cache_type,
					'followItemType'=>Follow::ITEM_TYPE_COMPANY,
					'recipientType'=>$item->cache_type,
					//'favoriteBtnStyle'=> 1 ? 'success' : '',
					'favoriteBtnStyle'=> Elist::model()->exists(
						'user_id=:user_id and item_id=:item_id and type=:type',
						array(':user_id'=>Yii::app()->user->id, ':item_id'=>$item->id, ':type'=>Elist::TYPE_FAVORITE)) 
						? 'success' : '',
					'elistBtnStyle'=> Elist::model()->exists(
						'user_id=:user_id and item_id=:item_id and type=:type',
						array(':user_id'=>Yii::app()->user->id, ':item_id'=>$item->id, ':type'=>Elist::TYPE_ELIST)) 
						? 'success' : '',				 
					'followBtnStyle'=> Follow::model()->exists(
						'user_id=:user_id and item_id=:item_id and item_type=:iten_type',
						array(':user_id'=>Yii::app()->user->id, ':item_id'=>$item->id, ':iten_type'=>Follow::ITEM_TYPE_COMPANY))
						? 'success' : '',
				)); ?>
			</span>		
			</div>							   
		<?php endif; ?>
        <hr />
		<?php if ($company->item->www && PackageControl::getValue($company->item->cache_package_id, 'package_service_41')): ?>
            <i class="fa fa-external-link"></i> 
            <?php $this->widget(
                    'application.components.widgets.TextOverflowScroll', 
                    array(
                        'text' => Html::link(str_replace(array('http://', 'https://'), '', $item->www), 
                        			$item->www,
                        			array('target' => '_blank'))
                    )
            ); ?><br />
        <?php endif; ?>
        
		<?php if (!$company->hide_email && $company->email): ?>
            <i class="fa fa-envelope"></i>
            <?php $this->widget(
                    'application.components.widgets.TextOverflowScroll',
                    array(
                        'text' => Html::link($company->email, 'mailto:'.$company->email),
                    )
            ); ?>

            <?php
                $emails = array();
                for($i=2; $i<=5; $i++) {
                    $attribute = 'email' . $i;
                    if ($company->$attribute) {
                        $emails []= '<i class="fa"></i> '
                            . /*Html::link(
                                $company->$attribute,
                                'mailto:' . $company->$attribute
                                )*/
                            $this->widget(
                                'application.components.widgets.TextOverflowScroll',
                                array(
                                    'text' => Html::link($company->$attribute, 'mailto:'.$company->$attribute),
                                ),
                                true
                            );
                    }
                }
                if (!empty($emails)) {
                    echo '<a class="expand-button" href="#">(...)<br /></a>'
                        . '<div style="display:none">'
                            . implode('<br />', $emails)
                        . '</div>';
                } else {
                    echo '<br />';
                }
            ?>

        <?php endif; ?>

        <?php if ($company->phone): ?>
            <i class="fa fa-phone"></i>
            <?php echo Html::link(
                    $company->phone,
                    'tel:'.str_replace(array(' ', '-', '&nbsp;'), '', $company->phone)
                    ); ?>

            <?php
                $phones = array();
                for($i=2; $i<=5; $i++) {
                    $attribute = 'phone' . $i;
                    if ($company->$attribute) {
                        $phones []= '<i class="fa"></i> '
                            . Html::link(
                                $company->$attribute,
                                'tel:'.str_replace(array(' ', '-', '&nbsp;'), '', $company->$attribute)
                                );
                    }
                }
                if (!empty($phones)) {
                    echo '<a class="expand-button" href="#">(...)<br /></a>'
                        . '<div style="display:none">'
                            . implode('<br />', $phones)
                        . '</div>';
                } else {
                    echo '<br />';
                }
            ?>

        <?php endif; ?>        
        <?php if ($item->facebook_profile): ?>
        	<?php $facebookLink = $item->getFacebook_profile_link();?>
            <i class="fa fa-facebook-square"></i> 
            <?php $this->widget(
                    'application.components.widgets.TextOverflowScroll', 
                    array(
                        'text' => Html::link(Yii::t('item', 'see profile on Facebook'), 
                        			$facebookLink,
                        			array('target' => '_blank'))
                    )
            ); ?><br />
        <?php endif; ?>
        <?php if ($company->youtube): ?>        	
            <i class="fa fa-youtube-square"></i> 
            <?php $this->widget(
                'application.components.widgets.TextOverflowScroll', 
                array(
                    'text' => Html::link(Yii::t('company', 'see channel on Youtube'), 
                    $company->getYoutube_link(),
                    array('target' => '_blank'))
                )
            ); ?><br />    
        <?php endif; ?>
        
		<br />

		<?php if ($item->facebook): ?>
            <?php /*<i class="fa fa-facebook"></i>
            <?php echo Html::link(
                Yii::t('item', 'see profile on Facebook'),
                $item->getFacebook_link(),
                array('target' => '_blank')
            ); ?><br />*/ ?>

            <?php $facebookLink = $item->getFacebook_link(); ?>
            <div class="text-center facebook-badge-wrapper">
                <div class="fb-page" data-href="<?php echo $facebookLink; ?>"
                        data-width="180" data-small-header="false"
                        data-adapt-container-width="true" data-hide-cover="false"
                        data-show-facepile="true">
                    <blockquote cite="<?php echo $facebookLink; ?>" class="fb-xfbml-parse-ignore">
                    <a href="<?php echo $facebookLink; ?>"><?php echo _($item->name); ?></a>
                    </blockquote>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($item->facebook_profile && false): ?>  
        	<?php /*<div class="fb-page" 
  data-href="https://www.facebook.com/profile.php?id=100002596300213"
  data-width="380" 
  data-hide-cover="false"
  data-show-facepile="false"></div>  
        	<div class="fb-page" data-href="https://www.facebook.com/profile.php?id=100002596300213" data-tabs="timeline" data-width="180" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
        	*/?>            
        <?php endif; ?>


        <?php if (false && $item->google_plus): ?>
            <?php /*<i class="fa fa-google-plus"></i>
            <?php echo Html::link(
                Yii::t('item', 'see profile on Google+'),
                $item->getGoogle_plus_link(),
                array('target' => '_blank')
            ); ?><br />*/ ?>

            <?php /*<script src="https://apis.google.com/js/platform.js" async defer>
                {lang: '<?php echo Yii::app()->language; ?>'}
            </script>
            <div class="text-center google-plus-badge-wrapper">
                <div class="g-page" data-width="180"
                    data-href="//plus.google.com/u/0/<?php echo $item->google_plus; ?>"
                    data-rel="publisher"></div>
            </div>*/ ?>
        <?php endif; ?>
        
        <?php /*if ($company->origin): ?>
            <?php echo CHtml::image('images/flag_icons/'.strtolower($company->country).'.gif',
            	'',
            	array(
            		'title' => Yii::t('country.name', $company->origin->name, null, 'dbMessages'),
            	)) ?> 
            <?php $this->widget(
                    'application.components.widgets.TextOverflowScroll', 
                    array(
                        'text' => Yii::t('country.name', $company->origin->name, null, 'dbMessages'),
                    )
            ); ?><br />
        <?php endif;*/ ?>
		
		<?php if ($company->allegro) : ?>
            <br />
            <div class="text-center">
                <a href="<?php echo $company->allegro_link; ?>" target="_blank">
                    <img src="images/allegro.png" alt="Sprzedaję na Allegro">
                </a>
            </div>
        <?php endif; ?>
        
		
		
    <?php $this->endWidget(); ?>
<?php //endif; ?>
        <?php /*if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company', 'item', 'updateUrl', 'editor', 'itemUpdateUrl')); ?>
        <?php endif;*/ ?>       
    
	    	
            <div class="clearfix"></div>
        	<?php /*//endif; ?>
		    <div class="smart-box">
			    <div class="title">Kategorie</div>
			    <ul>
					<li><a href="http://www.etotu.com/company/2/products?categoryId=1939" class=""> Mung Beans </a></li>
					<li><a href="http://www.etotu.com/company/2/products?categoryId=263" class=""> Other Agriculture Products </a></li>
				</ul>
			</div>
			<?php */?>	
			<?php
				$offerItems = array(); 
				$badge = $this->widget(
				    'bootstrap.widgets.TbBadge',
				    array(
					    'type' => 'info',					
					    'label' => $productCount,
				    	'htmlOptions' => array('style' => 'margin-left: 10px;'),				    
					), 
					true
				);
				$offerItems[] = array(                            	
                                'label' => '<i class="fa fa-shopping-cart"></i> '
                                        .Yii::t('company', 'Products').' '.$badge,
                                'url' => $this->createGlobalRouteUrl('/companies/offer/', array(
								        'name' => $item->alias,
                                        'type' => 'product')),
                                /*'badge' => array(
		                                'label' => '10',
		                                'type' => 'info',
                                ),*/        
                            );
				/*if($this->editorEnabled)   
					$offerItems[] = array(                            	
                                'label' => '<i class="fa fa-plus"></i> '
                                        .Yii::t('desktop', 'Add new product'),
                                'url' => $this->createGlobalRouteUrl('/products/add',
        							array('company'=>$company->item_id)),        					
                            );*/  
                $badge = $this->widget(
				    'bootstrap.widgets.TbBadge',
				    array(
					    'type' => 'info',					
					    'label' => $serviceCount,
				    	'htmlOptions' => array('style' => 'margin-left: 10px;'),				    
					), 
					true
				);                                    
				$offerItems[] = array(
                                'label' => '<i class="fa fa-truck"></i> '
                                        .Yii::t('company', 'Services').' '.$badge,
                                'url' => $this->createGlobalRouteUrl('/companies/offer/', array(
								        'name' => $item->alias,
                                        'type' => 'service')),
                            );  
                /*if($this->editorEnabled) 
                	$offerItems[] = array(                            	
                                'label' => '<i class="fa fa-plus"></i> '
                                        .Yii::t('desktop', 'Add new service'),
                                'url' => $this->createGlobalRouteUrl('/services/add',
        							array('company'=>$company->item_id)),
                            );*/
				$badge = $this->widget(
						'bootstrap.widgets.TbBadge',
						array(
								'type' => 'info',
								'label' => $newsCount,
								'htmlOptions' => array('style' => 'margin-left: 10px;'),
						),
						true
				);
				$offerItems[] = array(
						'label' => '<i class="fa fa-file-text-o"></i> '
						.Yii::t('news', 'News').' '.$badge,
						'url' => $this->createGlobalRouteUrl('/news/list/', array(
								'name' => $item->alias)),
				);
				
				
				/*$imgCount = UserFile::model()->count(
						'company_id=:company_id and active=1',
						array(':company_id'=>$company->item_id)
				);*/
				$imgCount = count($item->files);
				$badge = $this->widget(
						'bootstrap.widgets.TbBadge',
						array(
								'type' => 'info',
								'label' => $imgCount,
								'htmlOptions' => array('style' => 'margin-left: 10px;'),
						),
						true
				);
				$offerItems[] = array(
						'label' => '<i class="fa fa-picture-o"></i> '
						.Yii::t('company', 'Gallery').' '.$badge,
						'url' => $this->createGlobalRouteUrl('/companies/gallery/', array(
								'name' => $item->alias)),
				);
				
				
				$badge = $this->widget(
						'bootstrap.widgets.TbBadge',
						array(
								'type' => 'info',
								'label' => $moviesCount,
								'htmlOptions' => array('style' => 'margin-left: 10px;'),
						),
						true
				);
				$offerItems[] = array(
						'label' => '<i class="fa fa-film"></i> '
						.Yii::t('company', 'Movies').' '.$badge,
						'url' => $this->createGlobalRouteUrl('/movies/list/', array(
								'name' => $item->alias)),
				);
			?>
			
			
			<?php $box = $this->beginWidget(
				'bootstrap.widgets.TbBox',
				array(
					'title' => Yii::t('categories', 'Categories'),
					'htmlHeaderOptions' => array(
							'class' => 'package-item',
							'style' => $company->item->getItemStyle()
					)
					//'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),
				)
			);?>
				<strong><?php echo $item->category->nameLocal; ?></strong>
				<?php if (!empty($item->additionalCategories)) {
					echo '<hr />'.Yii::t('common', 'and').': ';
					$categories = array();
					foreach($item->additionalCategories as $category) {
						$categories []= $category->nameLocal;
					}
					echo implode(', ', $categories);
				} ?>
                <br>
                <?php // echo Html::link($item->category->nameLocal, $url); ?>
			<?php $this->endWidget(); ?>
			
			
			<?php $this->widget(
            'bootstrap.widgets.TbBox',
            array(
                'title' => Yii::t('company', 'Offer'),
                'htmlOptions' => array(
                		'class' => 'fixed-menu',
                    //'class' => 'widget-box fixed-menu',
                ),
                'content' => $this->widget(
                    			'bootstrap.widgets.TbMenu',
			                    array(
			                        'encodeLabel' => false,
			                        'items' => $offerItems,                    	   	
			                    ),
			                    true
			                ),
            	'htmlHeaderOptions' => array(
            			'class' => 'package-item',
            			'style' => $company->item->getItemStyle()
            	),
                //'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),
                'headerIcon' => 'fa fa-shopping-cart',
            )
        ); ?>


            <?php if (!$this->creatorsMode) {
				$this->renderPartial('/site/_qrCode', array(
	    		'data' => Yii::app()->createAbsoluteUrl('companies/show', array('name'=>$item->alias)),
    		        'filename' => $item->id,
    		        'class' => 'Item',
    		        'id' => $item->id
		    	));
			} ?>			
			
		</div>
	</div>