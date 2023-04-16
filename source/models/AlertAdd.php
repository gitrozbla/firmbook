<?php

/**
 * Klasa powiadomienia o nowym obiekcie FPS
 * @author inter
 *
 */

class AlertAdd extends Alert
{   
        
    public static function getMessage($data) 
    {
    	$msg = '';
    	switch($data['item_cache_type']) {
    		case 'c':
    			$msg .= Yii::t('alerts', 'New company').' ';
    			$msg .= CHtml::link($data['item_name'], Yii::app()->createUrl("companies/show",
    					array("name"=>$data['item_alias'])));
    			break;
    		case 'p':
    			$msg .= Yii::t('alerts', 'New product').' ';
    			$msg .= CHtml::link($data['item_name'], Yii::app()->createUrl("products/show",
    					array("name"=>$data['item_alias'])));
    			break;
    		case 's':
    			$msg .= Yii::t('alerts', 'New service').' ';
    			$msg .= CHtml::link($data['item_name'], Yii::app()->createUrl("services/show",
    					array("name"=>$data['item_alias'])));
    			break;
    		default:
    			$msg .= $data['item_name'];
    	}
    	switch($data['context_type']) {
    		case Alert::CONTEXT_TYPE_COMPANY:
    			$msg .= ', '.Yii::t('alerts', 'company').' ';
    			$msg .= CHtml::link($data['context_name'], Yii::app()->createUrl("companies/show",
    					array("name"=>$data['context_alias'])));
    			break;
    		case Alert::CONTEXT_TYPE_USER:
    			$msg .= ', '.Yii::t('alerts', 'user').' ';
    			$msg .= CHtml::link($data['context_name'], Yii::app()->createUrl("user/profile",
    					array("username"=>$data['context_name'])));
    			break;
    		case Alert::CONTEXT_TYPE_CATEGORY:
    			$msg .= ', '.Yii::t('alerts', 'category').' ';
    			
    			$alias = $data['context_alias']
    			? Yii::t('category.alias', $data['context_alias'], null, 'dbMessages')
    			: $data['context_id'];    			
    			
    			$search = Search::model()->getFromSession();
    			$msg .= CHtml::link(
//     					Yii::t('category.name', $data['context_name'], null, 'dbMessages'),
    					Yii::t('category.name', $data['context_alias'], array($data['context_alias']=>$data['context_name']), 'dbMessages'),
//     					Yii::app()->createUrl('categories/show', array('name'=>$alias)));
    					$search->createUrl('categories/show', array('name'=>$alias)));
    			
    			break;
    		default:	
    			$msg .= $data['context_name'];
    	}   	
    	
    	$msg .= '<br />'.Yii::app()->dateFormatter->format("yyyy-MM-dd", $data['date']);    	
    	return $msg;
    }
}
?>