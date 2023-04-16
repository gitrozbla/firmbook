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
    $updateUrl = $this->createUrl('companies/update', array('id'=>$item->id));
    $companyUpdateUrl = $this->createUrl('companies/update', array('model'=>'Company'));
    $user = $item->user;
    $itemUpdateUrl = $this->createUrl('companies/partialupdate', array('model'=>'Item'));
?>
<?php if ($item->facebook || $item->facebook_profile || $company->allow_comment && !$this->creatorsMode): ?>
	<div id="fb-root"></div>
	<?php /*<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.10&appId=<?php echo Yii::app()->params['facebook']['facebookId'];?>";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>*/ ?>
	
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.3&appId=<?php echo Yii::app()->params['facebook']['facebookId'];?>";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script> 
<?php endif; ?>
<div class="row">

	<?php require '_companyPanel.php'; ?>
	<?php $this->renderPartial('_companyLeft', compact(
                    'company', 'item', 'updateUrl', 'productCount', 'serviceCount', 'newsCount', 'user')); ?>
	<?php /*?><div class="right-frame"><? */ ?>
		<div class="span9">
			<?php if (!$this->creatorsMode && $item->active) {
					$this->renderPartial('/site/_socialButtons', array(
                        'title' => $item->name,
    	                'url' => Yii::app()->createAbsoluteUrl('companies/show', array('name'=>$item->alias)),
                        'description' => $company->short_description
		    	));
			} ?>
			
			<?php /*if($this->editorEnabled): ?>
			<div class="editor-button pull-right">
	        <?php $this->widget(
	                'bootstrap.widgets.TbButton',
	                array(
	                    //'type' => 'primary',
	                    'icon' => 'fa fa-wrench',
	                    'label' => Yii::t('editor', 'Edit'),
	                	'url' => $updateUrl,
	                    //'url' => $this->createUrl('companies/update'),
	                    //'active' => $editor,
	                    'type' => 'success',

	                )
	        ); ?>
    		</div>
    		<div class="clearfix"></div>
    		<?php endif;*/?>

		    <?php /*<div class="smart-box title clearfix"><?php //echo $item->getPackageItemClass();?>
		    	<?php //echo $user->badge(); ?>
				<div class="pull-right"><?php echo $item->badge2(); ?></div>
			    <h1><?php echo $item->name;?></h1>
				<div class="txt"><?php echo $item->description;?></div>
			</div>*/ ?>

			<?php $this->renderPartial('_companyData', compact('company', 'item')); ?>

		<?php
			$search = Search::model()->getFromSession();
            //$user = Yii::app()->user->getModel();
            $action = $search->action;
        ?>


        <div class="smart-box title clearfix">
    		<h2><?php echo Yii::t ( 'item', 'Description' ); ?></h2>
            <div class="txt"><?php echo $item->description; ?></div>
        </div>


        <?php
            $type = 'product';
            $itemCount = $productCount;
            $this->renderPartial('_itemsBoxList', compact(
                    'company', 'action', 'type', 'itemCount'));
        ?>
        <?php
            $type = 'service';
            $itemCount = $serviceCount;
            $this->renderPartial('_itemsBoxList', compact(
                    'company', 'action', 'type', 'itemCount'));
        ?>
        <?php
            $type = 'news';
            $itemCount = $newsCount;
            $this->renderPartial('_newsBoxList', compact(
                    'company', 'itemCount'));
        ?>
        <?php if ($company->allow_comment && !$this->creatorsMode) : ?>
	        <?php $this->renderPartial('/site/_fbComments', array(
		    	'url' => Yii::app()->createAbsoluteUrl('companies/show', array('name'=>$item->id))
	    	)); ?>
		<?php endif; ?>

    	<?php /*$this->widget('comments.widgets.ECommentsListWidget', array(
	    	'model' => $company,
			'htmlOptions' => array(
				'class' => 'span9'
			)
		));*/?>

		</div>
	<?php /*</div> */?>
	<!-- div.right-frame -->
</div>
<!-- div.row -->
