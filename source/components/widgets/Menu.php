<?php

Yii::import('bootstrap.widgets.TbMenu', true);

class Menu extends TbMenu
{
    public $badge = null;
    
    public function init()
    {
        foreach($this->items as $itemKey=>$item)
        {
            if (isset($item['badge'])) {
                if (is_string($item['badge'])) {
                    $item['badge'] = array('label'=>$item['badge']);
                }
                $this->items[$itemKey]['label'] .= ' '.$this->widget(
                        'bootstrap.widgets.TbBadge', 
                        $item['badge'],
                        true
                        );
            }
        }
        
        parent::init();
    }
    
}