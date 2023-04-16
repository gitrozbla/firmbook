<?php 
/**
 * Produkt na liście.
 *
 * @category views
 * @package product
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php 
	$url = $this->createGlobalRouteUrl('products/show', array(
    	'name'=>$data->alias)); //Yii::t('item-'.$data->id, $data->alias, array(), 'dbMessages'))); 
	$updateUrl = $this->createGlobalRouteUrl('products/update', array('id'=>$data->id));
	$addToFavoriteUrl = $this->createUrl('elists/add', array('id'=>$data->id, 'type'=>Elist::TYPE_FAVORITE));
	$addToElistUrl = $this->createUrl('elists/add', array('id'=>$data->id, 'type'=>Elist::TYPE_ELIST));
?>

<li<?php if(!isset($companyPage)) : ?><?php echo ' class="'.$data->getPackageItemClass().'"'; ?><?php endif; ?>>
	<?php if($this->id == 'categories' && $this->action->id == 'show') : ?>	
		<div class="inline column-checkbox">
		<?php if(isset($data->product->company)	&& $data->product->company->map_lat && $data->product->company->map_lng) : ?>
			<img src="http://www.google.com/intl/en_ALL/mapfiles/marker<?php echo Yii::app()->params['google']['map']['markers'][$markerIndexes[$data->id]] ?>.png" alt=""/>
			<br />
			<?php echo CHtml::checkBox('checkboxList', false, array('class'=>'marker', 'id'=>'mid_'.$data->id)) ?>
		<?php endif; ?>	
		</div>
	<?php endif; ?>
    <div class="inline column-thumbnail">
        <?php if ($data->thumbnail != null) : ?>
            <?php echo Html::link(
                    Html::image($data->thumbnail->generateUrl('small'), $data->name),
                    $url,
                    array(
                        'class' => 'thumbnail',
                        'data-content' => Html::image(
                                $data->thumbnail->generateUrl('large'), 
                                '',
                                array('style'=>'height: 200px;'))
                            .'<br />'.$data->name,
                        )); ?>
        <?php endif; ?>
        
    </div>
    	<div class="inline column-title">
    	<?php if(!Yii::app()->user->isGuest && !$this->creatorsMode) : ?>
    		<div class="pull-right bottom-10">
    		<?php $this->renderPartial('/elists/_addToElistButtons', 
				array(
					'itemId'=>$data->id,
					//'recipientType'=>$data->cache_type,
					//'itemType'=>$data->cache_type,
					//'followItemType'=>Follow::ITEM_TYPE_PRODUCT,
					'favoriteBtnStyle'=> in_array($data->id, $elistItems['favorite']) ? 'success' : '',
					'elistBtnStyle'=> in_array($data->id, $elistItems['elist']) ? 'success' : '',				 
				)); ?>
			</div>	
		<?php endif; ?>		
    	<?php /*if(!Yii::app()->user->isGuest) : ?>
    		<div class="pull-right">
                <?php $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'small',
                        'buttons' => array(
                            array(
                            	'htmlOptions' => array('id'=>'favorite-btn-'.$data->id),
                                'label' => Yii::t('common', 'Favorite'),
                                'url' => $addToFavoriteUrl,
                                'type' => in_array($data->id, $elistItems['favorite']) ? 'success' : '',
		    					'icon' => 'fa fa-heart',
                            	'buttonType' => 'ajaxLink',
                            	'ajaxOptions' => array(
                            			'dataType' => 'json',
                            			'success' => 'function(data) {					    				  
						    				$("#btn-favorite").html(data.button);  
						    				if(data.scenario)
						    					$("#favorite-btn-'.$data->id.'").addClass("btn-success");
						    				else	
						    					$("#favorite-btn-'.$data->id.'").removeClass("btn-success");						    										    				
						    			}'                           			
                            		)
                                ),
                            array(
                            	'htmlOptions' => array('id'=>'elist-btn-'.$data->id),
                                'label' => Yii::t('common', 'Elist'),
                                'url' => $addToElistUrl,
                                'type' => in_array($data->id, $elistItems['elist']) ? 'success' : '',
                                'icon' => 'fa fa-list',
                                'buttonType' => 'ajaxLink',
                            	'ajaxOptions' => array(
                            			'dataType' => 'json',
                            			'success' => 'function(data) {    								    				    			
						    				$("#btn-elist").html(data.button);	
						    				if(data.scenario)
						    					$("#elist-btn-'.$data->id.'").addClass("btn-success");
						    				else	
						    					$("#elist-btn-'.$data->id.'").removeClass("btn-success");		    				
						    			}'                           			
                            		)
                                ),
                        ),
                    )
                ); ?>
            </div>
        <?php endif; */?>	
        <?php if (Yii::app()->user->checkAccess('Products.update', array('record'=>$data))) : ?>
            <div class="pull-right clearfix">
                <?php $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'small',
                        'buttons' => array(
                            array(
                                'label' => Yii::t('editor', 'Edit'),
                                'url' => $updateUrl,
                                'icon' => 'fa fa-wrench',
                                ),
                            array(
                                'label' => Yii::t('editor', 'Remove'),
                                'url' => $this->createGlobalRouteUrl(
                                        '/products/remove',
                                        array('name'=>$data->alias)
                                        ),
                                'type' => 'danger',
                                'icon' => 'fa fa-times',
                                'htmlOptions' => array(
                                    'class' => 'confirm-box',
                                    'data-message' => Yii::t(
                                            'products', 
                                            '{remove-product?}', 
                                            array(
                                                '{remove-product?}' => 
                                                'Are you sure you want to remove this product? '
                                                . 'You can also temporarily hide this offer '
                                                . 'by clicking \'inactive\' on product page.'
                                                )),
                                )),
                        ),
                    )
                ); ?>
            </div>
        <?php endif; ?>
        <h2>
            <?php echo Html::link(
                    !empty($data->name) 
                        ? $data->name//Yii::t('item-'.$data->id, $data->name, array(), 'dbMessages')
                        : '('.Yii::t('item', 'No name').')',
                    $url
                ); ?><br/>
            <?php if (!$data->active) :?>
                <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
            <?php endif; ?>
        </h2>
        <?php if(isset($data->product->company)): ?>    
            <i class="fa fa-building-o"></i>    
            <?php
//                if(isset($data->product->company)) 
                    echo Html::link(
                        $data->product->company->item->name, 
                        $this->createGlobalRouteUrl('companies/show', array(
                            'name' => $data->product->company->item->alias))
                        );
                    ?>
            <?php if(isset($data->product->company->city)): ?>
                <br>
                <small><?php echo $data->product->company->city ?></small>
            <?php endif;?>
            <br>     
        <?php endif;?>    
        <?php if(isset($data->product->price)): ?>
	        <?php 
	        	if($data->product->promotion)
	        		$labelType = 'warning';
	        	else
	        		$labelType = 'success';	
	        ?>        
			<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => $labelType,
						'label' => $data->product->price
							. ($data->product->promotion ? ' ' . Yii::t('item', 'DISCOUNT') : '')
							//.' '.$data->product->currency->name
					)); ?>			
		<?php endif;?>
        <?php /*if($data->product->promotion_price) : ?>
        	<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => 'warning',
						'label' => $data->product->promotion_price//.' '.$data->product->currency->name				
					)); ?>
		<?php elseif($data->product->price): ?>
			<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => 'success',
						'label' => $data->product->price//.' '.$data->product->currency->name				
					)); ?>			
		<?php endif; */?>
        <br>
        <?php /*<div class="nowrap inline">
            <?php // echo Yii::t('item', 'likes'); ?>
            <?php // $this->widget('likedislike.widgets.LikeDislikeButton',
//                array('post_type'=>'item', 'post_id'=>$data->id)); ?>
            <?php $this->widget('LikeButton',
                array('post_type'=>'item', 'post_id'=>$data->id, 'clickable'=>false)); ?>
        </div>
        &nbsp;&nbsp;&nbsp;
         */ ?>   
        <div class="nowrap inline">
            <?php // echo Yii::t('item', 'views'); ?>
            <span><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $data->view_count ?></span>
        </div> 
        <?php if(isset($data->product->company)): ?>
            <?php $this->widget('BusinessReliableCompany', array('display' => $data->product->company->business_reliable, 'asBadge'=>true, 'inline' => true)) ?>    
        <?php endif;?>
    </div>
    <div class="inline column-contact">
    	<?php if($data->company): ?>
        	<?php echo CHtml::image('images/flag_icons/'.strtolower($data->company->country).'.gif',
            	'',
            	array(
            		'title' => $data->company->origin->name,
            	)
            	/*, $company->origin->name*/) ?>
        <?php endif;?>    	 
    </div>
</li>

<?php /*<hr />*/ ?>