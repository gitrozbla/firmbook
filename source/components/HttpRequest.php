<?php
/*
 * Wyprowadzona w celu wyłączenia sprawdzenia tokenu CSRF pod DOTPAY
 */
class HttpRequest extends CHttpRequest
{
    public $noCsrfValidationRoutes=array();

	protected function normalizeRequest()
    {
        //attach event handlers for CSRFin the parent
        parent::normalizeRequest();
        //remove the event handler CSRF if this is a route we want skipped
        if($this->enableCsrfValidation)
        {
        	//poniewaz parseUrl wymaga połączenia z bazą danych użyto metody z parent
        	$url=Yii::app()->getUrlManager()->parseUrlParent($this);
        	//$url=Yii::app()->getUrlManager()->parseUrl($this);
            foreach($this->noCsrfValidationRoutes as $route)
            {
                if(strpos($url,$route)===0)
                    Yii::app()->detachEventHandler('onBeginRequest',array($this,'validateCsrfToken'));
            }
        }
    }

}
?>
