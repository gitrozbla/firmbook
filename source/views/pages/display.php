<?php 
/**
 * Zwykła podstrona (artykuł).
 *
 * @category views
 * @package pages
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php if (!empty($article->title)) : ?>
    <h1><?php echo Yii::t('article.title', $article->title, array(), 'dbMessages'); ?></h1>
    <?php if($article->alias=='eu-projects'): ?>
    	<h5 style="color:#666;">
    	<?php //$currentDate = new DateTime(); echo ' - '.$currentDate->format('Y-m-d'); ?>
    	<?php   	
	    	$first  = $article->date;
	    	$first  = new DateTime($first);
	    	$first  = new DateTime($first->format('Y-m-d'));
	    	$second = new DateTime(date('Y-m-d'));    	
	    	$diff = $first->diff( $second );    	
	    	echo Yii::t('pages', 'Added {days} days ago', array('{days}'=>$diff->format('%d')));    	
    	?>
    	</h5>
    <?php endif; ?>
    
<?php endif; ?>
<br />
<?php if (!empty($article->content)) : ?>
    <?php echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}' => $article->content), 'dbMessages'); ?>
<?php else : ?>
    <?php echo Yii::t('pages', 'Content will be filled soon...'); ?>
<?php endif; ?>