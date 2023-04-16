<?php 
/**
 * Formularz - dodanie i edycja danych usługi.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 

$categories = array();
$productionServer = true;	
if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
	$productionServer = false;
else	
	$productionServer = true;
if(!$productionServer)			
	for($i=0;$i<$categoriesCount;++$i)		
	{	
		$parentName = 'category'.($i ? ($i+1) : '').'_parent_id'; 
		$category = Category::kategoria($item->$parentName);
		$category = Category::kategoriaToJson($category);
		$categoryName = 'category'.($i ? ($i+1) : '').'_id';
		$subcategory = Category::podkategoria($item->$categoryName);
		$subcategory = Category::kategoriaToJson($subcategory);
		
		$categories[] = array($category, $subcategory);	
	}
else
	for($i=0;$i<$categoriesCount;++$i)		
	{		
		$categoryName = 'category'.($i ? ($i+1) : '').'_id';
		$category = Category::model()->findByPk($item->$categoryName);
		if($category) {
			$categoriesPart = $category->getCategoriesToItemSelect();	
			$categories[] = $categoriesPart;
		} else {
			$categories[] = array(CJSON::encode(null), CJSON::encode(null));
		}	
			
	}
	
		
?>
<div class="">
    <div class="span8">        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
        	'htmlOptions' => array('class' => 'center'),            
        )); ?>        
        <h1><?php
        	if($service->getScenario() == 'create')
        		echo Yii::t('service', 'Add new service');
        	else 
        		echo Yii::t('service', 'Edit service data');     					    
        ?></h1>       
        
        <fieldset>
        <legend><?php echo Yii::t('service', 'Service data'); ?>:</legend>        
        <?php echo $form->checkBoxRow($item, 'active', 
        	array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
        	array('labelOptions'=> array(
                 'style' => "display: inline-block"
           	))); ?>        
        <?php echo $form->textFieldRow($item, 'name'); ?>
        <?php echo $form->textFieldRow($item, 'alias'); ?>
        <?php echo $form->checkBoxListRow($item, 'account_type', 
        	array('1'=> Yii::t('item', 'Buy'), '2'=> Yii::t('item', 'Sell')), 
        	array('labelOptions'=> array(
                 'style' => "display: inline-block"
           	))); ?>              
        <?php 
        	$opt = array(	
				'asDropDownList' => false,  	
				'val' => 1,
			  	'options'=>array(	
					'placeholder'=>Yii::t('service', 'select company'),
			    	'ajax' => array(
			        	'url' => Yii::app()->createUrl('companies/json_companies_list', 
                            array('user_id'=>Yii::app()->user->id)),
						'dataType' => 'json',
			        	'data' => 'js: function(text,id) {
							return {					
			                	query: text			                	                	
			                };
			            }',  
						'results'=>'js:function(data,id){ return {results:data};}'                                      
			        ),
			        'initSelection'=>'js:function(element,callback) {
			        			                    
			                   var data = '.($company ? '{id: '.$company->item_id.', text: "'.$company->item->name.'"}' : '\'NULL\'').';
			                   callback(data);                       
			        }',
			  	),
			);
        	echo $form->select2Row($service, 'company_id', $opt);
        ?>
        <?php 
   	for($i=0;$i<count($categories);++$i)
   	{
   		for($j=0;$j<count($categories[$i]);++$j)
   		{     
			$opt = array(	
				'asDropDownList' => false,  	
				'val' => 1,
			  	'options'=>array(	
					'placeholder'=>Yii::t('category', 'select category'),
			    	'ajax' => array(
			        	'url' => $productionServer ? Yii::app()->createUrl('categories/json_get_subcategories')
									: Yii::app()->createUrl('categories/json_get_subcategories_test'),
						'dataType' => 'json',
			        	'data' => 'js: function(text,id) {
							return {					
			                	query: text,
			                	'.($j!=0 ? 'id: $(\'#Item_'.'category'.($i ? ($i+1) : '').'_parent_id'.'\').val(),
			                	src: 1,' : '').'                	
			                };
			            }',  
						'results'=>'js:function(data,id){ return {results:data};}'                                      
			        ),
			        'initSelection'=>'js:function(element,callback) {
			        			var data = '.$categories[$i][$j].';                    
			                   /*var data = {id: 2, text: "Motoryzacja"};*/
			                   callback(data);                       
			        }',
			  	),
			);
			$parentName = 'category'.($i ? ($i+1) : '').'_parent_id';
			//if($j!=0 && !$item->$parentName)
			
			if($j!=0 && $categories[$i][$j-1]=='null')
				$opt['disabled']=true;
	        echo $form->select2Row($item, 'category'.($i ? ($i+1) : '').($j==0 ? '_parent_id' : '_id'), $opt); 
   		}
   	}	   	     
        ?>               
        <?php //echo $form->ckEditorRow($item, 'description'); ?>     
        <?php echo $form->html5EditorRow($item, 'description'); ?>		
        <?php 
        $opt = array('prepend' => 'http://');
        echo $form->textFieldRow($item, 'www', null, $opt); ?>        
        <hr />
        </fieldset>
 
		<div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?>
            <?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Cancel'),
			                    'type' => 'primary',
			                    'url' => Yii::app()->request->urlReferrer,
			                	
			                )
			            ); ?>    
			            
		</div>	            
        <?php $this->endWidget(); ?>
        
    </div>
    <div class="span3 pull-right">
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>       
    </div>
</div>
<?php 	
$str_js = '';  
for($i=0;$i<$categoriesCount;++$i)
{ 
	$str_js .= 
	"
	 $('#Item_category".($i ? ($i+1) : '')."_parent_id').change(function () {
	 	if($('#Item_category".($i ? ($i+1) : '')."_parent_id').val()==0) {	 			 		
	 		$('#Item_category".($i ? ($i+1) : '')."_parent_id').select2('data', null);
	 		
	 	} else {
	 		$('#Item_category".($i ? ($i+1) : '')."_id').prop(\"disabled\", false);	 		
	 	}	
	 	$('#Item_category".($i ? ($i+1) : '')."_id').select2('data', null);	 	   	
	 });
	 ";
}  
Yii::app()->clientScript->registerScript('category-change', $str_js);
?>