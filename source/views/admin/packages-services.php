<?php 
/**
 * Admin - lista usług.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */

if(!isset($creators)) $creators = false;

?>
<?php /*<div id="statusMsg">
<?php if(Yii::app()->user->hasFlash('success')):?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?> 
<?php if(Yii::app()->user->hasFlash('error')):?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif; ?>
</div>*/
 ?>
<h1 style=""><i class="fa fa-users"></i> <?php echo Yii::t('packages', 'Services'); ?></h1>
<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Add'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/packagesaddservice'),
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
	    'dataProvider' => PackageService::servicesDataProvider($creators),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	'enableSorting' => false,
	    'columns' => array(	            
    			array(
    				'name'=>'order_index',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),
	            'name',
    			'role',
	            //'description',
    			//'value_type'
    			array(
	    			'name'=>'value_type', 
	    			'value'=>'!$data->value_type ? Yii::t(\'packages\', \'Yes/No\') : Yii::t(\'packages\', \'Numerical\')'
    			),
	    		array(
	    			'name' => 'active',
	    			'value' => '"<i class=\"fa fa-".($data->active ? "check-" : "")."square-o\"></i>"',
	    			'type' => 'raw',
	    		),
	    		/*array(
	    			'name' => 'creators',
	    			'value' => '"<i class=\"fa fa-".($data->creators ? "check-" : "")."square-o\"></i>"',
	    			'type' => 'raw',
	    		),*/
    			array(
					'class'=>'bootstrap.widgets.TbRelationalColumn',
					'name' => 'Tłumaczenia',
					'url' => $this->createUrl('admin/translations/model/packageservice'),
					'value'=> '"Tłumaczenia"',
					/*'afterAjaxUpdate' => 'js:function(tr,rowid,data){
						bootbox.alert("I have afterAjax events too!
						This will only happen once for row with id: "+rowid);
					}'*/
				), 	    		
	    		array(
	    			//'class'=>'ButtonColumn',
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
	    			//'evaluateHtmlOptions'=>true,
	    			'buttons' => array(
                    	'delete' => array(	    					
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagesremoveservice/id/$data->id")',
	                        'label' => Yii::t('packages', 'Delete'),	
                    		'icon' => 'fa fa-trash-o',
                            /*'ajaxUpdate'=>false,*/                       
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagesupdateservice/id/$data->id")',
	                        'label' => Yii::t('packages', 'Edit'),
	    					'icon' => 'fa fa-pencil',
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
                    'url': '" . $this->createUrl('/admin/packagessortservices') . "',
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

			