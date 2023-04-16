<?php 
/**
 * Admin - tłumaczenia.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 

//echo CHtml::tag('h3',array(),'RELATIONAL DATA EXAMPLE ROW: '.$id);
$this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Add'),
			                    'type' => 'primary',
			                	'url' => $this->createUrl('admin/translationsadd/id/'.$id,
			                				array('model'=>$model)),
			                    //'url' => $this->createGlobalRouteUrl('admin/translationsadd/id/'.$id.'/model/'.$model),
			                	'htmlOptions'=> array(
					    		 	'style'=>'float: right; margin-right: 50px; margin-bottom: 10px;',	
					    		)			                	
			                )
			            );
?>
<br />
<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'striped',
	'dataProvider' => $translations,
	'template' => "{items}",
	'responsiveTable' => true,
	'columns' => array(
		array(
			'name' => 'Language',
			'value' => '$data[\'language\']',
		),		
		'title',
		array(
			'name' => 'content',
				'value' => '$data[\'content\']',
			//'value' => 'isset($data[\'content\']) ? $data[\'content\'] : ""',
			//'type'=>'raw',
		),		
		array(
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
	    			'buttons' => array(
                    	'delete' => array(
							'url' => 'Yii::app()->controller->createGlobalRouteUrl("/admin/translationsremove/",
								array("id"=>$data["object_id"], "lang"=>$data["language"], "model"=>"'.$model.'"))',
	                        'label' => Yii::t('packages', 'Delete'),
                    		'icon' => 'fa fa-trash-o',
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createGlobalRouteUrl("/admin/translationsupdate/",
	    							array("id"=>$data["object_id"], "lang"=>$data["language"], "model"=>"'.$model.'"))',
                            'label' => Yii::t('packages', 'Edit'),
	    					'icon' => 'fa fa-pencil',
	    				),
	    			),
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:70px; text-align: center;',	
		    		)					    
				),
	),
));

?>
