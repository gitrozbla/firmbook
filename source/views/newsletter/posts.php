<?php 
/**
 * Lista postów newslettera.
 *
 * @category views
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<h1><?php echo Yii::t('newsletter', 'Posts'); ?> </h1>
<hr />
<?php echo Html::coolAjaxLink(
        '<i class="fa fa-plus"></i>&nbsp;'.Yii::t('newsletter', 'Write new post'),
        $this->createUrl('write_post')
        ); ?>
<hr />
<?php
    $widget = $this->widget('EditableListView', array(
        'id' => 'post-list',
        'dataProvider' => $post->search(),
        'itemView' => '_post',
    ));
?>