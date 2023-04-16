<?php

Yii::import('bootstrap.widgets.TbButton');

class Button extends TbButton 
{
    public function init()
    {
        if ($this->disabled) {
            $this->url = '#';
            $script = 'return false;';
            if (isset($this->htmlOptions['onclick'])) {
                if (substr(trim($this->htmlOptions['onclick']), -1) == ';') {
                    $this->htmlOptions['onclick'] .= $script;
                } else {
                    $this->htmlOptions['onclick'] .= ';'.$script;
                }
            } else {
                $this->htmlOptions['onclick'] = $script;
            }
        }
        
        parent::init();
    }
    
}
