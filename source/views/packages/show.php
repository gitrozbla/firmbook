<?php 
/**
 * Porównanie pakietów.
 *
 * @category views
 * @package packages
 * @author
 * @copyright (C) 2014
 */ 
?>
<?php 
if (!Yii::app()->user->isGuest) 
{		
	$canTest = Package::canTestPackage(Yii::app()->user->id);	
	$packagePaid = PackagePurchase::model()->exists('user_id=:user_id and status=:status', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']));	
} else {	
	$packagePaid = false;
	$canTest = true;
}		

/*$articleLandingPage = Article::model()->find(
		'alias="landing-page"'
);*/
?>
<?php if (Yii::app()->user->isGuest == false) {
                require '_packageDetails.php';
            } ?>
<h1><?php echo Yii::t('packages', 'Packages'); ?></h1>
            
<div class="row">          
	<?php foreach($packages as $package) : ?>
	<div class="span3">
		<?php $this->beginWidget(
    		'bootstrap.widgets.TbHeroUnit',
    		array(    			
    			'heading' => Package::badge($package['name'], $package['css_name']),
    			'encodeHeading' => false,    			
    			'headingOptions' => array(    					
    				'style' => 'font-size: 26px; margin-top: 0; padding-top: 0;',
    			),		
    			'htmlOptions' => array(
    				'class' => 'text-center',	
    				'style' => 'cursor: pointer; height: 350px; 
    					background-color: #'.$package['color']//background-color: rgba(92,170,229,0.1);
    					.'; padding-left:20px; padding-right:20px; border: 1px solid #eee',    				
    				'onclick' => 'document.location.href="'.$this->createUrl('packages/change/package/'.$package['id']).'"'    							
    			)			
    		)			
    	); ?>    	
    	
    		<p style="height: 250px;"><?php echo Yii::t('package.content', '{'.$package['name'].'}', array('{'.$package['name'].'}'=>$package['description']), 'dbMessages');?></p>
    		<?php /*<p class="lead primary"><?php echo $package['description'] ?></p>*/?>
    		<?php if($package['id'] != Package::$_packageDefault) :?>
						<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',			                	 
			                    'label' => Yii::t('packages', 'Buy now'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('packages/change/package/'.$package['id']),			                	
			                )
			            ); ?>	
			            <br/>
			            <?php if($package['test_period'] && $canTest):?>			            
				            <?php $this->widget(
				                'bootstrap.widgets.TbButton',
				                array(
				                	'buttonType' => 'link',				                	 
				                	'label' => Yii::t('packages', 'Test ({period} days)', array('{period}'=>$package['test_period'])),				                    
				                    'type' => 'success',
				                    'url' => $this->createUrl('packages/change/package/'.$package['id'].'/option/test'),	
				                	'htmlOptions'=> array('style'=>'margin-top:10px;')		                	
				                )
				            ); ?>
			            <?php endif;?>				
			<?php endif;?>  
			  
    	<?php $this->endWidget(); ?>
    </div>	
    <?php endforeach;?>    
</div>