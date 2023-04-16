<?php

class CreatorsModule extends CWebModule
{
    public $layout = '/layouts/main';
    
    public $defaultController = 'site';
    
    public function init()
    {
        // Set required classes for import.
        $this->setImport(array(
            'creators.components.*',
            'creators.models.*',
        ));
    }
}
