<?php 
/**
 * Lista filmów firmy.
 *
 * @category views
 * @package company
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
    $item = $company->item; 
    //$fileUpdateUrl = $this->createGlobalRouteUrl('companies/partialupdate', array('model'=>'UserFile'));
?>

<div class="row">
	<?php require 'source/views/companies/_companyPanel.php'; ?>
	<?php $this->renderPartial('/companies/_companyLeft', compact('company', 'item')); ?>	
	<div class="right-frame">
		<div class="span9">
		<?php if($this->editorEnabled) : ?>
		<div class="editor-button pull-right">    
		    <?php $this->widget(
		        'Button',
		        array(
		            'label' => Yii::t('movies', 'Add movie'),
		            'type' => 'success',
		            'icon' => 'fa fa-plus',
		        	'url' => $this->createGlobalRouteUrl('movies/add',
		        		array('name'=>$company->item_id)),
		            //'url' => $this->createGlobalRouteUrl($controller.'/add'),
		            //'disabled' => $addButtonDisabled,
		        )
		    ); ?>           
		</div>    
		<div class="clearfix"></div>
		<?php endif; ?>
		<?php 
			echo CHtml::openTag('div', array('class' => 'row-fluid'));
			//echo CHtml::openTag('div', array('class' => 'row-fluid'));
			$this->widget(
					'bootstrap.widgets.TbThumbnails',
					array(
							'dataProvider' => Movie::moviesDataProvider($item->id),
							'template' => "{items}\n{pager}",
							'itemView' => '_moviesListItem',
							//'itemView' => 'application.views.widgets.grouping._thumb',
					)
			);
			echo CHtml::closeTag('div');
		?>
		</div>		
	</div><!-- div.right-frame -->
</div><!-- div.row -->
