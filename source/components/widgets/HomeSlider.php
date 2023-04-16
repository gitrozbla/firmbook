<?php
/**
 * Widget du¿ejkaruzeli na stronie g³ównej.
 *
 * Wyœwietla pole z przewodnikiem.
 *
 * @category components
 * @package components\widgets
 */
class HomeSlider extends CWidget
{
    public $height = 400;
		public $articlesGroup = '';
		public $slides = array();

    /**
     * Renderuje widget.
     * W zale¿noœci od typu i ID grupy wyœwietla link do oferty,
     * baner, film, karuzele b¹dŸ inne.
     */
    public function run()
		{
			// dane
			$articles = Article::model()->findAll('alias LIKE "'.$this->articlesGroup.'%"');

			if (!empty($articles)) {

				// render

				echo '<div
					id="home-slider-'.$this->id.'"
					class="home-slider home-slider-'.$this->articlesGroup.' hero-unit"
					data-slides="'.count($articles).'"
					data-current-slide="1"
				>';

				$counter = 1;
				foreach ($articles as $article) {
					echo '<div class="slide-'.$counter.($counter == 1 ? ' visible' : '').'">';
					echo '<div class="slide-content">';
					echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages');
					echo '</div>';
					echo '</div>';

					$counter++;
				}

				echo '</div>';


				// script
				Yii::app()->clientScript->registerScriptFile(Yii::app()->homeUrl.'js/home-slider.js?v=2.2');
        /*$cs->registerScript('home-slider-'.$this->id,
          "$('#home-slider-".$this->id."').homeSlider();"
        );*/

			}

    }

}
