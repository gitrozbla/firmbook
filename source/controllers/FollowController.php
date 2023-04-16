<?php

class FollowController extends Controller 
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
    	
    	$followedItem = Follow::model()->find(array(
            'condition'=>'item_id=:item_id and user_id=:user_id and item_type=:item_type',
            'params'=>array(':item_id'=>$id, ':user_id'=>Yii::app()->user->id, ':item_type'=>$type)
    	));
        
        if($type==Follow::ITEM_TYPE_COMPANY)
    	{
            $item = Item::model()->findByAttributes(array(), array(
                'condition'=>'t.id=:id and t.active',
                'params'=>array(
                    ':id'=>$id,
            )));
        } elseif($type==Follow::ITEM_TYPE_USER)
    	{
            $item = User::model()->findByAttributes(array(), array(
                'condition'=>'t.id=:id and t.active',
                'params'=>array(
                    ':id'=>$id,
            )));  				 
        }
        if ($type!=Follow::ITEM_TYPE_CATEGORY && !$item)        
            return;

    	if($followedItem) {
            $followedItem->delete();
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
    	//$button = '<i class="fa fa-list"></i> '.Yii::t('common','Elist').' ('.$itemsCount.')';	    
					    	
    	$inverseItemsCount = Follow::model()->count(
            array(
                'condition' => 'item_id=:item_id and item_type=:item_type',
                'params' => array(':item_type'=>$type, ':item_id'=>$id)
            ));
    	
    	$inverseButton = '<i class="fa fa-eye fa-1x"></i>&nbsp; '.$inverseItemsCount;
        if($scenario && ($type==Follow::ITEM_TYPE_USER || $type==Follow::ITEM_TYPE_COMPANY))
    	{
            $owner = $type == Follow::ITEM_TYPE_USER ? $item : $item->user;
//            if($scenario && ($type==Follow::ITEM_TYPE_USER || $type==Follow::ITEM_TYPE_COMPANY) && $owner->send_emails)
            if($owner->send_emails)
            {
                $this->layout = 'mail';

                $emailData = array(
                    'email' => $owner->email,
                    'name' => $owner->forename . ' ' . $owner->surname
                );
//                if($type==Follow::ITEM_TYPE_USER)
//                    $emailData = array(
//                        'email' => $item->email,
//                        'name' => $item->forename . ' ' . $item->surname
//                    );
//                else
//                    $emailData = array(
//                        'email' => $item->user->email,
//                        'name' => $item->user->forename . ' ' . $item->user->surname
//                    );
//                Yii::app()->language = $owner->language;
                $userOrgLanguage = Yii::app()->user->language;
                $appOrgLanguage = Yii::app()->language;
                Yii::app()->user->language = $owner->language;
                Yii::app()->language = $owner->language;        
                Yii::app()->mailer->systemMail(
                    $type==Follow::ITEM_TYPE_USER ? $item->email : $item->user->email,    
                    Yii::t('follow', 'Added to observed', [], null, $owner->language),
                    $this->render('addToFollowedEmail', compact('item', 'type', 'owner'), true, true),        
                    $emailData
                );
                Yii::app()->user->language = $userOrgLanguage;
                Yii::app()->language = $appOrgLanguage;
            }
        }
    	echo CJSON::encode(array(
            'counter' => $itemsCount,    		
            'button' => $button,
            'scenario' => $scenario,
            'inverse_button' => $inverseButton
    	));   	
    }
    
	/*
	 * Wyświetla listę ulubionych
	 * @type - 1 - ulubione, 2 - elista 
	 */	
    public function actionShow($jsload=false)
    {       	
    	$this->ajaxMode();
		$follow = new Follow();
        $follow->unsetAttributes();
        if(isset($_GET['Follow'])) {
            $follow->attributes = $_GET['Follow'];
        }        
        
       // if (!Yii::app()->user->isGuest)
        $follow->user_id = Yii::app()->user->id;        
        
        $params =array(
            'follow'=>$follow,
        );
        
        if($jsload=='true')
        	Yii::app()->clientscript->scriptMap['jquery-2.1.0.min.js'] = false;
        else
        	Yii::app()->clientscript->scriptMap['*.js'] = false;        		
        
        $this->renderPartial('show', $params, false, true);
        //echo $this->renderPartial('favorite', $params, false, false);
        Yii::app()->end();    	
    }
    
    public function actionList()
    {
//	echo '<br>ElistsController->actionList';
    	$follow = new Follow();
    	$follow->unsetAttributes();
    	 
//     	if($type!=Elist::TYPE_ELIST && $type!=Elist::TYPE_FAVORITE)
//     		$type = Elist::TYPE_FAVORITE;
    		 
//     	$elist->type = $type;
    	$follow->user_id = Yii::app()->user->id;
    	$dataProvider = $follow->followDataProviderUnion();
        $providerData = [];
    	//     	var_dump($dataProvider->getData());
    	//     	var_dump($dataProvider->getModels());
//    	foreach($dataProvider->getData() as &$row)
        foreach($dataProvider->getData() as $row)
    	{
    	//     		var_dump($row);
    	//     		echo '<br>'.$row;
    		// 			switch($row['cache_type'])
    		// 			{
    		// 				case 'u':
    		// 					echo '<br>user';
    			
    		// 						break;
    		// 				case 'c':
    		// 					echo '<br>c';
    		// // 					$file = new UserFile();
    		// // 					$file->setAttribute('class', $row['class']);
    		// // 					$file->setAttribute('data_id', $row['data_id']);
    		// // 					$file->setAttribute('hash', $row['hash']);
    		// // 					$file->setAttribute('extension', $row['extension']);
    		// // 					$row['url'] = $file->generateUrl('small');
    		// 					break;
    		// 				case 'p':
    		// 					echo '<br>p';
    		// 					break;
    		// 				case 's':
    		// 					echo '<br>service';
    		// // 					$file = new UserFile();
    		// // 					$file->setAttribute('class', $row['class']);
    		// // 					$file->setAttribute('data_id', $row['data_id']);
    		// // 					$file->setAttribute('hash', $row['hash']);
    		// // 					$file->setAttribute('extension', $row['extension']);
    		// // 					$row['url'] = $file->generateUrl();
    		// 					break;
    		// 			}
    		if($row['cache_type'] == 'k')
    			continue;
    		$file = new UserFile();
    		$file->setAttribute('class', $row['class']);
    		$file->setAttribute('data_id', $row['data_id']);
    		$file->setAttribute('hash', $row['hash']);
    		$file->setAttribute('extension', $row['extension']);
    		$row['url'] = $file->generateUrl('small');
                $providerData[] = $row;
//     		var_dump($row);
    }
    $dataProvider->setData($providerData);
    //     	$dataProvider->getData()
    //     	var_dump($dataProvider->getData());
    //     	$activeProvider = new CActiveDataProvider('Elist');
    //     	$activeProvider->setData($dataProvider->getData());
    //     	var_dump($activeProvider->getData());
    //     	var_dump($activeProvider->getData(true));
    //     	foreach($activeProvider->getData() as $row)
    	//     	{
    	//     		var_dump($row->thumbnail);
    	//  //     		echo '<br>'.$row;
    	//   		}
    
//     	$follow->user_id = Yii::app()->user->id;
    	//     	$this->breadcrumbs = null;
    	$this->breadcrumbs = array();
    	//     	$this->breadcrumbs []= Yii::t('elist', 'Favorite');
    	$this->setPageTitle(Yii::t('follow', 'Observed').' - '.Yii::app()->name);
    	$itemView = '_listItem';
    	$this->render('list', compact('follow', 'dataProvider', 'itemView'));
//     	exit;
    }
    
    // Użytkownicy którzy dodali do elisty
    public function actionInverseList($id, $itype)
    {
//    	echo '<br>FollowController->actionInverseList';
    	//     	echo '<br>typ: '.$type;
    	//     	echo '<br>id: '.$id;
    	//     	echo '<br>itype: '.$itype;
    
    	$follow = new Follow();
    	$follow->unsetAttributes();   			 
    		 
    	$follow->item_id = $id;
    	$follow->item_type = $itype;
    	
    	if($follow->item_type==Elist::ITEM_TYPE_ITEM)
    	{
            $resource = Item::model()->with(array('thumbnail'))->findByAttributes(array(), array(
                'condition'=>'t.id=:id and t.active',
                'params'=>array(
                                ':id'=>$follow->item_id,
            )));

            if (!$resource) {
                throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
            }
    	}
    	
    	$dataProvider = $follow->followInverseDataProvider();
        $providerData = [];
//    	var_dump($dataProvider->getData());
//    	foreach($dataProvider->getData() as &$row)
        foreach($dataProvider->getData() as $row)    
    	{
            //     		if(isset($row['class']));
            $file = new UserFile();
            $file->setAttribute('class', $row['class']);
            $file->setAttribute('data_id', $row['data_id']);
            $file->setAttribute('hash', $row['hash']);
            $file->setAttribute('extension', $row['extension']);
            $row['url'] = $file->generateUrl('small');
            $providerData[] = $row;
    	}
        $dataProvider->setData($providerData);
        //   		$elist->user_id = Yii::app()->user->id;

        $this->breadcrumbs = null;
        //   		$this->breadcrumbs = array();
//    	$this->breadcrumbs []= Yii::t('follow', 'Followers');
    	$itemView = '_inverseListItem';
    	$this->render('list', compact('follow', 'dataProvider', 'resource', 'itemView'));
    }
    
	//AJAX - usunięcie elementu z elisty
	public function actionRemove_old()
	{		
		$this->ajaxMode();	
		$id = Yii::app()->request->getParam('id');
		$type = Yii::app()->request->getParam('type');
		
		$item = Follow::model()->find(array(
    		'condition'=>'item_id=:item_id and user_id=:user_id and item_type=:item_type',
    		'params'=>array(':item_id'=>$id, ':user_id'=>Yii::app()->user->id, ':item_type'=>$type)
    	));
    	
    	if(!$item)
    		Yii::app()->end();

    	$item->delete();	
		$itemsCount = Follow::model()->count(
				    		array(
					    		'condition'=>'user_id=:user_id',
					    		'params'=>array(':user_id'=>Yii::app()->user->id)
					    	));
		
		$button = '<i class="fa fa-eye"></i> ('.$itemsCount.')';
					    	
    	echo CJSON::encode(array(
    		'counter' => $itemsCount,
    		'type' => $type,
			'button' => $button,    					
    	));       	       		
	}    	
	
	//AJAX - usunięcie wszystkich elementów z elisty
	public function actionClean_notusedyet()
	{
		$this->ajaxMode();		
		
		$type = Yii::app()->request->getParam('type');
	
		Elist::model()->deleteAll(
				'user_id=:user_id and type=:type',
				array(':user_id'=>Yii::app()->user->id, ':type'=>$type)
		);				
		 
		if(!$item)
			Yii::app()->end();	
		
		$itemsCount = Elist::model()->count(
				array(
						'condition'=>'user_id=:user_id and type=:type',
						'params'=>array(':user_id'=>Yii::app()->user->id, ':type'=>$type)
				));
		switch($type) {
			case Elist::TYPE_ELIST:
				$button = '<i class="fa fa-list"></i> '.Yii::t('common','Elist').' ('.$itemsCount.')';
				break;
			case Elist::TYPE_FAVORITE:
			default:
				$button = '<i class="fa fa-heart"></i> '.Yii::t('common','Favorite').' ('.$itemsCount.')';
		}
	
		echo CJSON::encode(array(
				'counter' => $itemsCount,
				'type' => $type,
				'button' => $button,
				//'scenario' => $scenario
		));
	}	
	
}

?>