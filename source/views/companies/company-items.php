<?php 
/**
 * Strona firmy.
 *
 * @category views
 * @package company
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
    $item = $company->item;   
?>
<div class="row">
	<?php require '_companyPanel.php'; ?>
	<?php $this->renderPartial('_companyLeft', compact('company', 'item')); ?>	
	<div class="right-frame">
		<div class="span9">
        <?php $this->renderPartial('_itemsList', compact('company', 'type')); ?>
   		</div>		
	</div><!-- div.right-frame -->
</div><!-- div.row -->
