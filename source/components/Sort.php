<?php

class Sort extends CSort
{

	public function createUrl($controller,$directions)
	{
		$sorts=array();
		foreach($directions as $attribute=>$descending)
			$sorts[]=$descending ? $attribute.$this->separators[1].$this->descTag : $attribute;
		$params=$this->params===null ? $_GET : $this->params;
		$params[$this->sortVar]=implode($this->separators[0],$sorts);
		if (Yii::app()->urlManager->globalRouteMode) return $controller->createGlobalRouteUrl($this->route,$params);
		else return $controller->createUrl($this->route,$params);
	}

}
