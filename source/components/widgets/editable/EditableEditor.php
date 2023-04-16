<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableEditor extends EditableField 
{   
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'editor';
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->type = 'editor';
        
        Yii::app()->bootstrap->assetsRegistry->registerPackage('ckeditor');
                $this->options['onblur'] = 'ignore';
                $this->options['editorOptions']['emptytext'] = '<p>abc</p>';
                $this->options['editorOptions'] = array_merge(
                        $this->editorOptions,
                        array(
                            'language' => Yii::app()->language,
                        )
        );
        
        parent::init();
    }
    
}