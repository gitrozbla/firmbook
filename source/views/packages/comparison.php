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
?>
<?php if (Yii::app()->user->isGuest == false) {
                require '_packageDetails.php';
            } ?>
            
<div class="well center">            
    <h1><?php echo Yii::t('packages', 'Packages'); ?></h1>
    <table style="" id="ads-buy">
        <tr class="odd head">
            <td class="service-desc"></td>
            <?php foreach($packages as $package) : ?>
                <?php /*<td class="package clearfix package-buy-<?php echo $package['css_name']; ?> package-list-label">*/ ?>
                <td class="packages-buy">
                    <?php // echo $package['name'] ?>
                    <?php echo Package::badge($package['name'], $package['css_name'], true); ?>
                    <?php /*echo Yii::t('packages', $package['name']);*/ ?>
                </td>
            <?php endforeach;?>					
        </tr>
        <?php if(!$packagePaid) :?>
        <tr class="odd buttons">
            <td></td>
            <?php foreach($packages as $package) : ?>	
            <td style="width: 120px">					
        <?php if($package['id'] != Package::$_packageDefault
                        && $package['id'] != Package::$_creatorsPackageDefault) :?>
                        <?php if($package['test_period'] && $canTest):?>
                    <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                                'buttonType' => 'link',
                                //'buttonType' => 'submit',
                                'label' => Yii::t('packages', 'Test ({period} days)', array('{period}'=>$package['test_period'])),
                            //'label' => Yii::t('packages', 'Test').' '.$package['test_period'].,
                            'type' => 'success',
                            'url' => $this->createUrl('packages/change/package/'.$package['id'].'/option/test'),
                        )
                    ); ?>
                                                <?php else: ?>
                                                        <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                                'buttonType' => 'link',
                                //'buttonType' => 'submit',
                            'label' => Yii::t('packages', 'Buy now'),
                            'type' => 'primary',
                            'url' => $this->createUrl('packages/change/package/'.$package['id']),
                        )
                    ); ?>
            <?php endif;?>	
                <?php endif;?>				
                </td>
                <?php endforeach;?>					
        </tr>
        <?php endif;?>
        <?php $rowIndex = 0; ?>
        <?php foreach($services as $service) : ?>				
        <tr class="<?php echo $rowIndex & 1 ? 'odd' : 'even'; ?>">
                <td style="">						
                        <?php //echo Yii::t('package.service.content', '{'.$service['name'].'}', array('{'.$service['name'].'}'=>$service['description']), 'dbMessages');?>						
                        <?php echo Yii::t('package.service.title', $service['name'], array(), 'dbMessages');?>
                        <?php //echo Yii::t('package.service.content', $service['name'], array(), 'dbMessages');?>
                        <?php /*$this->widget(
                                'bootstrap.widgets.TbBadge',
                                array(
                                        'type' => 'info',
                                        'label' => '?',
                                        //'encodeLabel'=>true,
                                        'htmlOptions' => array(
                                                'class' => 'pull-right',
                                                'data-content' => 
                                                        Yii::t('package.service.content', '{'.$service['name'].'}', array('{'.$service['name'].'}'=>$service['description']), 'dbMessages'),
                                                        'data-toggle' => 'popover',
                                                        'data-trigger' => 'hover',
                                        )				
                                )	
                        );*/ ?>
                        <?php if($service['description']) : ?>
                                <?php $this->widget(
                                        'bootstrap.widgets.TbLabel',
                                        array(
                                                'type' => 'info',
                                                'label' => '?',
                                                //'encodeLabel'=>true,
                                                'htmlOptions' => array(
                                                        'class' => 'pull-right',
                                                        'data-content' => 
                                                                Yii::t('package.service.content', '{'.$service['name'].'}', array('{'.$service['name'].'}'=>$service['description']), 'dbMessages'),
                                                                'data-toggle' => 'popover',
                                                                'data-trigger' => 'hover',
                                                )				
                                        )	
                                ); ?>
                        <?php endif; ?>
                </td>					
                <?php if($service['value_type'] == 1) : ?>
                        <?php foreach($packages as $package) : ?>
                <td style="text-align:center;">
                        <?php if(isset($package['services'][$service['id']])) : ?>
                                <?php if(isset($package['services'][$service['id']]['threshold']) && $package['services'][$service['id']]['threshold']) : ?>
                                <?php echo $package['services'][$service['id']]['threshold']; ?>
                                <?php else : ?>
                                <?php echo Yii::t('packages', 'No limit'); ?>
                                <?php echo $package['services'][$service['id']]['threshold']; ?>

                                <?php endif; ?>
                        <?php else : ?>
                        -
                        <?php endif; ?>
                </td>
                        <?php endforeach;?>
                <?php else : ?>
                        <?php foreach($packages as $package) : ?>
                <td style="text-align:center;">
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
        <?php if(!$packagePaid) :?>
        <tr class="odd buttons">
                <td>						
                </td>
                <?php foreach($packages as $package) : ?>	
                <td style="">					
        <?php if($package['id'] != Package::$_packageDefault
                        && $package['id'] != Package::$_creatorsPackageDefault) :?>
                        <?php if($package['test_period'] && $canTest):?>
                    <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                                'buttonType' => 'link',
                                //'buttonType' => 'submit',
                            'label' => Yii::t('packages', 'Test ({period} days)', array('{period}'=>$package['test_period'])),
                            'type' => 'success',
                            'url' => $this->createUrl('packages/change/package/'.$package['id'].'/option/test'),
                        )
                    ); ?>
                                                <?php else: ?>
                                                        <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                                'buttonType' => 'link',
                                //'buttonType' => 'submit',
                            'label' => Yii::t('packages', 'Buy now'),
                            'type' => 'primary',
                            'url' => $this->createUrl('packages/change/package/'.$package['id']),

                        )
                    ); ?>
            <?php endif;?>
                <?php endif;?>				
                </td>
                <?php endforeach;?>
        </tr>
        <?php endif;?>
    </table>	
</div>