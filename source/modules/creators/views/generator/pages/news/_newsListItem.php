<?php
	$url = $this->mapPage($this->page->alias.'/'.$data->id);
?>

<li>
		<div class="inline column-thumbnail">
				<?php if ($data->photo != null) : ?>
						<?php echo Html::link(
										Html::image($this->mapFile($data->photo->generateUrl('medium'), 'news'), $data->title),
										$url,
										array(
												'class' => 'thumbnail popover-trigger',
												'data-content' => Html::image(
																$this->mapFile($data->photo->generateUrl('large'), 'news'),
																'',
																array('style'=>'height: 200px;'))
														.'<br />'.$data->title,
												)); ?>
				<?php endif; ?>
		</div>

		<div class="inline column-title">
	    <h3>
	        <?php echo Html::link($data->title, $url); ?>
	    </h3>
	    <em class="muted"><?php echo Yii::app()->dateFormatter->format("dd-MM-yyyy", $data->date); ?></em>

	    <p><?php echo $data->description ?></p>

		</div>
</li>

<?php /*<hr />*/ ?>
