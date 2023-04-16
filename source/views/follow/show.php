<?php
/*
 * lista obserwowanych 
 *
 * @category views
 * @package follow
 * @author
 * @copyright (C) 2015
 */

//$deleteUrl = 'Yii::app()->controller->createUrl("/follow/remove", array(\'id\'=>$data["id"], \'type\'=>$data["item_type"]))';
$deleteUrl = 'Yii::app()->controller->createUrl("/follow/add", array(\'id\'=>$data["id"], \'type\'=>$data["item_type"]))';
$type = 'follow';
?>	

<?php /*$this->widget(
				    'Button',
				    array(
				    	'id' =>'btn-emailModal',
				    	'buttonType' => 'button',
					    //'label' => Yii::t('common','Favorite'),
					    //'type' => 'success',
				    	'icon' => 'fa fa-envelope',
					    'htmlOptions' => array(
						    'data-toggle' => 'modal',
						    'data-target' => '#emailModal',
				    		//'onclick' => 'loadMultiEmailForm(\'emailModal\')',
				    		'style'=>'margin-left: 10px;',
				    		'title' => Yii::t('common', 'Send message'), 				    			    	
				    	),				    	
				    )
			    );

<div class="clearfix"></div>			            
			      */ ?>      

<?php
$this->widget(
    'Button', array(
        'id' => 'btn' . ucfirst($type) . 'EmailModal',
        'buttonType' => 'button',    
        'icon' => 'fa fa-envelope',
        'htmlOptions' => array(
            'onclick' => 'loadEmailModal' . ucfirst($type) . '()',
            'style' => 'margin-left: 10px;',
            'title' => Yii::t('common', 'Send message'),
        )
    )
);
?>

<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(    	
    	'id'=>'grid-'.$type,    
    	'type' => 'striped',
    	//'selectableRows'=>1,    		
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    'dataProvider' => $follow->followDataProviderUnion(),    	
	    'responsiveTable' => true,	    
    	'enableSorting' => true,
    	'rowCssClassExpression' => '',
    	//'htmlOptions' => array('class' => 'grid-view rounded'),
    	//'itemsCssClass' => 'table table-striped table-hover',
    	//'template' => '{items}',
    		/*'htmlOptions'=> array(
    				'style'=>'text-align:center; background-color: #fff;',
    		),*/
    	//'rowCssClassExpression' => '$data->item->active ? "" : "fade-medium";',    	
	    'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'selectableRows' => 2,    
//                'value' => '$data["cache_type"]'
                'value' => '$data["cache_type"]',
//                'visible' =>false
                'disabled' => '$data["cache_type"]==\'k\''
            ),
            /*array(
                'class' => 'CCheckBoxColumn',
                'selectableRows' => 2,
                'name' => 'id'				
            ),*/
            array(
                'name' => 'cache_type',
                'value' => '"<i class=\"fa fa-".Follow::faIconClass($data["cache_type"])."\"></i>"',
                'type'  => 'raw',    				
                'htmlOptions'=> array(
                    'style'=>'width:50px; text-align: center;',	
                )
            ),   			
            array(
                'name'=>'name',
                'value' => 'Follow::itemLink($data)',
                /*'value' => 'CHtml::link($data[\'name\'], Yii::app()->createUrl("products/show",
                            array("name"=>$data->item->alias)))',*/	    			
                'type'  => 'raw',	    				
            ), 
            array(
                //'class'=>'bootstrap.widgets.TbButtonColumn',
                'class'=>'ButtonColumn',
                'template'=>'{delete}',
                /*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
                'evaluateHtmlOptions'=>true,
                'buttons' => array(
                    'delete' => array(
                        'url' => $deleteUrl,
                        //'Yii::app()->controller->createUrl("/elists/remove")',
                        //. '"/admin/packagesremoveservice/id/$data->item_id")',
                        'label' => Yii::t('packages', 'Delete'),
                        'options' => array('id' => '$data["id"]'),
                        'click' => 'function(){	    						
                            var btnId = $(this).attr(\'id\');	    						
                            $.fn.yiiGridView.update("grid-'.$type.'", {
                                    dataType:\'json\',
                                    //type:"POST",
                                    url:$(this).attr(\'href\'),
                                    success:function(data) {                                        	
                                        $.fn.yiiGridView.update("grid-'.$type.'");
                                        $("#'.$type.'-btn-"+btnId).removeClass("btn-success");
                                        $("#btn-'.$type.'").html(data.button);
                                        //alert("$data->item_id");
                                        //$("#favorite-btn-$data->item_id").addClass("btn-success");
                                    }
                                })
                                return false;

                        }'
                        /*'ajaxUpdate'=>false,*/  
                        /*'buttonType' => 'ajaxLink',
                        'ajaxOptions' => array(
                                    'dataType' => 'json',
                                    'success' => 'function(data) {					    				  
                                        alert(\'usuniecie\');						    										    										    				
                                    }'                           			

                        ),*/                     
                    ),	    				
                ),
                'htmlOptions'=> array(
                    'style'=>'width:20px; text-align: center;',	
                )					    
            ),
            array(
                'name' => 'date',
                'value' => 'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data[\'date\'])',
                'type'  => 'raw',    				
                'htmlOptions'=> array(
                    'style'=>'width:80px;',	
                )
            ),   			
	    )
    ));
    
?>

<?php echo Html::link(
                Yii::t('elists', 'Show all'),
                $this->createUrl('follow/list')); ?>

<?php $script = '$(\'#btn-'.$type.'\').click(function() {			 
			 			$.fn.yiiGridView.update("grid-'.$type.'");
					});'; ?>			            
<?php Yii::app()->clientScript->registerScript('load-grid', $script); ?>

<?php 
$itemId = 32682;
$itemType = 'c';
/*echo '
<script>	
	function loadMultiEmailForm(id) {
 			var checked=$("#grid-'.$type.'").yiiGridView("getChecked","grid-'.$type.'_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
			
        	var count=checked.length;
            if(count==0){
                alert("No items selected");
 				return false;
            } else
				alert(count);
		
			var values = [];
            checked.each(function(){
            	//values.push($(this).val());
            }); 	
			console.log(values);
			//alert(checked[0]);
			$.ajax({
				method: "POST",
				//method: "GET",
				url: "'.$this->createUrl('site/send_email_to_many').'",
				data: {recipientId:checked'.(isset($itemType) ? ', recipientType:"'.$itemType.'"' : '')
					.', '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				//data: {type: 1, '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				dataType: "html",
			}).done(function(html) {	 			      
				$("#" + id).html(html);  
		        //$("#" + id + " .modal-body").html(html);
				$("#" + id).modal("show");						
			});				
	}
</script>
';*/
?>

<?php
Yii::app()->clientScript->registerScript('loadEmailModal', '
function loadEmailModal' . ucfirst($type) . '() {
    var checked=$("#grid-' . $type . '").yiiGridView("getChecked","grid-' . $type . '_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
    var count=checked.length;
    if(count==0){
        return false;
    }
    var types = [];
    $(\'input[name="grid-' . $type . '_c0[]"]:checked\').each(function(i,e) {
        types.push($(this).val());
    });
    if(count>0)
    {        
        var id = "emailModal";
        $.ajax({
            method: "POST",
            url: "' . $this->createUrl('site/send_email_to_many') . '",
            data: {recipientId:checked, recipientType:types'
                . ', ' . Yii::app()->request->csrfTokenName . ': "' . Yii::app()->request->csrfToken . '"},
            dataType: "html",
        }).done(function(html) {	 			      
                $("#" + id).html(html);  
                $("#" + id).modal("show");						
        });	        
    }
}
');
?>

