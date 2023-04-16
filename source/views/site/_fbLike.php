<?php 
/*
 * facebook like button 
 */
?> 

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.3&appId=<?php echo Yii::app()->params['facebook']['facebookId'];?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like pull-right bottom-10" data-href="<?php echo $url; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>

<?php /*><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.3&appId=<?php echo Yii::app()->params['facebook']['facebookId'];?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like" data-href="<?php echo $url; ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
*/?>

<?php /*<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.3&appId=<?php echo Yii::app()->params['facebook']['facebookId'];?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-comments" data-href="<?php echo $url; ?>" data-numposts="5" data-colorscheme="light"></div>
*/?>
 