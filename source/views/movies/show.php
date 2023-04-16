<?php 
/**
 * Podstrona filmu.
 *
 * @category views
 * @package movies
 * @author
 * @copyright
 */ 

$item = $company->item;
$user = $item->user;

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
		                	'url' => $this->createGlobalRouteUrl('movies/update', array('id'=>$movie->id)),
		                    //'url' => $this->createUrl('companies/update'),
		                    //'active' => $editor,
		                    'type' => 'success',                    
		                )
		        ); ?>        
		    	</div>
		    <?php endif; ?>
		    <blockquote class="clearfix">
				<p><?php echo $movie->title; ?></p>
				<footer><small><?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd", $movie->date); ?> 
				<cite title="Source Title"><?php echo Html::link($user->username, $this->createUrl('user/profile', array(
	                    'username' => $user->username)));?></cite></small></footer>
			</blockquote>	
			<?php if (!$this->creatorsMode ) {
				if ($item->active) {
					$this->renderPartial('/site/_socialButtons', array(
						'title' => $movie->title,
						'url' => Yii::app()->createAbsoluteUrl('movies/show', array('id'=>$movie->id)),
						'description' => $movie->description
					));
				}
			} ?>
		    <div class="clearfix"></div>
		    
			<div class="video-container">
		    	<iframe width="100%" height="360px" src="//www.youtube.com/embed/<?php echo $movie->url ?>" frameborder="0" allowfullscreen></iframe>
			</div>
		    	
			<!-- <h1><?php echo $movie->title; ?></h1>
			<small><?php echo $movie->date; ?></small> -->
			<p><?php echo $movie->description; ?></p>		
			
		</div>
	</div><!-- div.right-frame -->
	
</div>