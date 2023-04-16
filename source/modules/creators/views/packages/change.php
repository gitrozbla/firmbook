<?php 
/**
 * Formularz zmiany paketu
 *
 * @category views
 * @package packages
 * @author
 * @copyright (C) 2015
 */

if(!($selectedId = Yii::app()->request->getParam('package')))
	$selectedId = 4;

$packages = Package::model()->findAll(array('condition'=>'creators','order'=>'order_index desc'));
$packagesToSelect = array();
foreach($packages as $package)
	if($package['id']!=Package::$_packageDefault) {		
    	$packagesToSelect[$package['id']]['package'] = $package;
    	$packagesToSelect[$package['id']]['periods'] = array();
	}	    	
$packagesPeriods = PackagePeriod::model()->findAll();
foreach($packagesPeriods as $period)
	if(array_key_exists($period['package_id'], $packagesToSelect))
	{
		$packagesToSelect[$period['package_id']]['periods'][]=$period; 
	}	
	
//print_r($packagesToSelect);
//if($canTest) {
	//for($i=0;$i<count($packagesPeriods))
	$packagesTestPeriods = array();
	$js_periods = 'var packages=[]; ';
	foreach($packagesToSelect as $package) {	    	
		$js_periods .= 'packages["'.$package['package']['id'].'"]=[]; ';
		if($package['package']['test_period']) {
			$js_periods .= 'packages["'.$package['package']['id'].'"]["test_period"]='.$package['package']['test_period'].'; ';	
			$js_periods .= 'packages["'.$package['package']['id'].'"]["test_label"]=\''.Yii::t('packages', 'Test ({period} days)', array('{period}'=> $package['package']['test_period'])).'\'; ';
			$packagesTestPeriods[$package['package']['id']]['period'] = $package['package']['test_period'];
	    	$packagesTestPeriods[$package['package']['id']]['label'] = Yii::t('packages', 'Test ({period} days)', array('{period}'=> $package['package']['test_period']));
	    	//$packagesTestPeriods[$package['package']['id']]['price'] = ;
		}
		$js_periods .= 'packages["'.$package['package']['id'].'"]["periods"]=[]; ';
		$js_periods .= 'var periods=[]; ';
		foreach($package['periods'] as $period) {			
			$js_periods .= 'periods["'.$period['period'].'"]={"period":'.$period['period'].', "price":"'.$period['price'].' PLN"}; ';						
		}
		$js_periods .= 'packages["'.$package['package']['id'].'"]["periods"]=periods; ';			
	}
//}
/*$tempPack = Yii::app()->user->getState('package_id');
echo $tempPack;
++$tempPack;
//echo $tempPack;
Yii::app()->user->setState('package_id', $tempPack);
echo Yii::app()->user->getState('package_id');*/
?>
<?php require '_packageDetails.php'; ?>
<div class="row">
    <div class="span6">        
        <?php $form = $this->beginWidget('ActiveForm', array(            
            'htmlOptions' => array('class' => 'well center'),
        )); ?>        
            <h1><?php echo Yii::t('packages', 'Upgrade your account'); ?> <?php echo Yii::app()->name; ?></h1>            
            <?php echo Yii::t('packages', 'Configure your order.'); ?>            
            <hr />
			<?php echo $form->dropDownListRow(
				$purchase,
				'package_id',
				Package::packagesToSelect(true),
				array('options' => array($selectedId=>array('selected'=>true)))
			); ?>
			<?php echo $form->dropDownListRow(
				$purchase,
				'period',
				PackagePeriod::periodsToSelect()
			); ?>
			<?php echo $form->textFieldRow($purchase, 'price', array("disabled"=>"disabled", "style"=>"width:70px;")); ?>
			<?php 
				//if(Yii::app()->user->package_id != Package::$_packageDefault)
					echo $form->checkBoxRow($purchase, 'force_activation'); 
			?>
            <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Buy now'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?> 
            <?php if($canTest):?>
                <span id="test_layer"<?php if(!array_key_exists($selectedId, $packagesTestPeriods)):?> style="display: none"<?php endif;?>>
                &nbsp;&nbsp;&nbsp;<?php echo Yii::t('packages', 'or');?>&nbsp;&nbsp;&nbsp;
         <!-- <div style="margin: 0 auto 0 auto; background-color: red; text-align: center;">lub testuj przez 30 dni<br /></div> -->
         		<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(	
			                	'id'=> 'test_button',		                	
			                	'buttonType' => 'submit',
			                	//'buttonType' => 'submit', 
			                    'label' => array_key_exists($selectedId, $packagesTestPeriods) ? $packagesTestPeriods[$selectedId]['label'] : '',
			                    'type' => 'success',			                    	
			                	'htmlOptions'=> array('name'=>'package_test')		                	
			                )
			            ); ?>       
			    </span> 
			<?php endif;?>           
        <?php $this->endWidget(); ?>
         
    </div>    
    <div class="span6">        
        <br />
        <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>
        <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
    </div>
</div>
<?php 
 //if($canTest) {
	$str_js = $js_periods.
	" $('#PackagePurchase_price').val(packages[$('#PackagePurchase_package_id').val()]['periods'][$('#PackagePurchase_period').val()]['price']); 
	 $('#PackagePurchase_package_id').change(function () {
	 	 $('#PackagePurchase_price').val(packages[$('#PackagePurchase_package_id').val()]['periods'][$('#PackagePurchase_period').val()]['price']);
	     var optionSelected = $(this).find(\"option:selected\");
	 	 var valueSelected  = optionSelected.val();     
	     var textSelected   = optionSelected.text();
	     if(packages[valueSelected]['test_period']) {
	     	$('#test_button').text(packages[valueSelected]['test_label']);
	     	$('#test_layer').css({'display':'inline'});     	
	     } else	
	     	$('#test_layer').css({'display':'none'});     	
	 });
	 $('#PackagePurchase_period').change(function () {
	 	$('#PackagePurchase_price').val(packages[$('#PackagePurchase_package_id').val()]['periods'][$('#PackagePurchase_period').val()]['price']);
	 });";    
    Yii::app()->clientScript->registerScript('package-change', $str_js);
 //}
?>