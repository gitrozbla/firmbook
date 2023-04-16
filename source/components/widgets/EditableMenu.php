<?php

Yii::import('bootstrap.widgets.TbMenu', true);

/**
 * Menu z obsługą EditableField.
 * 
 * Wyświetla menu z możliwościa edycji pól.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableMenu extends TbMenu 
{
    /**
     * Inicjuje menu.
     * Dodaje obsługę edytowalnych pól.
     */
    public function init()
    {
        parent::init();
        
        $cs = Yii::app()->clientScript;
        $cs->registerCssCrushFile(
            Yii::app()->basePath.'/../css/editable.css', '', true
        );
        
        $cs->registerScriptFile(
            'js/my_editable.js'
        );
        $cs->registerScript(
            'editable-menu-'.$this->id,
            "$('#".$this->id."').editableMenu();",
            ClientScript::POS_READY
        );
        
        if (isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] .= ' editable-menu';
        } else {
            $this->htmlOptions['class'] = 'editable-menu';
        }
        
        
        /*foreach($this->items as $key=>$value) {
            if (isset($value['items'])) {
                foreach($value['items'] as $key2=>$value2) {
                    if (isset($value2['editableApply']) and $value2['editableApply'] == false) {
                        echo 'a';
                        if (!isset($value2['linkOptions'])) {echo 'b';
                            $this->items[$key][$key2]['itemOptions'] = array(
                                'data-editable-apply' => '0',
                            );
                        } else {
                            $this->items[$key][$key2]['itemOptions']['data-editable-apply'] = '0';
                        }
                    }
                }
            }
        }*/
        
        /*foreach($this->items as $key=>$value) {
            $this->items[$key]['items'] []= array(
                'label' => '<i class="fa fa-plus"></i>',
                'url' => '#',
                'active' => false,
                'itemOptions' => array(
                    'class' => 'editable-add-button'
                ),
            );
        }
        
        $this->items []= array(
            'label' => '<i class="fa fa-plus"></i>',
            'url' => '#',
            'active' => false,
            'itemOptions' => array(
                'class' => 'editable-add-button'
            ),
        );*/
    }
    
}
