<?php
if ($this->previewMode || $this->page->comments) {

	$language = Yii::app()->language;
	switch($item->cache_type) {
		case 'c': $route = '/companies/show'; break;
		case 's': $route = '/services/show'; break;
		case 'p': $route = '/products/show'; break;
	}

	echo '
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "http://connect.facebook.net/'.$language.'_'.strtoupper($language).'/sdk.js#xfbml=1&version=v2.5";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, "script", "facebook-jssdk"));</script>

	<div class="comments" '.($this->page->comments ? '' : 'style="display:none;"').'>
		<hr />
		<h3>'.Yii::t('CreatorsModule.page', 'Comments and opinions').'</h3>
		<div id="fb-comments" class="fb-comments" data-numposts="5"
			data-firmbook-href="'.$this->createFirmbookUrl($route, array('name' => $item->alias)).'"
			data-comments-from-firmbook="'.$this->page->comments_from_firmbook.'"></div>
	</div>

	<script>
		var element = document.getElementById("fb-comments");
		var commentsFromFirmbook = element.getAttribute("data-comments-from-firmbook");
		element.setAttribute("data-href", commentsFromFirmbook == 1
			? element.getAttribute("data-firmbook-href")
			: window.location.href
		);
	</script>
	';
}
