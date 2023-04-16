<?php
    $news = News::model()->findByPk($params[0]);
    // validate
    if ($news->item_id != $this->website->company->item_id) {
        throw new CHttpException(403);
    }
?>

<h1><?php echo $news->title; ?></h1>

<em class="muted"><?php echo Yii::app()->dateFormatter->format("dd-MM-yyyy", $news->date); ?></em>

<?php if ($news->photo) {
	echo '<div>';
		echo Html::image($this->mapFile($news->photo->generateUrl('large'), 'news'), $news->title, array(
			'class' => 'img-polaroid',
			'style' => 'margin: 0 10px 10px 0'
		));
	echo '</div>';
} ?>

<div>
    <?php echo $news->content; ?>
</div>

<a href="##" onClick="history.go(-1); return false;">
    <?php echo Yii::t('CreatorsModule.navigation', 'Go back'); ?>
</a>
