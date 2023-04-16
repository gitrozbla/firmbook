<?php
/*
 * lista ulubionych 
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */
/* <h1><i class="fa fa-building-o"></i> <?php echo Yii::t('packages', 'Companies'); ?></h1> */
switch ($elist->type) {
    case Elist::TYPE_ELIST:
        $type = 'elist';
        $deleteUrl = 'Yii::app()->controller->createUrl("/elists/remove", array(\'id\'=>$data["id"], \'type\'=>Elist::TYPE_ELIST))';
        break;
    case Elist::TYPE_FAVORITE:
    default:
        $type = 'favorite';
        $deleteUrl = 'Yii::app()->controller->createUrl("/elists/remove", array(\'id\'=>$data["id"], \'type\'=>Elist::TYPE_FAVORITE))';
        break;
}
?>	

<?php
$this->widget(
        'Button', array(
    'id' => 'btn' . ucfirst($type) . 'EmailModal',
    'buttonType' => 'button',
    //'label' => Yii::t('common','Favorite'),
    //'type' => 'success',
    'icon' => 'fa fa-envelope',
    'htmlOptions' => array(
//						    'data-toggle' => 'modal',
//						    'data-target' => '#emailModal',
        //'onclick' => 'loadMultiEmailForm(\'emailModal\')',

        'onclick' => 'loadEmailModal' . ucfirst($type) . '()',
        'style' => 'margin-left: 10px;',
        'title' => Yii::t('common', 'Send message'),
    ),
        )
);
?>

<div class="clearfix"></div>			            

<?php
$this->widget(
        'bootstrap.widgets.TbExtendedGridView', array(
    //'id'=>'project-grid-'.uniqid(),
    'id' => 'grid-' . $type,
    'type' => 'striped',
    //'rowCssClassExpression'=>'"items[]_{$data->item_id}"',
//     	'selectableRows'=>1,    
    //'sortableRows'=>true,
    //'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
    'fixedHeader' => true,
    'headerOffset' => 0,
    //'type' => 'striped',
// 	    'dataProvider' => $elist->elistDataProvider(),
    'dataProvider' => $elist->elistDataProviderUnion(),
    //'filter' => $elist,
    'responsiveTable' => true,
    //'template' => "{items}",
    'enableSorting' => true,
    'rowCssClassExpression' => '',
    //'rowCssClassExpression' => '$data->item->active ? "" : "fade-medium";',
    /* 'bulkActions' => array(
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
      ), */
    'columns' => array(
//	    		array(
//	    			'class' => 'CCheckBoxColumn',
//	    			'selectableRows' => 2,
//	    			'name' => 'id'				
//				),
        array(
            'class' => 'CCheckBoxColumn',
            'selectableRows' => 2,
//	    			'name' => 'id',
//                                'value' => '$data["id"]."_".$data["cache_type"]'
            'value' => '$data["cache_type"]'
        ),
        array(
            'name' => 'cache_type',
//     				'value' => '"<i class=\"fa fa-".Item::faIconClass($data["cache_type"])."\"></i>"',
            'value' => '"<i class=\"fa fa-".Elist::faIconClass($data["cache_type"])."\"></i>"',
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'width:50px; text-align: center;',
            )
        ),
        array(
            'name' => 'name',
            'value' => 'Elist::itemLink($data)',
//     				'value' => 'CHtml::link($data["name"], Yii::app()->createUrl("products/show",
//     								array("name"=>$data["alias"])))',
            'type' => 'raw',
        //'htmlOptions'=> array('style'=>'width:20px;')	            	
        ),
        array(
            //'class'=>'bootstrap.widgets.TbButtonColumn',
            'class' => 'ButtonColumn',
            'template' => '{delete}',
            /* 'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }', */
            'evaluateHtmlOptions' => true,
            'buttons' => array(
                'delete' => array(
                    'url' => $deleteUrl,
                    //'Yii::app()->controller->createUrl("/elists/remove")',
                    //. '"/admin/packagesremoveservice/id/$data->item_id")',
                    'label' => Yii::t('packages', 'Delete'),
                    'options' => array('id' => '$data["id"]'),
                    'click' => 'function(){	    						
	    						var btnId = $(this).attr(\'id\');	    						
	    						$.fn.yiiGridView.update("grid-' . $type . '", {
	    								dataType:\'json\',
                                        //type:"POST",
                                        url:$(this).attr(\'href\'),
                                        success:function(data) {                                        	
                                        	$.fn.yiiGridView.update("grid-' . $type . '");
                                        	$("#' . $type . '-btn-"+btnId).removeClass("btn-success");
                                        	$("#btn-' . $type . '").html(data.button);
                                        	//alert("$data->item_id");
                                        	//$("#favorite-btn-$data->item_id").addClass("btn-success");
                                        }
                                    })
                                    return false;
	    						
	    					}'
                /* 'ajaxUpdate'=>false, */
                /* 'buttonType' => 'ajaxLink',
                  'ajaxOptions' => array(
                  'dataType' => 'json',
                  'success' => 'function(data) {
                  alert(\'usuniecie\');
                  }'

                  ), */
                ),
            ),
            'htmlOptions' => array(
                'style' => 'width:20px; text-align: center;',
            )
        ),
        array(
            'name' => 'date',
            'value' => 'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data["date"])',
            'type' => 'raw',
            'htmlOptions' => array(
                'style' => 'width:80px;',
            )
        ),
    )
));
?>
<?php
echo Html::link(
        Yii::t('elists', 'Show all'), $this->createUrl('elists/list', array(
            'type' => $elist->type)));
?>

<?php $script = '$(\'#btn-' . $type . '\').click(function() {			 
			 			$.fn.yiiGridView.update("grid-' . $type . '");
					});'; ?>			            
<?php Yii::app()->clientScript->registerScript('load-grid', $script); ?>
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
        alert(types);
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
