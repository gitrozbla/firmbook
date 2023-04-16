<?php 
/*
 * widok komentarazy facebooka 
 */
?> 
<?php /*<div id="fb-root"></div>*/ ?>
<?php /*<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.3&appId=<?php echo Yii::app()->params['facebook']['facebookId'];?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>*/ 
?>
<div class="fb-comments" data-href="<?php echo $url; ?>"
  data-numposts="5" data-colorscheme="light" data-width="100%"></div>

<?php /*<span class="fb-comments-count" data-href="<?php echo Yii::app()->createAbsoluteUrl('companies/show', array('name'=>$item->alias)); ?>"></span>
awesome comments*/ ?>