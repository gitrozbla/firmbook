<?php 
/**
 * Usługa na liście.
 *
 * @category views
 * @package service
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php 
	$url = $this->createGlobalRouteUrl('services/show', array('name'=>$data->alias));
	$updateUrl = $this->createGlobalRouteUrl('services/update', array('id'=>$data->id)); 
	//$updateUrl = $this->createGlobalRouteUrl('services/update', array('name'=>$data->alias));
	$addToFavoriteUrl = $this->createUrl('elists/add', array('id'=>$data->id, 'type'=>Elist::TYPE_FAVORITE));
	$addToElistUrl = $this->createUrl('elists/add', array('id'=>$data->id, 'type'=>Elist::TYPE_ELIST));
?>

<li<?php if(!isset($companyPage)) : ?><?php echo ' class="'.$data->getPackageItemClass().'"'; ?><?php endif; ?>>
	<?php if($this->id == 'categories' && $this->action->id == 'show') : ?>	
		<div class="inline column-checkbox">
		<?php if(isset($data->service->company)	&& $data->service->company->map_lat && $data->service->company->map_lng) : ?>
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
					//'itemType'=>$data->cache_type,
					'favoriteBtnStyle'=> in_array($data->id, $elistItems['favorite']) ? 'success' : '',
					'elistBtnStyle'=> in_array($data->id, $elistItems['elist']) ? 'success' : '',				 
				)); ?>
			</div>	
		<?php endif; ?>	
        <?php if (Yii::app()->user->checkAccess('Services.update', array('record'=>$data))) : ?>
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
                                        '/services/remove',
                                        array('name'=>$data->alias)
                                        ),
                                'type' => 'danger',
                                'icon' => 'fa fa-times',
                                'htmlOptions' => array(
                                    'class' => 'confirm-box',
                                    'data-message' => Yii::t(
                                            'services', 
                                            '{remove-service?}', 
                                            array(
                                                '{remove-service?}' => 
                                                'Are you sure you want to remove this service? '
                                                . 'You can also temporarily hide this offer '
                                                . 'by clicking \'inactive\' on service page.'
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
                        ? $data->name
                        : '('.Yii::t('item', 'No name').')',
                    $url
                ); ?><br/>
            <?php if (!$data->active) :?>
                <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
            <?php endif; ?>
        </h2>
        <?php if(isset($data->service->company)): ?>    
            <i class="fa fa-building-o"></i>    
            <?php
//                if(isset($data->product->company)) 
                    echo Html::link(
                        $data->service->company->item->name, 
                        $this->createGlobalRouteUrl('companies/show', array(
                            'name' => $data->service->company->item->alias))
                        );
                    ?>
            <?php if(isset($data->service->company->city)): ?>
                <br>
                <small><?php echo $data->service->company->city ?></small>
            <?php endif;?>
            <br>     
        <?php endif;?>
        <?php if($data->service->price): ?>
	        <?php 
	        	if($data->service->promotion)
	        		$labelType = 'warning';
	        	else
	        		$labelType = 'success';	
	        ?>        
			<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => $labelType,
						'label' => $data->service->price
							. ($data->service->promotion ? ' ' . Yii::t('item', 'DISCOUNT') : '')
							//.' '.$data->service->currency->name
					)); ?>			
		<?php endif;?>
        <br>
        <?php /*<div class="nowrap inline">
            <?php $this->widget('LikeButton',
                array('post_type'=>'item', 'post_id'=>$data->id, 'clickable'=>false)); ?>
        </div>
        &nbsp;&nbsp;&nbsp; 
         */ ?>  
        <div class="nowrap inline">
            <span><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $data->view_count ?></span>
        </div>  
        <?php if(isset($data->service->company)): ?>
            <?php $this->widget('BusinessReliableCompany', array('display' => $data->service->company->business_reliable, 'asBadge'=>true, 'inline' => true)) ?>    
        <?php endif;?>
    </div>
    <div class="inline column-contact">
        
    </div>
</li>

<?php /*<hr />*/ ?>