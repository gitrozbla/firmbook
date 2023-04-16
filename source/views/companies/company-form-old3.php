<?php 
/**
 * Formularz - dodanie i edycja danych firmy.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 

/*$category = Category::kategoria($item->category_parent_id);
$category = Category::kategoriaToJson($category);
$subcategory = Category::podkategoria($item->category_id);
$subcategory = Category::kategoriaToJson($subcategory);*/
		//print_r($category);	
		//print_r($subcategory);
$categories = array();		
for($i=0;$i<$categoriesCount;++$i)		
{	
	$parentName = 'category'.($i ? ($i+1) : '').'_parent_id'; 
	$category = Category::kategoria($item->$parentName);
	$category = Category::kategoriaToJson($category);
	$categoryName = 'category'.($i ? ($i+1) : '').'_id';
	$subcategory = Category::podkategoria($item->$categoryName);
	$subcategory = Category::kategoriaToJson($subcategory);
	$categories[] = array('parent'=>$category, 'category'=>$subcategory,
		'parent_name'=>$parentName, 'category_name'=>$categoryName,   
	);	
}
?>
<div class="row">
    <div class="span8">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        <h1><?php
        	if($company->getScenario() == 'create')
        		echo Yii::t('company', 'Add new company');
        	else 
        		echo Yii::t('company', 'Edit company data');     					    
        ?></h1>       
        <hr />
        <h5>Dane adresowe:</h5>
        <?php   /*     
		$opt = array(	
			'asDropDownList' => false,  	
			'val' => 1,
		  	'options'=>array(	
				'placeholder'=>'wybierz kategorię',
		    	'ajax' => array(
		        	'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
					'dataType' => 'json',
		        	'data' => 'js: function(text,id) {
						return {					
		                	query: text,                	
		                };
		            }',  
					'results'=>'js:function(data,id){ return {results:data};}'                                      
		        ),
		        'initSelection'=>'js:function(element,callback) {
		        			var data = '.$category.';	                   
		                   callback(data);                       
		        }',
		  	),
		);
        echo $form->select2Row($item, 'category_parent_id', $opt);*/ 
        ?>
        <?php /*
        $opt = array(	
			'asDropDownList' => false,  	
			'val' => 3,
		    //'disabled' => true,    
		  	'options'=>array(	
		        'placeholder'=>'wybierz kategorię',
		    	'ajax' => array(
		        	'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
					'dataType' => 'json',
		        	'data' => 'js: function(text,page) {        		
						return {					
		                	query: text,
		                	id: $(\'#Item_category_parent_id\').val(),
		                };
		            }',  
					'results'=>'js:function(data,id){ return {results:data};}'                                      
		        ),
		        'initSelection'=>'js:function(element,callback) {   
		        			var data = '.$subcategory.';	                   
		                   callback(data);                       
		        }',
		  	),
		);
		if(!$item->category_parent_id)
			$opt['disabled']=true;
        echo $form->select2Row($item, 'category_id', $opt);*/ 
        ?>        
        <?php 
   		for($i=0;$i<$categoriesCount;++$i)
   		{     
			$opt = array(	
				'asDropDownList' => false,  	
				'val' => 1,
			  	'options'=>array(	
					'placeholder'=>'wybierz kategorię',
			    	'ajax' => array(
			        	'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
						'dataType' => 'json',
			        	'data' => 'js: function(text,id) {
							return {					
			                	query: text,                	
			                };
			            }',  
						'results'=>'js:function(data,id){ return {results:data};}'                                      
			        ),
			        'initSelection'=>'js:function(element,callback) {
			        			var data = '.$categories[$i]['parent'].';                    
			                   /*var data = {id: 2, text: "Motoryzacja"};*/
			                   callback(data);                       
			        }',
			  	),
			);
	        echo $form->select2Row($item, $categories[$i]['parent_name'], $opt); 
	       
	        $opt = array(	
				'asDropDownList' => false,  	
				'val' => 3,
			    //'disabled' => true,    
			  	'options'=>array(	
			        'placeholder'=>'wybierz kategorię',
			    	'ajax' => array(
			        	'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
						'dataType' => 'json',
			        	'data' => 'js: function(text,page) {        		
							return {					
			                	query: text,
			                	id: $(\'#Item_'.$categories[$i]['parent_name'].'\').val(),
			                	src: 1,
			                };
			            }',  
						'results'=>'js:function(data,id){ return {results:data};}'                                      
			        ),
			        'initSelection'=>'js:function(element,callback) {   
			        			var data = '.$categories[$i]['category'].';                 
			                   /*var data = {id: 202, text: "Czesci"};*/
			                   callback(data);                       
			        }',
			  	),
			);
			if(!$item->$categories[$i]['parent_name'])
				$opt['disabled']=true;
	        echo $form->select2Row($item, $categories[$i]['category_name'], $opt); 
	   	}        
        ?>
        <?php echo $form->textFieldRow($item, 'name'); ?>
        <?php echo $form->textFieldRow($item, 'alias'); ?>                 
        <?php echo $form->textAreaRow($company, 'short_description'); ?>
        <?php //echo $form->textAreaRow($company, 'description'); ?>
        
        <hr />
        <?php echo $form->textFieldRow($company, 'phone'); ?>       
        <?php echo $form->textFieldRow($company, 'email'); ?>
        <?php echo $form->ckEditorRow($item, 'description'); ?>
         <hr />
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
        <?php $this->endWidget(); ?>
        
    </div>
</div>
<?php 
 //if($canTest) {
	$str_js_old = 
	"  
	 $('#Item_category_parent_id').change(function () {
	 	$('#Item_category_id').prop(\"disabled\", false);
	 	var vbefore = $('#Item_category_id').val();	 	
	 	$('#Item_category_id').select2('data', null);
	 	var vafter = $('#Item_category_id').val();
	 	//alert('przed '+vbefore+', po '+vafter);
	 	
	 	/*$('#Item_category_id').select2('data', {id: null, text: null});*/	
	 	 /*alert('kategoria '+$(this).val()+', podkategoria '+$('#Item_category_id').val());*/
	 	 /*$('#Item_category_id').val(\"wybierz kategorie\");*/  
	 	 /*$('#Item_category_parent_id').select2('val', 1 );*/
	 	 /*$('#Item_category_id').select2('val', 302 );*/ 
	 	   	
	 });
	 ";
	$str_js = '';  
	for($i=0;$i<$categoriesCount;++$i)
   	{ 
	$str_js .= 
	"  
	 $('#Item_".$categories[$i]['parent_name']."').change(function () {
	 	if($('#Item_".$categories[$i]['parent_name']."').val()==0) {
	 		//alert('pusty drop');	 		
	 		$('#Item_".$categories[$i]['parent_name']."').select2('data', null);
	 		//$('#Item_".$categories[$i]['category_name']."').prop(\"disabled\", true);
	 	} else {
	 		$('#Item_".$categories[$i]['category_name']."').prop(\"disabled\", false);	 		
	 	}	
	 	$('#Item_".$categories[$i]['category_name']."').select2('data', null);	 	   	
	 });
	 ";
   	}
	//$('#select2_element').select2('val', id_to_load );  
    Yii::app()->clientScript->registerScript('category-change', $str_js);
 //}
?>