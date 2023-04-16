<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableCheckbox extends EditableField 
{   
    /**
     * Opcja zaznaczona na czerwono.
     * @var string
     */
    public $red = null;
    /**
     * Opcja zaznaczona na niebiesko.
     * @var string
     */
    public $blue = null;
    /**
     * Jeśli != null to przy wybranej wartości fragment 
     * strony staje się półprzeźroczysty.
     * @var string|null
     */
    public $fade = null;
    /**
     * Selektor fragmentu strony, który ma być półprzeźroczysty.
     * @var string
     */
    public $fadeSelector = null;
    /**
     * Selektor najbliższego fragmentu strony (rodzica), 
     * który ma być półprzeźroczysty.
     * @var string
     */
    public $closestFadeSelector = null;
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'checkbox';
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        if ($this->type == null) {
            $this->type = 'checkbox';
        }
        
        if (empty($this->source)) {
            $this->source = array(
                '0' => Yii::t('editable', 'No'), 
                '1' => Yii::t('editable', 'Yes')
            );
        }
        
        $attribute = $this->attribute;
        $this->options = array_merge($this->options, array(
            'red' => $this->red,
            'blue' => $this->blue,
            'fade' => $this->fade, 
            'fadeClassPostfix' => $this->id,
            // pass options direct to toggle input
            'url' => $this->url,
            'attribute' => $this->attribute,
            'value' => $this->_value,
            'pk' => $this->model->primaryKey,
            'csrfToken' => Yii::app()->request->csrfToken,
            //'size' => $this->toggleSize,
        ));
        if (!empty($this->fadeSelector)) {
            $this->options['fadeSelector'] = $this->fadeSelector;
        }
        if (!empty($this->closestFadeSelector)) {
            $this->options['closestFadeSelector'] = $this->closestFadeSelector;
        }
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        $value = $this->_value;
        
        /*if (empty($this->source)) {
            $this->source = array(
                '0' => Yii::t('editable', 'No'), 
                '1' => Yii::t('editable', 'Yes')
            );
        }*/

        /*if ($value) {
            $result = '<i class="fa fa-check-square-o"></i> '.$this->source[$value];
        } else {
            $result = '<i class="fa fa-square-o"></i> '.$this->source[$value];
        }*/
        $result = $this->source[$value];

        if ($this->red !== null and $this->red == $value) {
            $result = '<span class="red">'.$result.'</span>';
        } else if ($this->blue !== null and $this->blue == $value) {
            $result = '<span class="blue">'.$result.'</span>';
        }

        if ($this->fade !== null and $this->fade == $value) {
            if (!empty($this->fadeSelector) or !empty($this->closestFadeSelector)) {
                Yii::app()->clientScript->registerScript('editable-fade-'.($this->id), 
                        (!empty($this->fadeSelector) ? 
                            "$('".$this->fadeSelector."').addClass('fade-medium');" : "")
                        .(!empty($this->closestFadeSelector) ? 
                            "$('#editable-".$this->id."').closest('".$this->closestFadeSelector."').addClass('fade-medium');" : "")
                        );
                $result = '<span id="editable-'.$this->id.'">'.$result.'</span>';
            } else {
                $result = '<span class="fade-medium">'.$result.'</span>';
            }
        }

        return $result;  
    }
    
}