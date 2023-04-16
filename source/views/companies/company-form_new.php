<?php 
/**
 * Formularz - dodanie i edycja danych firmy.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 


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
        <h5>Profil firmy:</h5>        
        <hr />
        <?php echo $form->textFieldRow($item, 'name'); ?>
        <?php echo $form->textFieldRow($item, 'alias'); ?>
        <?php 
        	echo $form->checkBoxListRow($item, 'account_type', array('1'=> Yii::t('item', 'Buy'), '2'=> Yii::t('item', 'Sell')));
        	//echo $form->checkBoxListRow($item, 'account_type', array(Yii::t('item', 'Buy'), Yii::t('item', 'Sell')), array(1));
        	//echo $form->checkBoxListRow($item, 'buy', array('Kupujący', 'Sprzedawca', 'Oba'));
        	//echo $form->checkBoxListRow($item, 'buy', array('id'=>1, 'name'=>'Kupujacy', 'id', 'name'));
        //echo $form->checkBoxListRow($item, 'tagsIds', CHtml::listData(Tag::model()->findAll(), 'id', 'name'));
        ?>
        <?php echo $form->textFieldRow($company, 'nip'); ?>
        <?php echo $form->textFieldRow($company, 'regon'); ?>
        <?php echo $form->textFieldRow($company, 'krs'); ?>
        <h5>Branże w których działa firma:</h5>        
        <hr />
        <?php 
   		for($i=0;$i<$categoriesCount;++$i)
   		{     
			$opt = array(	
				'asDropDownList' => false,  	
				'val' => 1,
			  	'options'=>array(	
					'placeholder'=>Yii::t('category', 'select category'),
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
			        'placeholder'=>Yii::t('category', 'select category'),
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
        <h5>Opis:</h5>        
        <hr />   
        <?php echo $form->textAreaRow($company, 'short_description'); ?>
        <?php echo $form->ckEditorRow($item, 'description'); ?>     
        <h5>Adres firmy:</h5>        
        <hr />
        <?php echo $form->textFieldRow($company, 'city'); ?>
        <?php echo $form->textFieldRow($company, 'province'); ?>
        <?php echo $form->textFieldRow($company, 'street'); ?>
        <?php echo $form->textFieldRow($company, 'postcode'); ?>
        <h5>Kontakt:</h5>        
        <hr />       
        
        <?php echo $form->textFieldRow($company, 'phone'); ?>       
        <?php echo $form->textFieldRow($company, 'email'); ?>
        
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
Yii::app()->clientScript->registerScript('category-change', $str_js);
?>