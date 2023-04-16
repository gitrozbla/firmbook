<?php 
/**
 * Firma na liście.
 *
 * @category views
 * @package company
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php	
	/*if($data->alias != $data->id && PackageControl::getValue($data->cache_package_id, 'subdomain'))
		$url = $this->createUrl('companies/show', array('subdomain'=>$data->alias)); 
	else*/
		$url = $this->createUrl('companies/show', array('name'=>$data->alias));	
		
	$urlUpdate = $this->createUrl('companies/update', array('id'=>$data->id));	
	
	$addToFavoriteUrl = $this->createUrl('elists/add', array('id'=>$data->id, 'type'=>Elist::TYPE_FAVORITE));
	$addToElistUrl = $this->createUrl('elists/add', array('id'=>$data->id, 'type'=>Elist::TYPE_ELIST));
?>

<li<?php if(!isset($companyPage)) : ?><?php echo ' class="'.$data->getPackageItemClass().'"'; ?><?php endif; ?>>
	<?php if($this->id == 'categories' && $this->action->id == 'show') : ?>
		<div class="inline column-checkbox">
		<?php if($data->company->map_lat && $data->company->map_lng) : ?>
			<?php /*<img src="http://www.google.com/intl/en_ALL/mapfiles/marker<?php echo Yii::app()->params['google']['map']['markers'][$index] ?>.png" alt=""/>*/?>
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
    	<?php if(!Yii::app()->user->isGuest) : ?>
    		<div class="pull-right bottom-10">
    		<?php $this->renderPartial('/elists/_addToElistButtons', 
				array(
					'itemId'=>$data->id,
					'itemType'=>$data->cache_type,
					'favoriteBtnStyle'=> in_array($data->id, $elistItems['favorite']) ? 'success' : '',
//					'elistBtnStyle'=> in_array($data->id, $elistItems['elist']) ? 'success' : '',				 
				)); ?>
			</div>	
		<?php endif; ?>	
        <?php if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$data))) : ?>
            <div class="pull-right clearfix">
                <?php $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'small',
                        'buttons' => array(
                            array(
                                'label' => Yii::t('editor', 'Edit'),
                                'url' => $urlUpdate,
                                'icon' => 'fa fa-wrench',
                                ),
                            array(
                                'label' => Yii::t('editor', 'Remove'),
                                'url' => $this->createUrl(
                                        '/companies/remove',
                                        array('name'=>$data->alias)
                                        ),
                                'type' => 'danger',
                                'icon' => 'fa fa-times',
                                'htmlOptions' => array(
                                    'class' => 'confirm-box',
                                    'data-message' => Yii::t(
                                            'companies', 
                                            '{remove-company?}', 
                                            array(
                                                '{remove-company?}' => 
                                                'Are you sure you want to remove this company? '
                                                . 'You can also temporarily hide this offer '
                                                . 'by clicking \'inactive\' on company page.'
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
                ); ?>            
            
            <?php if (!$data->active) :?>
                <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
            <?php endif; ?>
            
        </h2>
        <?php if(isset($data->company->city)): ?>            
            <small><?php echo $data->company->city ?></small>
        <?php endif;?>            
        <br>
        <?php /*
        <div class="nowrap inline">
            <?php $this->widget('LikeButton',
                array('post_type'=>'item', 'post_id'=>$data->id, 'clickable'=>false)); ?>
        </div>
        &nbsp;&nbsp;&nbsp;
         */ ?>    
        <div class="nowrap inline">
            <span><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $data->view_count ?></span>
        </div> 
        <?php $this->widget('BusinessReliableCompany', array('display' => $data->company->business_reliable, 'asBadge'=>true, 'inline' => true)) ?>    
    </div>
    <div class="inline column-contact">
        
    </div>
</li>

<?php /*<hr />*/ ?>