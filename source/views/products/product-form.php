<?php 
/**
 * Formularz - dodanie i edycja danych produktu.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 

/*$categories = array();
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
*/

if ($item->category) {
	$mainCategoryTree = $item->category->ancestors()->findAll();
	$mainCategoryTree []= $item->category;
}else {
	$mainCategoryTree = null;
}

$additionalCategoriesTrees = array();
foreach($item->additionalCategories as $additionalCategory) {
	$additionalCategoriesTree = $additionalCategory->ancestors()->findAll();
	$additionalCategoriesTree []= $additionalCategory;
	$additionalCategoriesTrees []= $additionalCategoriesTree;
}

$domain = Yii::app()->params['websiteMode'] == 'creators'
	? Yii::app()->params['firmbookUrl']
	: Yii::app()->params['branding']['domain'];

?>
<?php
    $class = get_class($product);
    $source = strtolower($class);    
?>
<div class="row">
	<?php //require './source/views/companies/_companyPanel.php'; ?>
    <div class="span12">
		<div class="well">
			<?php $form = $this->beginWidget('ActiveForm', array(
				'type' => 'horizontal', 
//                                'action' => 'javascript:alert(grecaptcha.getResponse());',
				'htmlOptions' => array('class' => 'center'),            
			)); ?>        
			<h1><?php
				if($product->getScenario() == 'create')
					echo Yii::t($source, 'Add new '.$source);
				else 
					echo Yii::t($source, 'Edit '.$source.' data');     					    
			?></h1>       
			
			<fieldset>
			<legend><?php echo Yii::t($source, $class.' data'); ?>:</legend>        
			<?php echo $form->checkBoxRow($item, 'active', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>        
			<?php echo $form->textFieldRow($item, 'name'); ?>   
            <?php echo $form->textFieldRow($item, 'alias', null,
        		array('hint' =>Yii::t('item', '<b>Alias is the most important SEO phrase</b>. It\'s visible in the link of the '
                            . 'address of Firmbook after domain.<br>Alias should contain only numbers, '
                            . 'letters and dashes instead of spaces. Example:') . '<br>'
                        . '<a href="opakowania-wielorazowe-starachowice">'
                        . '<b>'.$domain . '/opakowania-wielorazowe-starachowice</b>'
                        . '</a>'
	            ));
			?>
			<?php /*echo $form->textFieldRow($item, 'alias', null,
            		array(
            			'prepend' => $domain.'/',
            			'append' => '<a class="form-hint" href="#" onclick="return false;" data-content="'
                            . Yii::t('item', 'Alias is the most important SEO phraze. It\'s visible in the link of the '
								. 'address of Firmbook after domain. Alias should contain only numbers, '
								. 'letters and dashes instead of spaces. Example:') . ' '
                            . $domain . '/<b>' . Yii::t('product', 'chainsaw') . '</b>'
                            . '">'
                            . '<i class="fa fa-question-circle"></i>'
                        . '</a>'
            	));*/
            ?>
            
			<?php echo $form->textFieldRow($product, 'signature', null, 
				array('hint' => Yii::t('product', 'e.g. product number'))); ?>
			
			<?php echo $form->checkBoxListRow($item, 'account_type', 
				array('1'=> Yii::t('item', 'Buy'), '2'=> Yii::t('item', 'Sell')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>   
			<?php echo $form->select2Row($product, 'company_id', array(
					'data' => CHtml::listData(Company::userCompaniesArray(Yii::app()->user->id), 'id', 'name')        		
				)
			);
			?>   	
			
			<?php echo $form->textFieldRow($item, 'youtube_link',
				array('style' => 'width:350px'),
				array('hint' => YIi::t('item', 'Example:')
				. ' http://www.youtube.com/watch?v=videoId')); ?>
			
                        <?php echo $form->textFieldRow($product, 'allegro_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
//	            . ' http://allegro.pl/listing/user/listing.php?us_id=12345678'));
                    . 'https://allegro.pl/oferta/KodProduktu')); ?>
                        
			<?php echo $form->select2TreeRow($item, 'category_id', array(
				'tree' => $mainCategoryTree,
				'limit' => 2,
				'labelAttribute' => 'nameLocal',
				'emptyText' => Yii::t('item', 'Select (optional)'),
				'rootEmptyText' => Yii::t('item', 'Select'),
				'url' => $this->createGlobalRouteUrl('categories/json_get_subcategories')
			)); ?>
			<?php /*echo $form->select2TreesRow($item, 'additionalCategories', array(
				'trees' => $additionalCategoriesTrees,
				'labelAttribute' => 'nameLocal',
				'emptyText' => Yii::t('item', 'Select (optional)'),
				'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
				'limit' => 5
			));*/ ?>
			<?php /* 
				$opt = array(	
					'asDropDownList' => false,  	
					'val' => 1,
					'options'=>array(	
						'placeholder'=>Yii::t('product', 'select company'),
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
				echo $form->select2Row($product, 'company_id', $opt);*/
			?>
			<?php 
			/*for($i=0;$i<count($categories);++$i)
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
			*/?>       
			<legend>Opis:</legend>           
			<?php echo $form->textAreaRow($product, 'short_description', null, array(
				'append' => '<a class="form-hint" href="#" onclick="return false;" 
								data-content="Krótki opis jest widoczny przy udostępnianiu 
									produktu/usługi na portalach społecznościowych">'
                            . '<i class="fa fa-question-circle"></i>'
                        . '</a>'
			)); ?>
			<?php echo $form->ckEditorRow($item, 'description', array(
				'editorOptions' => array(
					'height'=> 400
				)
			)); ?>    
			<?php //echo $form->ckEditorRow($item, 'description'); ?>     
			<?php //echo $form->html5EditorRow($item, 'description'); ?>
			
			<?php 
			$opt = array(
				//'prepend' => 'http://'
				'hint' => Yii::t('item', 'Example:') . ' http://example.com'
			);
			echo $form->textFieldRow($item, 'www', null, $opt); ?>		
			<?php //echo $form->textFieldRow($item, 'www', null, array('prepend' => 'http://')); ?>    
			
			<legend>Cena:</legend>
			<?php echo $form->textFieldRow($product, 'price'); ?>
			
			<?php echo $form->select2Row($product, 'currency_id', array(
					'data' => CHtml::listData(Dictionary::unitsListArray('currency', false), 'id', 'name'))); ?>
			
			<?php /*echo $form->select2Row($product, 'unit_id', array(
					'data' => CHtml::listData(Dictionary::unitsListArray('unit'), 'id', 'name')));*/ ?>
			
			<?php /*<div class="control-group">
			<?php echo CHtml::activeLabel($product, 'promotion_price', array('class'=>'control-label', 'required'=>true)); ?>
			<div class="controls">
			<?php echo CHtml::activeTextField($product, 'promotion_price'); ?>
			<?php echo CHtml::error($product, 'promotion_price'); ?>
			<div class="help-inline error" id="Product_promotion_price_em_" style="display:none"></div>
			</div></div>*/ ?>
			<?php echo $form->checkBoxRow($product, 'promotion',
				array(), 
				//array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>
			<?php echo $form->textFieldRow($product, 'promotion_price'); ?>        
			<?php //echo $form->dateFieldRow($product, 'promotion_expire'); ?>
			<?php echo $form->datePickerRow($product, 'promotion_expire'); ?>
			<?php //echo $form->datePickerRow($product, 'promotion_expire', array('value'=>Yii::app()->dateFormatter->format('MM/dd/yyyy', $product->promotion_expire))); ?>
			<?php //echo $form->datePickerRow($product, 'promotion_expire', null, array('dateFormat'=>'yyyy-MM-dd')); ?>
			
			<?php if($source == 'product') : ?>
			<legend>Dodatkowe informacje:</legend>
			<?php echo $form->checkBoxRow($product, 'delivery_free', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>           
			<?php echo $form->textFieldRow($product, 'delivery_price'); ?> 
			<?php echo $form->textFieldRow($product, 'delivery_min'); ?>
			<?php echo $form->textFieldRow($product, 'delivery_time'); ?>
			<?php echo $form->checkBoxRow($product, 'adults', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>  
			<?php endif; ?>
			
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
		<?php /*<div class="span3 pull-right">
			<?php if ($company) : ?>
				<?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
			<?php endif; ?>       
		</div>*/ ?>
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

Yii::app()->clientScript->registerScript('hints',
		'$(".form-hint").popover({"html": true});');
?>