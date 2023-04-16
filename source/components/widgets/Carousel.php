<?php

Yii::import('bootstrap.widgets.TbCarousel');

/**
 * Widget karuzeli.
 * 
 * Zastąpiona funkcja renderItems. 
 * Dodana obsługa wyświetlania plansz w formie linków.
 * Modyfikacja na bazie biblioteki Yii-Booster
 * 
 * @category components
 * @package components\widgets
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php) 
 * @since 0.9.10
 */
class Carousel extends TbCarousel
{
	// link support
	protected function renderItems($items)
	{
		foreach ($items as $i => $item) {
			if (!is_array($item)) {
				continue;
			}

			if (isset($item['visible']) && $item['visible'] === false) {
				continue;
			}

			if (!isset($item['itemOptions'])) {
				$item['itemOptions'] = array();
			}

			$classes = array('item');

			if ($i === 0) {
				$classes[] = 'active';
			}

			if (!empty($classes)) {
				$classes = implode(' ', $classes);
				if (isset($item['itemOptions']['class'])) {
					$item['itemOptions']['class'] .= ' ' . $classes;
				} else {
					$item['itemOptions']['class'] = $classes;
				}
			}
                        
                        if (isset($item['itemOptions']['href'])) {
                            $link = true;
                            echo CHtml::openTag('a', $item['itemOptions']);
                        } else {
                            echo CHtml::openTag('div', $item['itemOptions']);
                        }

			if (isset($item['image'])) {
				if (!isset($item['alt'])) {
					$item['alt'] = '';
				}

				if (!isset($item['imageOptions'])) {
					$item['imageOptions'] = array();
				}

				echo CHtml::image($item['image'], $item['alt'], $item['imageOptions']);
			}

			if (!empty($item['caption']) && (isset($item['label']) || isset($item['caption']))) {
				if (!isset($item['captionOptions'])) {
					$item['captionOptions'] = array();
				}

				if (isset($item['captionOptions']['class'])) {
					$item['captionOptions']['class'] .= ' carousel-caption';
				} else {
					$item['captionOptions']['class'] = 'carousel-caption';
				}

				echo CHtml::openTag('div', $item['captionOptions']);

				if (isset($item['label'])) {
					echo '<h4>' . $item['label'] . '</h4>';
				}

				if (isset($item['caption'])) {
					echo '<p>' . $item['caption'] . '</p>';
				}

				echo '</div>';
			}
                        if (isset($link)) {
                            echo '</a>';
                        } else {
                            echo '</div>';
                        }
		}
	}
}
