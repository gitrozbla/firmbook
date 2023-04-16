<?php 
/**
 * Formularz - dodanie i edycja danych firmy.
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
			
	}*/
	
$itemUpdateUrl = $this->createUrl('companies/partialupdate', array('model'=>'Item'));	

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

if($company->getScenario() == 'create') {
	$package = Package::model()->findByPk(Yii::app()->user->package_id);
	$item->color = $package->color;
} else {
	$package = Package::model()->findByPk($item->cache_package_id);
	if(!$item->color)		
		$item->color = $package->color;	
}

$domain = Yii::app()->params['websiteMode'] == 'creators'
	? Yii::app()->params['firmbookUrl']
	: Yii::app()->params['branding']['domain'];

?>

<div class="row">
	<?php require '_companyPanel.php'; ?>
    <div class="span12">
		<div class="well">        
			<?php $form = $this->beginWidget('ActiveForm', array(        		
				'type' => 'horizontal',        	
				'htmlOptions' => array('class' => 'center'),
				//'htmlOptions' => array('class' => 'well center'),
				
				/*
				 //przygotowane pod focus na polu z error-em
				 'id' => 'companyForm',
				'enableAjaxValidation'=>false,
				'clientOptions'=>array(
							'validateOnSubmit'=>false,
				),
				'focus'=>'#companyForm input:text:empty:first,'
							. '#companyForm input[class=error]:first',*/
			)); ?>        
			<h1><?php
				if($company->getScenario() == 'create')
					echo Yii::t('company', 'Add new company');
				else 
					echo Yii::t('company', 'Edit company data');     					    
			?></h1>       
			
			<fieldset>
			<legend>Profil firmy:</legend>    
            <?php if(Yii::app()->user->isAdmin) : ?>
            <?php echo $form->checkBoxRow($company, 'business_reliable', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>
            <?php endif; ?>
			<?php echo $form->checkBoxRow($item, 'active', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>
			<?php //echo $form->toggleButtonRow($item, 'active'); 
			//, array('0'=>'Tak', '1'=>'Nie'
			?>
			<?php echo $form->textFieldRow($item, 'name'); ?>
            <?php echo $form->textFieldRow($item, 'alias', null,
        		array('hint' =>Yii::t('item', '<b>Alias is the most important SEO phrase</b>. It\'s visible in the link of the '
                            . 'address of Firmbook after domain.<br>Alias should contain only numbers, '
                            . 'letters and dashes instead of spaces. Example:') . '<br>'
//                        . '<a href="'. $domain . '/opakowania-wielorazowe-starachowice">'
                        . '<a href="opakowania-wielorazowe-starachowice">'
                        . '<b>'.$domain . '/opakowania-wielorazowe-starachowice</b>'
                        . '</a>'
	            ));
			?>
			<?php /*echo $form->textFieldRow($item, 'alias', null,
        		array(
        			'prepend' => $domain.'/',
        			'append' => '<a class="company-form-hint" href="#" onclick="return false;" data-content="'
                        . Yii::t('item', 'Alias is the most important SEO phraze. It\'s visible in the link of the '
                            . 'address of Firmbook after domain. Alias should contain only numbers, '
                            . 'letters and dashes instead of spaces. Example:') . ' '
                        . $domain . '/<b>' . Yii::t('company', 'furniture-manufacturer') . '</b>'
                        . '">'
                        . '<i class="fa fa-question-circle"></i>'
                    . '</a>'
				));*/
			?>
			<?php
				/*$legalForms = array(0 => '(' . Yii::t('item', 'not selected') . ')');
				foreach(Company::$legalForms as $key=>$value) {
					$legalForms[$key] = Yii::t('company', $value);
				}
				echo $form->dropdownListRow($company, 'legal_form', $legalForms, array(
					'style' => 'width:300px'
				));*/
			?>
			<?php echo $form->checkBoxListRow($item, 'account_type',
				array(
					'1'=> Yii::t('company', 'buys products / orders services'),
					'2'=> Yii::t('company', 'sell products / offers services')
				),
				array(
					'label' => Yii::t('company', 'Company'),
			)); ?>
			<?php echo $form->textFieldRow($company, 'nip'); ?>
			<?php echo $form->textFieldRow($company, 'regon'); ?>
			<?php echo $form->textFieldRow($company, 'krs'); ?>
			<?php echo $form->checkBoxRow($company, 'allow_verification', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>
				
			<hr />
			
			
			<?php $paymentsTab = array();
				//for($i=1; $i<count(Yii::app()->params->payments); ++$i) {
					//$paymentsTab["$i"] =  CHtml::image('images/payment_icons/'.Yii::app()->params->payments["$i"]['name'].'.png', Yii::app()->params->payments["$i"]['name']);
				foreach(Yii::app()->params->payments as $key=>$payment) {
							if (isset($payment['image'])) {
						$paymentsTab[$key] =  CHtml::image('images/payment_icons/'.$payment['image'], $payment['name']);
							} else {
								$paymentsTab[$key] =  Yii::t('company', $payment['name']);
							}
				} ?>   
			<?php echo $form->checkBoxListRow($company, 'payment_type',
				$paymentsTab,
				array('labelOptions'=> array(
					 'style' => "display: inline-block; margin-left: 15px;",
					 //'class' => 'payment-icon'	
				))); ?>   
				
			<?php echo $form->checkBoxRow($company, 'payment_wire_transfer') ?>
			<?php echo $form->textFieldRow($company, 'payment_bank_account',
				array('style'=>'width:350px')) ?>
			<?php echo $form->textFieldRow($company, 'payment_swift_code',
				null,
				array('append'=>'<a class="company-form-hint" href="#" onclick="return false;" data-content="'
					. Yii::t('company', 'For international transactions.') . '">'
					. '<i class="fa fa-question-circle"></i>'
				. '</a>')) ?>

			<?php echo $form->checkBoxRow($company, 'payment_cash') ?>
			
			<hr />
			
			
			<?php $deliveryTab = array();
				//for($i=1; $i<count(Yii::app()->params->payments); ++$i) {
					//$paymentsTab["$i"] =  CHtml::image('images/payment_icons/'.Yii::app()->params->payments["$i"]['name'].'.png', Yii::app()->params->payments["$i"]['name']);
				foreach(Yii::app()->params->delivery as $key=>$delivery) {
							if (isset($delivery['image'])) {
						$deliveryTab[$key] =  CHtml::image('images/delivery_icons/'.$delivery['image'], $delivery['name']);
							} else {
								$deliveryTab[$key] =  Yii::t('product', $delivery['name']);
							}
				} ?>
			<?php echo $form->checkBoxListRow($company, 'delivery_type',
						$deliveryTab,
				array('labelOptions'=> array(
					'style' => "display: inline-block; margin-left: 10px;",
					//'class' => 'payment-icon'
				))); ?>
			
			<?php echo $form->checkBoxRow($company, 'free_delivery') ?>
			
			<hr />
			
				
			<?php 
				$colors = array();
				//if($company->getScenario() == 'create') {
					//$package = Package::model()->findByPk(Yii::app()->user->package_id);
					$colors[$package->color] = '<div style="width: 40px; height: 40px; background-color: #'.$package->color.'"></div>';
				//}
					
				foreach(Yii::app()->params->colors as $color) {
					$colors[$color] = '<div style="width: 40px; height: 40px; background-color: #'.$color.'"></div>'; 
				}
			?>   	  	
			<?php echo $form->radioButtonListRow($item, 'color', 
				$colors,
				array('labelOptions'=> array(
					 'style' => "display: inline-block; margin-left: 10px;",
					 //'class' => 'payment-icon'	
				))); ?>
				
				
			<legend><?php echo Yii::t('item', 'Your social media') ?>:</legend>

			<?php echo $form->textFieldRow($item, 'facebook_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
	            . ' https://www.facebook.com/Firmbook.Creators')); ?>
	            
	        <?php echo $form->textFieldRow($item, 'facebook_profile_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
	            . ' https://www.facebook.com/Firmbook.Creators')); ?>

			<?php /*echo $form->textFieldRow($item, 'google_plus_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
	            . ' https://plus.google.com/115339039966271072536/posts'));*/ ?>

			<?php echo $form->textFieldRow($company, 'allegro_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
//	            . ' http://allegro.pl/listing/user/listing.php?us_id=12345678'));
                    . 'https://allegro.pl/uzytkownik/MojaNazwa')); ?>
				
                        <?php echo $form->textFieldRow($company, 'youtube_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
	            . ' https://www.youtube.com/user/channelId')); ?>
                        
			<?php echo $form->textFieldRow($item, 'youtube_link',
				array('style' => 'width:350px'),
	        	array('hint' => Yii::t('item', 'Example:')
	            . ' https://www.youtube.com/watch?v=videoId')); ?>

			   
			<?php echo $form->checkBoxRow($company, 'allow_comment', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>   	
			<legend>Branże w których działa firma:</legend>     
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
		}*/	   	     
			?>
			
			<legend>Opis:</legend>           
			<?php //echo $form->textAreaRow($company, 'short_description'); ?>
			
			<?php echo $form->ckEditorRow($item, 'description', array(
				'editorOptions' => array(
					'height'=> 400
				)
			)); ?>
			<?php //echo $form->redactorRow($item, 'description'); ?>
			<?php //echo $form->html5EditorRow($item, 'description'); ?>
			
			<?php //echo $form->html5EditorRow($item, 'description'); ?>     
			<?php /*echo $form->html5EditorRow($item, 'description', array(
	'editorOptions' => array(
	'class' => 'span4',
	'rows' => 3,
	'height' => '200',
	'options' => array('color' => true)
	)
	));*/ ?>
			<legend><?php echo Yii::t('company', 'Company address') ?>:</legend>                            
			<?php echo $form->select2Row($company, 'country', array(
                            'data' => CHtml::listData(Country::getCountries(), 'code', 'text'),
//                            'val' => 'PL'
                            ));
                            //'data' => CHtml::listData(Country::model()->findAll(), 'code', 'name')));		
			?>
						
			<?php echo $form->textFieldRow($company, 'city'); ?>
			<?php echo $form->textFieldRow($company, 'province'); ?>
			<?php echo $form->textFieldRow($company, 'street'); ?>
			<?php echo $form->textFieldRow($company, 'postcode'); ?>
			<?php require '_formGMap.php'; ?>
			<?php //$this->renderPartial('formGMap', compact('company')); ?>
			<hr />
			<?php //wymaga dopracowania, obecnie wklejamy cąły iframe, a dla bezpieczenstwa powinien byc sam adres ?>
			<?php //echo $form->textFieldRow($company, 'street_view_embed'); ?>
			
			<?php /*echo $form->checkBoxListRow($company, 'street_view_active', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"        		 	
				)));*/ ?>
			
			<?php echo $form->checkBoxRow($company, 'street_view_active', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>
			
			<legend>Kontakt:</legend> 
			
			<div id="phone-input-group">
				<?php echo $form->textFieldRow($company, 'phone'); ?>
				<?php for($i=2; $i<=5; $i++) {
					echo $form->textFieldRow($company, 'phone' . $i, array(), array('label' => false));
				} ?>
			</div>

			<div id="email-input-group">
				<?php echo $form->textFieldRow($company, 'email'); ?>
				<?php for($i=2; $i<=5; $i++) {
					echo $form->textFieldRow($company, 'email' . $i, array(), array('label' => false));
				} ?>
			</div>
				
			<?php echo $form->checkBoxRow($company, 'hide_email', 
				array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')), 
				array('labelOptions'=> array(
					 'style' => "display: inline-block"
				))); ?>
			<?php echo $form->textFieldRow($company, 'skype'); ?>
			<?php 
			$opt = array(
				//'prepend' => 'http://'
				'hint' => Yii::t('item', 'Example:') . ' http://example.com'
			);
			echo $form->textFieldRow($item, 'www', null, $opt); ?>
					
			<hr />
			</fieldset>
	 
			<div class="form-actions">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType' => 'submit', 
					'label' => Yii::t('common', 'Save'), 
					'type' => 'primary',
					'htmlOptions' => array(
						'class' => 'form-indent',
						'onclick' => 'codeAddress(true)',                	
					))); ?>
				<?php $this->widget(
					'bootstrap.widgets.TbButton', array(
						'buttonType' => 'link',
						//'buttonType' => 'submit', 
						'label' => Yii::t('common', 'Cancel'),
						'type' => 'primary',
						'url' => Yii::app()->request->urlReferrer,
					)); ?> 			            
			</div>	            
			<?php $this->endWidget(); ?>
			
		</div>
		<?php /*<div class="span3 pull-right">
			<?php if (isset($companyClone)) : ?>
				<?php $this->renderPartial('/companies/_companyBox', array('company' => $companyClone)); ?>
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
	 	if($('#Item_category".($i ? ($i+1) : '')."_parent_id').val()==0)	 			 		
	 		$('#Item_category".($i ? ($i+1) : '')."_parent_id').select2('data', null);	 		
	 	else 
	 		$('#Item_category".($i ? ($i+1) : '')."_id').prop(\"disabled\", false);	 		
	 		
	 	$('#Item_category".($i ? ($i+1) : '')."_id').select2('data', null);	 	   	
	 });
	";
}  
Yii::app()->clientScript->registerScript('category-change', $str_js);

Yii::app()->clientScript->registerScriptFile('js/input-group.js');

Yii::app()->clientScript->registerScript('company-form', '
    $(".company-form-hint").popover({"html": true});

    $("#Company_nip").on("change", function() {
			var val = $(this).val();
			$(this).val(val.replace(/-/g, "").replace(/ /g, ""));
		});

		$("#phone-input-group").inputGroup({
			"add-button-label": "' . Yii::t('company', 'Add another phone number') . '",
		});
		$("#email-input-group").inputGroup({
			"add-button-label": "' . Yii::t('company', 'Add another email address') . '",
		});
');
?>