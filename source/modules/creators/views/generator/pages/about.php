<?php

$company = $page->website->company;
$item = $company->item;



echo '<div>';

	// gallery
	if (!empty($item->files)) {
		echo '<div class="pull-right">';
			echo '<div class="company-gallery">';
				$files = array();
				foreach($item->files as $file) {
					echo '<div class="img-polaroid bottom-10">'
						.Html::link(
							Html::image(
								$this->mapFile($file->generateUrl('medium'), 'company'),
								$company->item->name
							),
							$this->mapFile($file->generateUrl('large'), 'company'),
							array(
								'data-lightbox' => 'roadtrip',
								'title' => $item->name
							)
						)
						.'</div>';
				}
			echo '</div>';
		echo'</div>';
	}

	// company data
	if ($this->previewMode || $this->page->company_data) {
		echo '<div class="pull-left company-data" '.($this->page->company_data ? '' : 'style="display:none;"').'>';
			$this->renderPartial('//companies/_companyData', compact('company', 'item'));
		echo '</div>';
		echo '<div style="clear:left;"></div>';
		echo '<hr />';
	}

	// description
	echo '<div>'.$item->description.'</div>';
	
	$this->renderPartial('layouts/partials/comments', compact('item', 'page'));

echo '</div>';
