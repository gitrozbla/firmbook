<?php
/**
 * Podstrona artykułu (news).
 *
 * @category views
 * @package news
 * @author
 * @copyright
 */

$item = $company->item;
$user = $item->user;
//print_r($user);
//exit;
?>
<div class="row">
	<?php require 'source/views/companies/_companyPanel.php'; ?>
	<?php $this->renderPartial('/companies/_companyLeft', compact('company', 'item', 'user')); ?>
	<div class="right-frame">
		<div class="span9">
			<?php if($this->editorEnabled): ?>
		    	<div class="editor-button pull-right">
		        <?php $this->widget(
		                'bootstrap.widgets.TbButton',
		                array(
		                    //'type' => 'primary',
		                    'icon' => 'fa fa-wrench',
		                    'label' => Yii::t('editor', 'Edit'),
		                	'url' => $this->createGlobalRouteUrl('news/update', array('id'=>$news->id)),
		                    //'url' => $this->createUrl('companies/update'),
		                    //'active' => $editor,
		                    'type' => 'success',
		                )
		        ); ?>
		    	</div>
		    <?php endif; ?>
		    <blockquote class="clearfix">
				<p><?php echo $news->title; ?></p>
				<footer><small><?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd", $news->date); ?>
				<cite title="Source Title"><?php echo Html::link($user->username, $this->createUrl('user/profile', array(
	                    'username' => $user->username)));?></cite></small></footer>
			</blockquote>

			<?php if (!$this->creatorsMode ) {
				if ($news->active && $item->active) {
					$this->renderPartial('/site/_socialButtons', array(
						'title' => $news->title,
						'url' => Yii::app()->createAbsoluteUrl('news/show', array('id'=>$news->id)),
						'description' => $news->description
					));
				}
			} ?>
		    <div class="clearfix"></div>
			<!-- <h1><?php echo $news->title; ?></h1>
			<small><?php echo $news->date; ?></small> -->

			<?php if ($news->photo) {
				echo '<div>';
					echo Html::image($news->photo->generateUrl('large'), $news->title, array(
						'class' => 'img-polaroid',
						'style' => 'margin: 0 10px 10px 0'
					));
				echo '</div>';
			} ?>

			<p><?php echo $news->content; ?></p>

			<?php
			/*	dodawanie nie zostąło skończone, wymaga rozwiązania problemu sortowania i wyszukiwania pod wielu tabelach
			 * 	item_id może wskazywać na tabele tbl_item, tbl_news, tbl_user
			 */
			/* if(!Yii::app()->user->isGuest) : ?>
			<hr />
			<div class="bottom-10 text-center">
			<span>
			<?php $this->renderPartial('/elists/_addToElistButtons',
				array(
					'itemId'=>$news->id,
					'itemType'=>Elist::ITEM_TYPE_NEWS,
					//'favoriteBtnStyle'=> 1 ? 'success' : '',
					'favoriteBtnStyle'=> Elist::model()->exists(
						'user_id=:user_id and item_id=:item_id and type=:type and item_type=:item_type',
						array(':user_id'=>Yii::app()->user->id, ':item_id'=>$news->id, ':type'=>Elist::TYPE_FAVORITE, ':item_type'=>Elist::ITEM_TYPE_NEWS))
						? 'success' : '',
					'elistBtnStyle'=> Elist::model()->exists(
						'user_id=:user_id and item_id=:item_id and type=:type and item_type=:item_type',
						array(':user_id'=>Yii::app()->user->id, ':item_id'=>$news->id, ':type'=>Elist::TYPE_ELIST, ':item_type'=>Elist::ITEM_TYPE_NEWS))
						? 'success' : '',
				)); ?>
			</span>
			</div>
			<?php endif;*/ ?>

		</div>
	</div><!-- div.right-frame -->
	
</div>
