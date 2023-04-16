<?php

Yii::import('ext.morris-js.MorrisChartWidget', true);

class Chart extends MorrisChartWidget
{
    public $select = null;
    
    /**
     * Przerobione pod kątem dodatkowych funkcjonalności.
     */
    public function run()
    {
        $id                      = $this->getId();
        $this->htmlOptions['id'] = $id;

        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::closeTag('div');

        $defaultOptions = array();
        $this->options  = CMap::mergeArray($defaultOptions, $this->options);
        $this->options['element'] = $id;
        $jsOptions      = CJavaScript::encode($this->options);

        $chartType = $this->options['chartType'];

        // added
        if ($this->select !== null) {
            $selection = '.select('.$this->select.')';
        } else {
            $selection = '';
        }
        // end added
        // modified
        $this->registerScripts(
                __CLASS__ . '#' . $id, 
                "function morris_".$id."() {
                    $('#".$id."').empty();
                    Morris.{$chartType}($jsOptions)".$selection.";
                }
                morris_".$id."();
                $( window ).resize(function() {
                    morris_".$id."();
                });
                "
                );
        
    }

}