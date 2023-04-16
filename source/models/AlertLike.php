<?php

/**
 * Klasa powiadomienia o nowym obiekcie FPS
 * @author inter
 *
 */

class AlertLike extends Alert
{   
        
    public static function getMessage($data) 
    {    	
        
        $userLink = Html::link($data['context_name'], Yii::app()->createUrl("user/profile",
    					array("username"=>$data['context_name'])));
//    	if($data['item_type'])
//        {    
            switch($data['item_cache_type']) {
                case 'c':    			
                    $link = Html::link($data['item_name'], Yii::app()->createUrl("companies/show",
                            array("name"=>$data['item_alias'])));
                    $msg = Yii::t('alerts', 'Your company {item_name} has been added to favorites of user {user_name}', array('{item_name}' => $link, '{user_name}' => $userLink));
                    break;
                case 'p':
                    $link = Html::link($data['item_name'], Yii::app()->createUrl("products/show",
                            array("name"=>$data['item_alias'])));
                    $msg = Yii::t('alerts', 'Your product {item_name} has been added to favorites of user {user_name}', array('{item_name}' => $link, '{user_name}' => $userLink));
                    break;
                case 's':
                    $link = Html::link($data['item_name'], Yii::app()->createUrl("services/show",
                            array("name"=>$data['item_alias'])));
                    $msg = Yii::t('alerts', 'Your service {item_name} has been added to favorites of user {user_name}', array('{item_name}' => $link, '{user_name}' => $userLink));
                    break;
                case 'u':
                    $link = Html::link($data['item_name'], Yii::app()->createUrl("services/show",
                            array("name"=>$data['item_alias'])));
                    $msg = Yii::t('alerts', 'You have been added to favorites of user {user_name}', array('{user_name}' => $userLink));
                    break;
        		default:
        			$msg .= $data['item_name'];
            }
//        }    
    	
    	$msg .= '<br />'.Yii::app()->dateFormatter->format("yyyy-MM-dd", $data['date']);    	
    	return $msg;
    }
}
?>