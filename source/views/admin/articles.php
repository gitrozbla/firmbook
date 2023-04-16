<?php 
/**
 * Admin - lista pakietów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 

if(!isset($creators)) $creators = false;

?>
<h1><i class="fa fa-users"></i> <?php echo Yii::t('admin', 'Articles'); ?></h1>
<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Add'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/addarticle'),
			                	'htmlOptions'=> array(
					    		 	'style'=>'float: right; margin-right: 50px; margin-bottom: 10px;',	
					    		)
			                	
			                )
			            ); ?>
<br />			            
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	'id'=>'project-grid',
    	//'rowCssClassExpression'=>'"items[]_{$data->id}"',
    	'selectableRows'=>1,    
    	//'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    'type' => 'striped',
	    'dataProvider' => Article::articlesDataProvider($creators),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	//'enableSorting' => false,
    	'rowCssClassExpression' => '$data->visible ? "" : "fade-medium";',
    	/*'bulkActions' => array(
    				'actionButtons' => array(
    						array(
    								'id' => 'jakiesid',
    								'buttonType' => 'button',
    								'context' => 'primary',
    								'size' => 'small',
    								'label' => 'Testing Primary Bulk Actions',
    								'click' => 'js:function(values){console.log(values);}'
    						)
    				),
    				// if grid doesn't have a checkbox column type, it will attach
    				// one and this configuration will be part of it
    				'checkBoxColumnConfig' => array(
    					'name' => 'id'
    				),
    	),*/
	    'columns' => array(	            
    			/*array(
    				'name'=>'id',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),*/    		
    			'label',	
    			'alias',
	            'title',   			
    			 array(
					'class'=>'bootstrap.widgets.TbRelationalColumn',
					'name' => 'Tłumaczenia',
					'url' => $this->createUrl('admin/translations/model/article'),
					'value'=> '"Tłumaczenia"',
					/*'afterAjaxUpdate' => 'js:function(tr,rowid,data){
						bootbox.alert("I have afterAjax events too!
						This will only happen once for row with id: "+rowid);
					}'*/
				), 
	    		array(
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
	    			'buttons' => array(
                    	'delete' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/removearticle/id/$data->id")',
	                        'label' => Yii::t('packages', 'Delete'),
                    		'icon' => 'fa fa-trash-o',
                            /*'ajaxUpdate'=>false,*/                       
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/updatearticle/id/$data->id")',
	                        'label' => Yii::t('packages', 'Edit'),
	    					'icon' => 'fa fa-pencil',
	    				)
	    			),
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:70px; text-align: center;',	
		    		)	
				    
				),
				
	    )
    ));
    
?>
			            
