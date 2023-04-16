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
<h1><i class="fa fa-users"></i> <?php echo Yii::t('packages', 'Packages'); ?></h1>
<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Add'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/packagesaddpackage'),
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
    	'rowCssClassExpression'=>'"items[]_{$data->id}"',
    	'selectableRows'=>1,    
    	'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    'type' => 'striped',
	    'dataProvider' => Package::packagesDataProvider($creators),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	'enableSorting' => false,
	    'columns' => array(	            
    			array(
    				'name'=>'order_index',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),    		
    			'name',	
	            'description',  
	    		/*array(
	    			'name' => 'creators',
	    			'value' => '"<i class=\"fa fa-".($data->creators ? "check-" : "")."square-o\"></i>"',
	    			'type' => 'raw',
	    		),*/
    			array(
    				'name'=>'test_period',    				
    				'value'=>'$data->test_period ? $data->test_period : \'-\';',
    				'htmlOptions'=> array('style'=>'width:100px;')	            	
    			),
                        array(
    				'name'=>'active',    				
    				'value'=>'$data->active ? Yii::t(\'ad\',\'Yes\') : Yii::t(\'ad\', \'No\')',
    				'htmlOptions'=> array('style'=>'width:100px;')	            	
    			),
                        
	    		array(
	    				'class'=>'bootstrap.widgets.TbRelationalColumn',
	    				'name' => 'Tłumaczenia',
	    				'url' => $this->createUrl('admin/translations', 
	    						array('model'=>'package')),
	    				'value'=> '"Tłumaczenia"',
	    				/*'afterAjaxUpdate' => 'js:function(tr,rowid,data){
	    				 bootbox.alert("I have afterAjax events too!
	    				 		This will only happen once for row with id: "+rowid);
	    		}'*/
	    		),
	    		array(
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{services}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
	    			'buttons' => array(
                    	'delete' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagesremovepackage/id/$data->id")',
	                        'label' => Yii::t('packages', 'Delete'),
                    		'icon' => 'fa fa-trash-o',
                            /*'ajaxUpdate'=>false,*/                       
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagesupdatepackage/id/$data->id")',
	                        'label' => Yii::t('packages', 'Edit'),
	    					'icon' => 'fa fa-pencil',
	    				),
	    				'services' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagespackageservices/id/$data->id")',
	                        'label' => Yii::t('packages', 'Services'),	  
                            'icon' => 'fa fa-list',                     
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
                    'url': '" . $this->createUrl('/admin/packagessortpackages') . "',
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
			            
