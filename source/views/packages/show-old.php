<?php 
/**
 * Porównanie pakietów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 
?>
<?php 
if (PackagePurchase::model()->exists('user_id=:user_id and status=:status', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])))
	$packagePending = true;
else	
	$packagePending = false;
/*
$this->widget(
'bootstrap.widgets.TbButton',
array(
'label' => 'Top popover',

)
);*/

?>
<?php  //echo Yii::app()->file->filesPath; ?>
<?php if (Yii::app()->user->isGuest == false) {
                require 'packageDetails.php';
            } ?>
            
<div class="well center">            
<h1><?php echo Yii::t('packages', 'Packages'); ?></h1>
			<table style="" id="ads-buy">
				<tr class="odd head">
					<td></td>
					<?php foreach($packages as $package) : ?>
					<td>
						<div class="package package-platinum clearfix ">
							<!-- <h1 class="title" style="border-bottom-width: 0px; margin-bottom: 0px; padding-bottom: 0px;"> -->
								<?php 
									//echo $package['name']; 
									echo Package::badge($package['name'], $package['css_name']);
								?>
							<!-- </h1>-->								
						</div>
					</td>
					<?php endforeach;?>					
				</tr>
				<?php if(!$packagePending) :?>
				<tr class="odd">
					<td>
						
					</td>
					<?php foreach($packages as $package) : ?>	
					<td style="">
					<?php /*$form = $this->beginWidget(
			            'ActiveForm',
			            array(
			                'id' => 'contact-form',
			                'type' => 'horizontal',
			            	'action' => $this->createUrl('packages/beforbuy')
			            )
			        ); */?>
			        <?php if($package['id'] != Package::$_packageDefault) :?>
									<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Buy now'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('packages/beforbuy/package_id/'.$package['id']),
			                	
			                )
			            ); ?>
            
					<?php /*$this->endWidget(); */?>
					
					<?php endif;?>				
					</td>
					<?php endforeach;?>		
					<!-- <td>
						<a href="/account/packages" class="button" style="width: 80px;">Kup teraz</a>
					</td>
					<td>
						<a href="/account/packages" class="button" style="width: 80px;">Kup teraz</a>
					</td>
					<td>
						<a href="/account/packages" class="button" style="width: 80px;">Kup teraz</a>
					</td> -->
				</tr>
				<?php endif;?>
				<?php $rowIndex = 0; ?>
				<?php foreach($services as $service) : ?>				
				<tr class="<?php echo $rowIndex & 1 ? 'odd' : 'even'; ?>">
					<td style="width:350px;"><?php echo $service['name']; ?></td>					
					<?php if($service['value_type'] == 1) : ?>
						<?php foreach($packages as $package) : ?>
					<td>
						<?php if(isset($package['services'][$service['id']])) : ?>
							<?php if(isset($package['services'][$service['id']]['threshold']) && $package['services'][$service['id']]['threshold']) : ?>
							<?php echo $package['services'][$service['id']]['threshold']; ?>
							<?php else : ?>
							Bez limitu
							<?php echo $package['services'][$service['id']]['threshold']; ?>
							
							<?php endif; ?>
						<?php else : ?>
						-
						<?php endif; ?>
					</td>
						<?php endforeach;?>
					<?php else : ?>
						<?php foreach($packages as $package) : ?>
					<td>
						<?php if(isset($package['services'][$service['id']])) : ?>
							<?php if($service['role'] == 'order'):?>
								<?php echo Yii::t('packages', $package['id']); ?>	
							<?php else:?>
						<img src="/images/icons/tick.png" />
							<?php endif;?>
						<?php else : ?>
						-
						<?php endif; ?>
					</td>
						<?php endforeach;?>
					<?php endif;?>					
				</tr>	
				<?php $rowIndex++; ?>
				<?php endforeach;?>
				<?php if(!$packagePending) :?>
				<tr class="<?php echo $rowIndex & 1 ? 'odd' : 'even'; ?>">
					<td>						
					</td>
					<?php foreach($packages as $package) : ?>	
					<td style="">					
			        <?php if($package['id'] != Package::$_packageDefault) :?>
									<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Buy now'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('packages/beforbuy/package_id/'.$package['id']),
			                	
			                )
			            ); ?>				
					
					<?php endif;?>				
					</td>
					<?php endforeach;?>
				</tr>
				<?php endif;?>
			</table>	
</div>