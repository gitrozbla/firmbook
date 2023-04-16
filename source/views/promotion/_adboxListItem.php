<?php 
/**
 * Podstrona boxu reklamowego.
 *
 * @category views
 * @package promotion
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 

	$url = $updateUrl = $this->createUrl('promotion/buy_box', array(
    	'id'=>$data->id)); //Yii::t('item-'.$data->id, $data->alias, array(), 'dbMessages'))); 
	
?>

<li>
    <div class="inline column-thumbnail">
        <?php /*echo $this->widget(
				    'bootstrap.widgets.TbBadge',
				    array(
					    'type' => 'info',					
					    'label' => $data->label,
				    	//'htmlOptions' => array('style' => 'margin-left: 10px;'),				    
					), 
					true
				);*/ ?>
		<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => 'info',
						'label' => $data->label,
						'htmlOptions' => array(
							'style' => 'padding: 2px 7px;'			
						)	
											
					)); ?>		
    </div>
    	<div class="inline column-title">
    	
    		<div class="pull-right bottom-10">
    		
			</div>   		
       
            <div class="pull-right clearfix">
                <?php $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'small',
                        'buttons' => array(
                            array(
                                'label' => Yii::t('ad', 'Buy'),
                                'url' => $updateUrl,
                                'icon' => 'fa fa-shopping-cart',
                                ),
                            array(
                                'label' => Yii::t('ad', 'Contact'),
                                'url' => $this->createUrl(
                                        '/promotion/contact',
                                        array('id'=>$data->id)
                                        ),                                
                                'icon' => 'fa fa-envelope',
                            )    
                        ),
                    )
                ); ?>
            </div>
        
        <h2>
        	<?php echo Yii::t('adsbox.name', $data->name, array(), 'dbMessages') ?><br/>
        	
        	<?php //echo Yii::t('adsbox.name', '{'.$data->alias.'}', array('{'.$ad->alias.'}'=>$ad->name), 'dbMessages') ?>
            <?php //echo $data->name ?>
        </h2>
        <p><?php echo Yii::t('adsbox.description', '{'.$data->alias.'}', array('{'.$data->alias.'}'=>$data->description), 'dbMessages') ?></p>
	        <?php 
	        	
	        	//	$labelType = 'warning';
	        	
	        		$labelType = 'info';	
	        ?>        
			<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => $labelType,
						'label' => $data->size				
					)); ?>			
		
        
    </div>
    <div class="inline column-contact">
        
    </div>
</li>

<?php /*<hr />*/ ?>