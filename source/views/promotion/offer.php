<?php 
/**
 * Oferta banerowa.
 *
 * @category views
 * @package promotion
 * @author
 * @copyright (C) 2014
 */ 
?>


<div class="row">
    <div class="span6">
    <?php echo CHtml::image('images/branding/ad-boxes.jpg', '', array('style'=>'margin-left: 10px;')); ?>
	<?php //echo CHtml::image('images/branding/adv_01_up.gif', '', array('style'=>'margin-left: 10px;')); ?>
	</div>    
    <div class="span6">
    <?php $this->renderPartial('_adboxList') ?>     
	</div>
</div>