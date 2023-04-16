<?php 
/**
 * Admin - lista pakietów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 

if($category->id) {
	$breadcrumbs = $category->manageCategoryBreadcrumbs();
} else 
	$breadcrumbs = NULL;
?>
<h1><i class="fa fa-list"></i> <?php echo Yii::t('categories', 'Categories'); ?>
<?php //if($category->id) echo '<br/>'.$category->name ?>
</h1>
<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('categories', 'Add'),
			                    'type' => 'primary',
			                    'url' => $this->createGlobalRouteUrl('admin/addcategory', array("category"=>$category->id)),
			                	'htmlOptions'=> array(
					    		 	'style'=>'float: right; margin-right: 50px; margin-bottom: 10px;',	
					    		)
			                	
			                )
			            ); ?>
<?php $this->widget(
                    'bootstrap.widgets.TbBreadcrumbs',
                    array(
                    	'homeLink' => Html::link('Kategorie', $this->createGlobalRouteUrl('admin/categories')),
                        //'homeLink' => Html::link(Yii::t('site', 'Home'), $absoluteHomeUrl),
                        'links' => $breadcrumbs,
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
    	'dataProvider' => $category->adminDataProvider(),
	    //'dataProvider' => Category::adminDataProvider($root, $level),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	'enableSorting' => false,
	    'columns' => array(	            
    			/*array(
    				'name'=>'order_index',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),*/    		
    			'name',
	    		'alias',
	    		array(
	    				'class'=>'bootstrap.widgets.TbRelationalColumn',
	    				'name' => 'Tłumaczenia',
	    				'url' => $this->createGlobalRouteUrl('admin/translations', 
	    						array('model'=>'category')),
	    				'value'=> '"Tłumaczenia"',	    				
	    		),
	    		array(
	    				'class'=>'CLinkColumn',
	    				'labelExpression' => '"Podkategorie (".count($data->children()->findAll()).")"',
	    				'urlExpression' => 'Yii::app()->controller->createGlobalRouteUrl("admin/categories",
	    						array("category"=>$data->id))',
	    							//"level"=>$data->level+1))',
	    				//'value'=> '"Tłumaczenia"',
	    		), 	    		
	    		array(
	    			//'class'=>'ButtonColumn',
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
	    			//'evaluateHtmlOptions'=>true,
	    			'buttons' => array(
                    	'delete' => array(

	    					'url' => 'Yii::app()->controller->createGlobalRouteUrl('
                            . '"/admin/removecategory/id/$data->id")',
	                        'label' => Yii::t('common', 'Delete'),
													'icon' => 'fa fa-trash-o'
                            /*'ajaxUpdate'=>false,*/
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createGlobalRouteUrl('
                            . '"/admin/updatecategory/id/$data->id")',
	                        'label' => Yii::t('common', 'Edit'),
													'icon' => 'fa fa-pencil'
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
                    'url': '" . $this->createGlobalRouteUrl('/admin/sortcategories') . "',
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
