<?php 
/**
 * Przyciski: 
 * 1-2 - dodania/usuniecia do ulubionych i elisty.
 * 3 - dodania/usuniecia do obserwowanych
 * 4 - formularza wiadomości email
 *
 * @category views
 * @package elist
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php	
	if(!isset($itemType))
		$itemType = Elist::ITEM_TYPE_ITEM;
	
	$addToFavoriteUrl = $this->createUrl('elists/add', array('id'=>$itemId, 'elist_id'=>Elist::TYPE_FAVORITE, 'type'=>$itemType));
	$addToElistUrl = $this->createUrl('elists/add', array('id'=>$itemId, 'elist_id'=>Elist::TYPE_ELIST, 'type'=>$itemType));
	
	if(isset($followItemType))
		$addToFollowUrl = $this->createUrl('follow/add', array('id'=>$itemId, 'type'=>$followItemType));	
	
?>
    		
<?php if(isset($favoriteBtnStyle)) : ?>
	<?php $this->widget(
				    'Button',
				    array(
				    	'id' =>'favorite-btn-'.$itemId,
				    	'buttonType' => 'ajaxLink',
				    	'url' => $addToFavoriteUrl,
					    //'label' => Yii::t('common','Favorite'),
					    'type' => $favoriteBtnStyle,
				    	'icon' => 'fa fa-heart',
				    	'htmlOptions' => array('title' => Yii::t('elists', 'Add to favorites')),	
					    'ajaxOptions' => array(
                            			'dataType' => 'json',
                            			'success' => 'function(data) {					    				  
						    				$("#btn-favorite").html(data.button);  
					    					$("#inverse-btn-favorite").html(data.inverse_button);
					    					if(data.counter>0)
					    						$("#btn-favorite").addClass("btn-success");
					    					else
					    						$("#btn-favorite").removeClass("btn-success");
						    				if(data.scenario)
						    					$("#favorite-btn-'.$itemId.'").addClass("btn-success");
						    				else	
						    					$("#favorite-btn-'.$itemId.'").removeClass("btn-success");						    										    				
						    			}'                           			
                            		)
                                				    	
				    )
			    ); ?>
<?php endif; ?>
<?php /*echo Html::link(
			                Yii::t('item', 'Polubień'),
			                $this->createUrl('elists/inverselist', array(
			                    'type' => 1)));*/ ?>			    
<?php if(isset($elistBtnStyle) && false) : ?>			    
	<?php $this->widget(
				    'Button',
				    array(
				    	'id' =>'elist-btn-'.$itemId,
				    	'buttonType' => 'ajaxLink',
				    	'url' => $addToElistUrl,
					    //'label' => Yii::t('common','Favorite'),
					    'type' => $elistBtnStyle,
				    	'icon' => 'fa fa-list',
					    'ajaxOptions' => array(
                            			'dataType' => 'json',
                            			'success' => 'function(data) {    								    				    			
						    				$("#btn-elist").html(data.button);	
					    					$("#inverse-btn-elist").html(data.inverse_button);
					    					if(data.counter>0)
					    						$("#btn-elist").addClass("btn-success");
					    					else
					    						$("#btn-elist").removeClass("btn-success");
						    				if(data.scenario)
						    					$("#elist-btn-'.$itemId.'").addClass("btn-success");
						    				else	
						    					$("#elist-btn-'.$itemId.'").removeClass("btn-success");		    				
						    			}'                           			
                            		),
                        'htmlOptions' => array(
                        		'style'=>'margin-left: 5px;',
                        		'title' => Yii::t('elists', 'Add to elist')
                        ),                                				    	
				    )
			    ); ?>
<?php endif; ?>			    
<?php /*echo Html::link(
			                Yii::t('item', 'Dodań do elisty'),
			                $this->createUrl('elists/inverselist', array(
			                    'type' => 1)));*/ ?>
<?php if(isset($followItemType)) : ?>			    
	<?php $this->widget(
				    'Button',
				    array(
				    	'id' =>'follow-btn-'.$itemId,
				    	'buttonType' => 'ajaxLink',
				    	'url' => $addToFollowUrl,
					    //'label' => Yii::t('common','Favorite'),
					    'type' => $followBtnStyle,
				    	'icon' => 'fa fa-eye',
					    'ajaxOptions' => array(
                            			'dataType' => 'json',
                            			'success' => 'function(data) {    								    				    			
						    				$("#btn-follow").html(data.button);	
					    					$("#inverse-btn-follow").html(data.inverse_button);
					    					if(data.counter>0)
					    						$("#btn-follow").addClass("btn-success");
					    					else
					    						$("#btn-follow").removeClass("btn-success");
						    				if(data.scenario)
						    					$("#follow-btn-'.$itemId.'").addClass("btn-success");
						    				else	
						    					$("#follow-btn-'.$itemId.'").removeClass("btn-success");		    				
						    			}'                           			
                            		),
                        'htmlOptions' => array(
                        		'style'=>'margin-left: 5px;',
                        		'title' => Yii::t('follow', 'Add to observed')
                        ),                                				    	
				    )
			    ); ?>			   
<?php endif; ?>		
<?php /*echo Html::link(
			                Yii::t('item', 'Obserwujących'),
			                $this->createUrl('elists/inverselist', array(
			                    'type' => 1)));*/ ?>
<?php if(isset($recipientType)) :?>
	     
	<?php $this->widget(
				    'Button',
				    array(
				    	'id' =>'btn-emailModal',
				    	'buttonType' => 'button',					    
				    	'icon' => 'fa fa-envelope',
					    'htmlOptions' => array(
						    'data-toggle' => 'modal',
						    'data-target' => '#emailModal',
				    		'onclick' => 'loadEmailForm(\'emailModal\')',
				    		'style'=>'margin-left: 10px;',
				    		'title' => Yii::t('common', 'Send message'), 
                            'class' => 'btn-info'
				    	),				    	
				    )
			    ); ?>
			    
	<?php $this->beginWidget('bootstrap.widgets.TbModal',
		array(
				'id' => 'emailModal',
				'htmlOptions' => array(
					'class'=>'text-left'
				)
		)
	); 
	?>	
	<?php $this->endWidget(); ?>
			    
	<?php echo '
	<script>	
		function loadEmailForm(id){			
				$.ajax({
					method: "POST",				
					url: "'.$this->createUrl('site/send_email').'",
					data: {recipientId:'.$itemId.', recipientType:"'.$recipientType.'"'
						.', '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
					dataType: "html",
				}).done(function(html) {	 			      
					$("#" + id).html(html);  
			        //$("#" + id + " .modal-body").html(html);
					$("#" + id).modal("show");						
				});				
		}
	</script>
	'; ?>
				    			    			    
<?php endif; ?>      
        