<?php

class ElistsController extends Controller 
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
     * @param integer $elist_id Rodzaj listy - Elist::TYPE_FAVORITE, Elist::TYPE_ELIST ...
     * @param integer $type Typ elementu: 1 - item, 2 - news, 3 - user
     */
    public function actionAdd($id, $elist_id, $type)
    {
    	//echo $id.' '.$elist_id. ' '.$type;
    	$this->ajaxMode();
    	
    	$elistItem = Elist::model()->find(array(
            'condition'=>'item_id=:item_id and user_id=:user_id and type=:type and item_type=:item_type',
            'params'=>array(':item_id'=>$id, ':user_id'=>Yii::app()->user->id, ':type'=>$elist_id, ':item_type'=>$type)
    	));
        
        if($type==Elist::ITEM_TYPE_ITEM)
    	{
            $item = Item::model()->findByAttributes(array(), array(
                'condition'=>'t.id=:id and t.active',
                'params'=>array(
                    ':id'=>$id,
            )));
        } elseif($type==Elist::ITEM_TYPE_USER)
    	{
            $item = User::model()->findByAttributes(array(), array(
                'condition'=>'t.id=:id and t.active',
                'params'=>array(
                    ':id'=>$id,
            )));  				 
        }
        if (!$item)        
            return;

    	if($elistItem) {
            $elistItem->delete();
            $scenario = 0;	
    	} else {
            $elist = new Elist();
            $elist->item_id = $id;
            $elist->user_id = Yii::app()->user->id;
            $elist->type = $elist_id;
            $elist->item_type = $type;
            $elist->save();
            $scenario = 1;
    	}
    	$itemsCount = Elist::model()->count(
            array(
                'condition'=>'user_id=:user_id and type=:type',
                'params'=>array(':user_id'=>Yii::app()->user->id, ':type'=>$elist_id)
        ));
    	
    	$inverseItemsCount = Elist::model()->count(
        array(
            'condition' => 'type=:type and item_id=:item_id and item_type=:item_type',
            'params' => array(':type'=>$elist_id, ':item_type'=>$type, ':item_id'=>$id)
        ));
    	
        switch($elist_id) {
            case Elist::TYPE_ELIST:				
                $button = '<i class="fa fa-list"></i> ('.$itemsCount.')';
                //$button = '<i class="fa fa-list"></i> '.Yii::t('common','Elist').' ('.$itemsCount.')';
                $inverseButton = '<i class="fa fa-list fa-1x"></i>&nbsp; '.$inverseItemsCount;

                if($scenario && $item->user->send_emails)
                {
                    $this->layout = 'mail';
//                    Yii::app()->mailer->ClearAttachments();
                    $emailData = array(
                        'email' => $item->user->email,
                        'name' => $item->user->forename . ' ' . $item->user->surname
                    );
                    $userOrgLanguage = Yii::app()->user->language;
                    $appOrgLanguage = Yii::app()->language;
                    Yii::app()->user->language = $item->user->language;
                    Yii::app()->language = $item->user->language;
                    Yii::app()->mailer->systemMail(

                        $item->user->email,    
                        Yii::t('elists', 'Added to elist', [], null, $item->user->language),
                        $this->render('addToElistEmail', compact('item', 'elist_id'), true, true),        
                        $emailData
                    );
                    Yii::app()->user->language = $userOrgLanguage;
                    Yii::app()->language = $appOrgLanguage;
                }		 
                break;
            case Elist::TYPE_FAVORITE:
            default: 
                
                $button = '<i class="fa fa-heart"></i> ('.$itemsCount.')';
                //$button = '<i class="fa fa-heart"></i> '.Yii::t('common','Favorite').' ('.$itemsCount.')';
                $inverseButton = '<i class="fa fa-heart fa-1x"></i>&nbsp; '.$inverseItemsCount;
                
                $owner =$type == Elist::ITEM_TYPE_USER ? $item : $item->user;
                
                if($scenario && $owner->send_emails)
                {
                    $this->layout = 'mail';
//                    Yii::app()->mailer->ClearAttachments();
                    if($type==Elist::ITEM_TYPE_USER)
                        $emailData = array(
                            'email' => $item->email,
                            'name' => $item->forename . ' ' . $item->surname
                        );
                    else
                        $emailData = array(
                            'email' => $item->user->email,
                            'name' => $item->user->forename . ' ' . $item->user->surname
                        );
                    $userOrgLanguage = Yii::app()->user->language;
                    $appOrgLanguage = Yii::app()->language;
                    Yii::app()->user->language = $owner->language;
                    Yii::app()->language = $owner->language;
                    Yii::app()->mailer->systemMail(
                        $type==Elist::ITEM_TYPE_USER ? $item->email : $item->user->email,    
                        Yii::t('elists', 'Added to favorites', [], null, $type==Elist::ITEM_TYPE_USER ? $item->language : $item->user->language),
                        $this->render('addToFavoritesEmail', compact('item', 'elist_id', 'type'), true, true),        
                        $emailData
                    );
                    Yii::app()->user->language = $userOrgLanguage;
                    Yii::app()->language = $appOrgLanguage;
//                    $this->layout = 'main';
                }
                
        }
					    	
    	echo CJSON::encode(array(
    		'counter' => $itemsCount,
    		'elist_id' => $elist_id,
                'button' => $button,
    		'scenario' => $scenario,
    		'inverse_button' => $inverseButton				
    	)); 
        
    }
	/*
	 * Wyświetla listę ulubionych
	 * @type - 1 - ulubione, 2 - elista 
	 */	
    public function actionShow($type, $jsload=false)
    {       	
    	$this->ajaxMode();
		$elist = new Elist();
        $elist->unsetAttributes();
        if(isset($_GET['Elist'])) {
            $elist->attributes = $_GET['Elist'];
        }
        
        if($type!=Elist::TYPE_ELIST && $type!=Elist::TYPE_FAVORITE)
        	$type = Elist::TYPE_FAVORITE;
        
        $elist->type = $type;
       // if (!Yii::app()->user->isGuest)
        $elist->user_id = Yii::app()->user->id;
        
        
        $params =array(
            'elist'=>$elist,
        );
        
        if($jsload=='true')
        	Yii::app()->clientscript->scriptMap['jquery-2.1.0.min.js'] = false;
        else
        	Yii::app()->clientscript->scriptMap['*.js'] = false;        		
        
        $this->renderPartial('show', $params, false, true);
//         $this->renderPartial('test', $params, false, true);
        //echo $this->renderPartial('favorite', $params, false, false);
        Yii::app()->end();    	
    }
    
    public function actionList($type)
    {
//     	echo '<br>ElistsController->actionList';
//     	echo '<br>typ: '.$type;
    	
    	$elist = new Elist();
    	$elist->unsetAttributes();
    	
    	if($type!=Elist::TYPE_ELIST && $type!=Elist::TYPE_FAVORITE)
    		$type = Elist::TYPE_FAVORITE;
    	
    	$elist->type = $type;
    	$elist->user_id = Yii::app()->user->id;
    	$dataProvider = $elist->elistDataProviderUnion();
//     	var_dump($dataProvider->getData());
//     	var_dump($dataProvider->getModels());
     	$providerData = [];
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
			$file = new UserFile();
			$file->setAttribute('class', $row['class']);
			$file->setAttribute('data_id', $row['data_id']);
			$file->setAttribute('hash', $row['hash']);
			$file->setAttribute('extension', $row['extension']);
			$row['url'] = $file->generateUrl('small');
// 			var_dump($row);
                        $providerData [] = $row;
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
    		
    	$elist->user_id = Yii::app()->user->id;
//     	$this->breadcrumbs = null;
    	$this->breadcrumbs = array();
//     	$this->breadcrumbs []= Yii::t('elist', 'Favorite');
    	$itemView = '_listItem';
		$this->setPageTitle(Yii::t('elists', $elist->elistName()).' - '.Yii::app()->name);
    	$this->render('list', compact('elist', 'dataProvider', 'itemView'));
//     	exit;
    }
    
    public function actionTest()
    {
    	echo '<br>ElistsController->actionTest';
        exit;
    }    
    
    // Użytkownicy którzy dodali do elisty
    public function actionInverseList($type, $id, $itype)
    {
//    	echo '<br>ElistsController->actionInverseList';
//    	exit; 
    	$elist = new Elist();
    	$elist->unsetAttributes();
    	 
    	if($type!=Elist::TYPE_ELIST && $type!=Elist::TYPE_FAVORITE)
    		$type = Elist::TYPE_FAVORITE;


    	
    	$elist->type = $type;
    	$elist->item_id = $id;
    	$elist->item_type = $itype;
    	
    	//     	$elist->user_id = Yii::app()->user->id;
//     	var_dump($elist);
    	
    	if($elist->item_type==Elist::ITEM_TYPE_ITEM)
    	{
    		$resource = Item::model()->with(array('thumbnail'))->findByAttributes(array(), array(
    			'condition'=>'t.id=:id and t.active',
    			'params'=>array(
    				':id'=>$elist->item_id,
    			)));
    				 
    		if (!$resource) {
    			throw new CHttpException(404, Yii::t('item', 'Item does not exist or is inactive.'));
//     			switch($resource->cache_type)
//     			{
//     				case 'c':
//     					throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
//     					break;
//     				case 'p':
//     					throw new CHttpException(404, Yii::t('products', 'Product does not exist or is inactive.'));
//     					break;
//     				case 's':
//     					throw new CHttpException(404, Yii::t('services', 'Service does not exist or is inactive.'));
//     					break;
//     			}
    			
    		}	
//     		var_dump($resource);
    	} elseif($elist->item_type==Elist::ITEM_TYPE_USER)
    	{
    		$resource = User::model()->with(array('thumbnail'))->findByAttributes(array(), array(
    			'condition'=>'t.id=:id and t.active',
    			'params'=>array(
    				':id'=>$elist->item_id,
    			)));
    				 
    		if (!$resource) {
    			throw new CHttpException(404, Yii::t('user', 'User does not exists.'));
//     			switch($resource->cache_type)
//     			{
//     				case 'c':
//     					throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
//     					break;
//     				case 'p':
//     					throw new CHttpException(404, Yii::t('products', 'Product does not exist or is inactive.'));
//     					break;
//     				case 's':
//     					throw new CHttpException(404, Yii::t('services', 'Service does not exist or is inactive.'));
//     					break;
//     			}
    			
    		}	
//     		var_dump($resource);
    	} 	
    	
//     	$company = Company::model()->with(
//     			array('item'=>array(
//     					'alias'=>'i'
//     			),
//     					'item.category'
//     			))->findByAttributes(array(), array(
//     					'condition'=>'i.alias=:alias and (active or i.user_id=:user_id)',
//     					'params'=>array(
//     							':alias'=>$name,
//     							':user_id'=>Yii::app()->user->id,
//     					)));
    	
//     			if (!$company) {
//     				throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
//     			}
    	
    	
    	$dataProvider = $elist->elistInverseDataProvider();
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
//        var_dump($this->breadcrumbs);
//        $this->breadcrumbs []= $elist->inverseListName();
        $itemView = '_inverseListItem';
        $this->render('list', compact('elist', 'dataProvider', 'resource', 'itemView'));    	
    }
    	
	//AJAX - usunięcie elementu z elisty
	public function actionRemove()
	{		
		$this->ajaxMode();	
		$id = Yii::app()->request->getParam('id');
		$type = Yii::app()->request->getParam('type');
		
		$item = Elist::model()->find(array(
    		'condition'=>'item_id=:item_id and user_id=:user_id and type=:type',
    		'params'=>array(':item_id'=>$id, ':user_id'=>Yii::app()->user->id, ':type'=>$type)
    	));
    	
    	if(!$item)
    		Yii::app()->end();

    	$item->delete();	
		$itemsCount = Elist::model()->count(
				    		array(
					    		'condition'=>'user_id=:user_id and type=:type',
					    		'params'=>array(':user_id'=>Yii::app()->user->id, ':type'=>$type)
					    	));
		switch($type) {
			case Elist::TYPE_ELIST:				
				$button = '<i class="fa fa-list"></i> ('.$itemsCount.')';				
				break;
			case Elist::TYPE_FAVORITE:
			default: 					
				$button = '<i class="fa fa-heart"></i> ('.$itemsCount.')';								
		}
					    	
    	echo CJSON::encode(array(
    		'counter' => $itemsCount,
    		'type' => $type,
			'button' => $button,
    		//'scenario' => $scenario			
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