<?php

class AlertsController extends Controller 
{		
	/**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {    	
    	//return 'favorite, add';    	        
    }    
    
    /**
     * Dodaje lub usuwa element na eliscie
     * @param integer $id Id elementu    
     * @param integer $type Typ elementu: 1 - company, 2 - user, 3 - category
     */
    public function actionAdd($id, $type)
    {
    	
    	$this->ajaxMode();
    	
    	$item = Follow::model()->find(array(
    		'condition'=>'item_id=:item_id and user_id=:user_id and item_type=:item_type',
    		'params'=>array(':item_id'=>$id, ':user_id'=>Yii::app()->user->id, ':item_type'=>$type)
    	));
    	if($item) {
    		$item->delete();
    		$scenario = 0;	
    	} else {
	    	$follow = new Follow();
	    	$follow->item_id = $id;
	    	$follow->user_id = Yii::app()->user->id;	    	
	    	$follow->item_type = $type;
	    	$follow->save();
	    	$scenario = 1;
    	}
    	$itemsCount = Follow::model()->count(
				    		array(
					    		'condition'=>'user_id=:user_id',
					    		'params'=>array(':user_id'=>Yii::app()->user->id)
					    	));
    	
    	$button = '<i class="fa fa-eye"></i> ('.$itemsCount.')';    		    
					    	
    	echo CJSON::encode(array(
    		'counter' => $itemsCount,    		
			'button' => $button,
    		'scenario' => $scenario			
    	));   	
    }
    
	/*
	 * Wyświetla listę alertów  
	 */	
    public function actionShow($jsload=false)
    {       	
    	$this->ajaxMode();
		$alert = new Alert();
        $alert->unsetAttributes();
        
        if(isset($_GET['Alert']))
            $alert->attributes = $_GET['Alert'];        
        
        $alert->user_id = Yii::app()->user->id;        
        
        $params =array(
            'alert'=>$alert,
        );
        
        if($jsload=='true')
        	Yii::app()->clientscript->scriptMap['jquery-2.1.0.min.js'] = false;
        else
        	Yii::app()->clientscript->scriptMap['*.js'] = false;        		
        
        $this->renderPartial('show', $params, false, true);
        
        Yii::app()->end();    	
    }
    
	//AJAX - usunięcie elementu z elisty
	public function actionRemove()
	{		
		$this->ajaxMode();	
		$id = Yii::app()->request->getParam('id');		
		
		$item = Alert::model()->find(array(
    		'condition'=>'id=:id',
    		'params'=>array(':id'=>$id)
    	));
    	
    	if(!$item)
    		Yii::app()->end();

    	$item->delete();	
		$itemsCount = Alert::model()->count(
						    		array(
							    		'condition'=>'user_id=:user_id and date>:date',
							    		'params'=>array(
							    			':user_id'=>Yii::app()->user->id,
							    			':date'=> date('Y-m-d', strtotime('-'.Alert::EXPIRE_AFTER.' days'))			
		    							)
							    	));		
		
		$button = '<i class="fa fa-bell"></i> ('.$itemsCount.')';
					    	
    	echo CJSON::encode(array(
    		'counter' => $itemsCount,    		
			'button' => $button,    					
    	));       	       		
	}
	
}

?>