<?php

/**
 * Edytowalne pole.
 *
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableSkype extends EditableField
{
		public function init()
    {
        $this->type = 'text';

        parent::init();
    }

    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField()
    {
        /*return Html::link(
                $this->_value,
                'mailto:'.$this->_value
            );   */
				if (!empty($this->_value)) {
					return Html::skypeWidget($this->_value);
				} else {
					return $this->emptytext;
				}
    }

}
