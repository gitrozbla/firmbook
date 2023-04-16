<?php

Yii::import('bootstrap.widgets.TbListView');

/**
 * ListView z obsługą EditableField.
 * 
 * Wyświetla listę z możliwościa edycji pól.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableListView extends TbListView 
{
    /**
     * Inicjuje listę.
     * Po części kopia z TbListView.
     * Dodatkowo dodaje obsługę skryptów w wierszach 
     * (potrzebne dla działania edytowalnych pól).
     */
    public function init()
    {
        $popover = Yii::app()->bootstrap->popoverSelector;
        $tooltip = Yii::app()->bootstrap->tooltipSelector;

        // ajax of TbListView + support of scripts as data in div
        $afterAjaxUpdate = "js:function(id, data) {
            jQuery('.popover').remove();
            jQuery('{$popover}').popover();
            jQuery('.tooltip').remove();
            jQuery('{$tooltip}').tooltip();

            jQuery('#'+id+' .ajax-script').each(function(){
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.text = $(this).attr('data-script');
                $(this).replaceWith(s);
            });
        }";

        if (!isset($this->afterAjaxUpdate)) {
            $this->afterAjaxUpdate = $afterAjaxUpdate;
        }
        
        parent::init();
    }
    
}
