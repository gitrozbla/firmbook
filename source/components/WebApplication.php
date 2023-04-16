<?php
/**
 * Aplikacja. Główny obiekt całego systemu.
 * 
 * Obiekt jest tworzony w index.php .
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class WebApplication extends CWebApplication
{
    /*// lista coolAjax callback
    protected $callbackArray = null;


    // rozszerza o możliwość dodania kilku event handlerów jednocześnie w config
    public function __set($name, $value)
    {
            if(strncasecmp($name,'on',2)===0 and substr_compare($name, 'Array', -5, 5)===0 and method_exists($this, substr($name, 0, -5))) 
            {
                    $name = substr($name, 0, -5);
                    foreach($value as $handler)
                    {
                            $this->__set($name, $handler);
                    }
            }
            else return parent::__set($name, $value);
    }


    public function beforeControllerAction($controller,$action) 
    {
            // obsługa coolAjax callback - init
            if (Yii::app()->request->isCoolAjaxRequest
                    // or isset($_GET['ajax']) or isset($_POST['ajax'])
            ) {
                    if (!isset(Yii::app()->session['coolAjaxWidgetCallback'])) {
                            echo CJSON::encode(array('status'=>'refresh'));
                            Yii::app()->end();
                    }
                    $this->callbackArray = Yii::app()->session['coolAjaxWidgetCallback'];

                    // Czy klasa obsługuje coolAjax. Jeśli nie to każemy przekierować
                    if (is_subclass_of(Yii::app()->controller, 'Controller') == false) {
                            header('Content-type: application/json');
                            // wyłaczanie logów
                            Yii::app()->disableWebLogs();
                            echo CJSON::encode(array('status'=>'avoidCoolAjax'));
                            Yii::app()->end();
                    }
            }
            else 
            {
                    $this->callbackArray = array();
            }

            // true, aby zezwolić na wykonanie akcji
            return true;
    }

    public function afterControllerAction($controller,$action) 
    { 
            // coolAjax - zapamiętanie listy callback
            if (Yii::app()->request->isCoolAjaxRequest == false) {
                    Yii::app()->session['coolAjaxWidgetCallback'] = $this->callbackArray;
            }
    }


    // obsługa coolAjax callback - register callback
    public function coolAjaxRegister($includePath, $id, $callback)
    {
            // jeżeli jest to zapytanie coolAjax to ignorujemy rejestrowanie
            if (Yii::app()->request->isCoolAjaxRequest) return;

            // czy na liście callback nie znajduje się jeszcze to id
            if (!isset($this->callbackArray[$id]))
            {
                    $this->callbackArray[$id] = array('path'=>$includePath, 'callback'=>$callback);
            }
    }

    // obsługa coolAjax callback - process callbacks
    public function coolAjaxProcessAll()
    {
            $result = array();
            foreach($this->callbackArray as $key=>$value)
            {
                    Yii::import($value['path'], true);
                    $result[$key] = call_user_func($value['callback']);
            }
            return $result;
    }*/
    
    /**
     * Kończy działanie aplikacji.
     * Automatycznie wycina logi na stronie gdy zapytanie jest typu ajax.
     * @param int $status Kod statusu HTTP.
     * @param boolean $exit Czy zakończyć funkcją exit().
     */
    public function end($status=0, $exit=true)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $this->disableWebLogs();
        }

        parent::end($status, $exit);
    }


    /**
     * Wyłącza wyświetlanie logów na stronie.
     */
    public function disableWebLogs()
    {
            foreach ($this->log->routes as $route) {
                    if ($route instanceof CWebLogRoute) {
                            $route->enabled = false;
                    }
            }
    }
		
}
