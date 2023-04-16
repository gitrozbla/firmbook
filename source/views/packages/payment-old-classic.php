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
<h1><?php echo Yii::t('packages', 'Packages'); ?></h1>
			<table width="100%" style="" id="ads-buy">
				<tr class="odd">
					<td><?php echo Yii::t('packages', 'Package'); ?></td>
					<td><?php echo Yii::t('packages', 'Price'); ?></td>
					<td><?php echo Yii::t('packages', 'Status'); ?></td>
					<td><?php echo Yii::t('packages', 'Date from'); ?></td>
					<td><?php echo Yii::t('packages', 'Expires on'); ?></td>
					<td></td>
				</tr>
				<?php $rowIndex = 0; ?>
				<?php foreach($history as $purchase) : ?>				
				<tr class="<?php echo $rowIndex & 1 ? 'odd' : 'even'; ?>">
					<td>
					<?php echo $purchase['package']['name']; ?>
					<?php if($purchase['package_id'] != Package::$_packageDefault) : ?>
						<?php echo ' \ '.$purchase['period'].' miesiące'; ?>
					<?php endif; ?>					 
					</td>				
					<td>
					<?php if($purchase['price'] != 0) 
							  	echo $purchase['price'].' PLN';	
							  else
							  	echo 'za darmo';
					?>
					</td>
					<td>
					<?php 
						if($purchase['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
							echo 'oczekuje'; 
						elseif($purchase['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'])
							echo 'aktualny'; 
						elseif($purchase['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_CANCELED'])
							echo 'anulowany'; 
						elseif($purchase['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'])
							echo 'wygasł'; 
						elseif($purchase['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])
							echo 'opłacony';
					?>
					</td>
					<td>
					<?php if($purchase['package_id'] == Package::$_packageDefault) : ?>
						<?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd", $purchase['date_added']); ?>
					<?php else: ?>
						<?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd", $purchase['date_start']); ?>
					<?php endif; ?>
					</td>
					<td>
					<?php if($purchase['package_id'] == Package::$_packageDefault) : ?>
						<?php echo 'bez ograniczeń'; ?>
					<?php else: ?>
						<?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd", $purchase['date_expire']); ?>
					<?php endif; ?>
					</td>
					<td>
					<?php if($purchase['status'] == Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING']):?>
						<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Pay'),
			                    'type' => 'primary',
			                    
			                	'url' => $this->createUrl('packages/paymentconfirm/id/'.$purchase['id']),
			                	
			                )
			            ); ?>
			        <?php endif; ?>    
					</td>					
				</tr>
				<?php $rowIndex++; ?>
				<?php endforeach;?>
			</table>	
		
			