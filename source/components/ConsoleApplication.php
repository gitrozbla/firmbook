<?php

class ConsoleApplication extends CConsoleApplication
{
    private $_controller = false;
    protected $_layoutPath = null;

    public function getController(){
        if ($this->_controller === false)
        $this->_controller = new Controller('cron');

        return $this->_controller;
    }

    public function getViewRenderer(){
        return null;
    }

    public function getViewPath(){
        return $this->getBasePath().DIRECTORY_SEPARATOR.'views';
    }
    public function getTheme(){
        return NULL;
    }

    public function getLayoutPath()
    {
        if($this->_layoutPath!==null)
            return $this->_layoutPath;
        else
            return $this->_layoutPath=$this->getViewPath().DIRECTORY_SEPARATOR.'layouts';
    }
}
