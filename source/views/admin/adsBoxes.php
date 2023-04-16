<?php 
/**
 * Admin - boxy reklamowe.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 

?>
<h1><i class="fa fa-users"></i> Boxy reklamowe</h1>
<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('categories', 'Add'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/adsaddbox'),
			                	'htmlOptions'=> array(
					    		 	'style'=>'float: right; margin-right: 50px; margin-bottom: 10px;',	
					    		)
			                	
			                )
			            ); ?>
			            
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	'id'=>'project-grid',
    	'rowCssClassExpression'=>'"items[]_{$data->id}"',
    	'selectableRows'=>1,    
    	'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    'type' => 'striped',
    	'dataProvider' => AdsBox::adminDataProvider(),
	    //'dataProvider' => Category::adminDataProvider($root, $level),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	'enableSorting' => false,
	    'columns' => array(	            
    			/*array(
    				'name'=>'order_index',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),*/    		
    			'label',
	    		'alias',
	    		'name',
	    		'size',
	    		'height',
	    		'price',
	    		'period',
	    		'carousel',
	    		array(
	    				'class'=>'bootstrap.widgets.TbRelationalColumn',
	    				'name' => 'Tłumaczenia',
	    				'url' => $this->createUrl('admin/translations', 
	    						array('model'=>'adsbox')),
	    				'value'=> '"Tłumaczenia"',	    				
	    		),	    		 	    		
	    		array(
	    			//'class'=>'ButtonColumn',
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',	    			
	    			//'evaluateHtmlOptions'=>true,
	    			'buttons' => array(
                    	'delete' => array(	    					
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/adsremovebox/id/$data->id")',
	                        'label' => Yii::t('common', 'Delete'),                                                  
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/adsupdatebox/id/$data->id")',
	                        'label' => Yii::t('common', 'Edit'),                            	                       
	    				),
	    			),
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:70px; text-align: center;',	
		    		)	
				    
				),
				array(
    				'name'=>'id',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),
	    )
    ));
	
?>


<?php 

$str_js = "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };

        $('#project-grid table.items tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
            	serial = $('#project-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'});
            	serial=serial+'&YII_CSRF_TOKEN=".Yii::app()->request->csrfToken."';
                $.ajax({
                    'url': '" . $this->createUrl('/admin/sortcategories') . "',
                    'type': 'post',
                    'data': serial,
                    'success': function(data){
                    	 /*$.fn.yiiGridView.update('project-grid');*/
                    },
                    'error': function(request, status, error){
                        /*alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');*/
                    }
                });
            },
            helper: fixHelper
        }).disableSelection();
    ";

Yii::app()->clientScript->registerScript('sortable-project', $str_js);

?>			            
