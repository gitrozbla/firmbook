<?php

class Pagination extends CPagination
{

	public function createPageUrl($controller,$page)
	{
		$params=$this->params===null ? $_GET : $this->params;
		if($page>0) // page 0 is the default
			$params[$this->pageVar]=$page+1;
		else
			unset($params[$this->pageVar]);
		if (Yii::app()->urlManager->globalRouteMode) return $controller->createGlobalRouteUrl($this->route,$params);
		else return $controller->createUrl($this->route,$params);
	}

}
