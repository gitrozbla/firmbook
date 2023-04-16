<?php
	$pos = $socialIconsLocation;
	$visibleVariableName = $pos.'_social_icons';
	$visible = $website->$visibleVariableName;

	if ($this->previewMode || $visible) {
		$cs->registerCssFile($this->mapFile('/js/vendor/share-button/share-button.min.css'), '', true);
		$cs->registerScriptFile($this->mapFile('js/vendor/share-button/share-button.min.js'), CClientScript::POS_END);
		$cs->registerScriptFile($this->mapFile('js/create-share-buttons.js'), CClientScript::POS_END);

	echo '<div class="social-icons pull-right" '.($visible ? '' : 'style="display:none"').'>
		<share-button
			id="'.$pos.'-share-button"
			data-button-text="'.htmlentities($website->social_icons_title).'"
			data-default-text="'.Yii::t('CreatorsModule.website', 'tell a friend').'"
			data-networks="'.implode(',', $website->social_icons_networks).'"
		></share-button>
	</div>';
}
