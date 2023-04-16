<?php
/**
 * Artykuły w kontekście obiektu.
 *
 * @category views
 * @package news
 * @author
 * @copyright (C) 2015
 */
?>
<?php
	$item = $company->item;
?>

<?php Yii::app()->clientScript->registerScript(
    'item-list-thumbnail-hover',
    "$('#item-list .thumbnail').popover({"
        . "'trigger': 'hover',"
        . "'html': true"
    . "});"
); ?>

<div class="row">
	<?php require 'source/views/companies/_companyPanel.php'; ?>
	<?php $this->renderPartial('/companies/_companyLeft', compact('company', 'item')); ?>
	<div class="right-frame">
		<div class="span9">
        <?php $this->renderPartial('_newsList', compact('company', 'news')); ?>
   		</div>
	</div><!-- div.right-frame -->
</div><!-- div.row -->
