<?php
/**
 * Kontroler akcji dla usługi.
 * 
 * @category controllers
 * @package service
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class ServicesController extends Controller
{
    /**
     * Domyślna akcja.
     * @var string
     */
    public $defaultAction = 'show';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'show';
    }
    
    /**
     * Wyświetla informacje o usłudze.
     * @param string $name Nazwa usługi.
     * @throws CHttpException
     */
    public function actionShow($name=null)
    {
//         $this->setPageTitle(Yii::app()->name.' - '.Yii::t('service', 'service').' '.$name);
        
        $name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');

        // get service
        $service = Service::model()->with(
                array('item'=>array(
                        'alias'=>'i'
                    ),
                    'item.category'
                ))->findByAttributes(array(), array(
                    'condition'=>'i.alias=:alias and (active or i.user_id=:user_id)',
                    'params'=>array(
                        ':alias'=>$name,
                        ':user_id'=>Yii::app()->user->id,
                )));
        
        if (!$service) {
            throw new CHttpException(404, Yii::t('services', 'Service does not exist or is inactive.'));
        }
        
        $item = $service->item;
        $this->setPageTitle($item->name.' - '.Yii::app()->name);
        
        // editor
        if (Yii::app()->user->checkAccess('Services.update', array('record'=>$service))) {
            $this->editorEnabled = true;
        }
        
        // change context
        $search = Search::model()->getFromSession();
        $search->type = 'service';
        $search->action = $item->sell ? 'sell' : 'buy';
        
        if ($item->category) {
            $this->breadcrumbs = $item->category->generateBreadcrumbs();
        } else {
            $this->breadcrumbs = array();
        }
        $this->breadcrumbs []= $item->name;
        
        $item->saveCounters(array('view_count'=>'1'));
        
        $this->pageDescription = $service->short_description;

        $this->render('../products/product', array('product' => $service));
        //$this->render('service', compact('service'));
    }
    
	/**
     * Modyfikuje informacje o produkcie.
     * ID podane jest w $_GET/$_POST.
     * @param type $model Klasa modelu.
     */
    public function actionPartialupdate($model='Service')
    {
        $es = new EditableSaver($model);
        $es->scenario = 'update';
        $es->update();
    }
    
    /**
     * Modyfikuje informacje o usłudze.
     * ID podane jest w $_GET/$_POST.
     * @param type $model Klasa modelu.
     */
    public function actionUpdate_org($model='Service')
    {
        $es = new EditableSaver($model);
        $es->scenario = 'update';
        $es->update();
    }
    
	public function actionUpdate()
	{		 
		//obecnie max 4
		$categoriesCount = PackageControl::getValue(Yii::app()->user->package_id, 'categories_count');
		if($categoriesCount > 4)
			$categoriesCount = 4;
		$categoriesCount = 1;
		
		$id = Yii::app()->request->getParam('id');
		//$product = Service::model()->findByPk($id);
		// get service
		$product = Service::model()->with(
				array('item'=>array(
						'alias'=>'i'
				),
						//'item.category'
				))->findByAttributes(array(), array(
						'condition'=>'i.id=:id',
						//'condition'=>'i.id=:id and i.user_id=:user_id',
						'params'=>array(
								':id'=>$id,
								//':user_id'=>Yii::app()->user->id,
						)));
		
		if (!$product || !Yii::app()->user->checkAccess('Services.update', array('record'=>$product))) {
			throw new CHttpException(404);
			//throw new CHttpException(404, Yii::t('services', 'Service does not exist or is inactive.'));
		}		
		
		$item = $product->item;
		$itemClone = clone $item;		
						
		if (isset($_POST['Service']) && isset($_POST['Item'])) {
			
			$product->attributes = $_POST['Service'];				
			$item->attributes = $_POST['Item'];
			//$item->additionalCategories = array_filter($_POST['Item']['additionalCategories']);
			
			//obejscie mechanizmu kategorii
			/*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
				$item->category_id = 255;*/
						
			if ($product->validate() && $item->validate()) {												
				//kupujacy, sprzedajacy				
				$item->buy = in_array(1, $item->account_type) ? 1 : 0;						    			
				$item->sell = in_array(2, $item->account_type) ? 1 : 0;					
				
            	$item->save();            	            	
    			$product->save();
    			
    			Yii::app()->user->setFlash('success', Yii::t('services', 'Service updated.'));
        		$this->redirect($this->createGlobalRouteUrl('services/show', array('name'=>$product->item->alias)));					
			}
		} else {
			if($item->alias == $item->id) 
				$item->alias='';
				
			$item->account_type = array();
			if($item->buy)
				$item->account_type[] = 1;	
			if($item->sell)
				$item->account_type[] = 2;	
				
			$product->promotion_expire = Yii::app()->dateFormatter->format('MM/dd/yyyy', $product->promotion_expire);	
		}			
		
		$company = $product->company;
		
		$this->breadcrumbs = array();
		$this->breadcrumbs [Yii::t('navigation', 'My services')]
			= $this->createUrl('/user/items/', array('type' => 'services'));
		if($company)
			$this->breadcrumbs [$company->item->name]= Yii::app()->createUrl('companies/show', array('name'=>$company->item->alias));
		$this->breadcrumbs [$itemClone->name] = $this->createUrl('show', array('name' => $itemClone->alias));
		//$this->breadcrumbs [Yii::t('common', 'Service')] = $this->createUrl('show', array('name' => $itemClone->alias));
		
		$this->render('../products/product-form', compact('product', 'item', 'categoriesCount', 'company'));
	}
	
    public static function checkAccess($bizruleName, $params=array())
    {
        switch ($bizruleName) {
            case 'add':
            	if (isset($params['record'])) {
            		$item = $params['record'];
            		return $item->user_id == Yii::app()->user->id;
            	} else
            		return Yii::app()->user->isGuest ? false : true;                
                
            case 'remove':
                $alias = Yii::app()->request->getParam('name');
                $item = Item::model()->findByAttributes(array(
                    'alias' => $alias,
                ));
                return $item && $item->user_id == Yii::app()->user->id;
                
            case 'update':
                if (isset($params['record'])) {
                    $class = get_class($params['record']);
                    $item = $params['record'];
                    if ($class=='Service') {
                        $item = $item->item;
                    }
                    $pk = $item->id;
                    $attribute = isset($params['attribute']) 
                            ? $params['attribute']
                            : '';
                } else {
                    $c = Yii::app()->controller;
                    switch($c->id.'.'.$c->action->id) {
                        case 'services.update':
                            $request = Yii::app()->request;
                            $pk = $request->getParam('pk');
                            $class = $request->getParam('model');
                            $class = $class ? $class : 'Service';
                            $attribute = $request->getParam('name');
                            
                            if ($class=='Service') {
                                $item = Service::model()->findByPk($pk)->item;
                            } else {
                                $item = Item::model()->findByPk($pk);
                            }
                            break;
                        
                        default:
                            // unknown action
                            return false;
                    }
                }
                
                switch($class.'.'.$attribute) {
                    case 'Item.thumbnail_file_id':
                    case 'Item.name':
                    case 'Item.alias':
                    case 'Item.active':
                    case 'Item.category_id':
                    case 'Item.buy':
                    case 'Item.sell':
                    case 'Item.description':
                    case 'Service.company_id':
                        return $item 
                                ? $item->user_id == Yii::app()->user->id
                                : false;
                        
                    case 'Service.':
                    case 'Item.':    
                        // for editor button
                        return $item->user_id == Yii::app()->user->id;
                        
                    default:
                        return false;
                        
                }
                
            default:
                return false;
        }
    }
    
    public function actionAdd_org() 
    {
        $service = new Service('create');
        $service->save();
        
        Yii::app()->user->setFlash('success', Yii::t('services', 'Service created.'));
        $this->redirect($this->createUrl('show', 
                array('name'=>$service->item->alias)));
    }
    
    public function actionAdd($company=NULL)
    {		
        //obecnie max 4
        $categoriesCount = PackageControl::getValue(Yii::app()->user->package_id, 'categories_count');
        if($categoriesCount > 4)
                $categoriesCount = 4;
        $categoriesCount = 1;

        if($company) {
                $company = Company::model()->with(
            array('item'=>array(
                    'alias'=>'i'
                ),
                'item.category'
            ))->findByAttributes(array(), array(
                    'condition'=>'i.id=:item_id',
                //'condition'=>'i.id=:item_id and i.user_id=:user_id',
                'params'=>array(
                    ':item_id'=>$company,
                    //':user_id'=>Yii::app()->user->id,
            )));

            if (!$company || !Yii::app()->user->checkAccess('Services.add', array('record'=>$company->item))) {
                throw new CHttpException(404);            
            }        	        
        }

        $product = new Service('create');
        $item = new Item('create');		

        if (isset($_POST['Service']) && isset($_POST['Item'])) {			
            $product->attributes = $_POST['Service'];				
            $item->attributes = $_POST['Item'];
            //$item->additionalCategories = array_filter($_POST['Item']['additionalCategories']);

            $item->cache_package_id = Yii::app()->user->package_id;
            //obejscie mechanizmu kategorii
            /*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
                    $item->category_id = 255;*/

            if ($product->validate() && $item->validate()) {			
                //kupujacy, sprzedajacy				
                $item->buy = in_array(1, $item->account_type) ? 1 : 0;						    			
                $item->sell = in_array(2, $item->account_type) ? 1 : 0;					
                $item->cache_type = 's';
                $item->save(); 

                $product->item_id = $item->id;            	
                $product->save();

                Yii::app()->user->setFlash('success', Yii::t('services', 'Service created.'));
                
                if(Yii::app()->params['productionMode'] && $item->category && $item->user->send_emails) {
                    $this->layout = 'mail';                    
                    $recipients = Social::getNewItemInCategoryMessageRecipients($item->category->id);
                    foreach($recipients as $recipient)
                    {   
//                        if($recipient['email'] != 'seointercode@gmail.com')
//                            continue;
                        $username = null;
                        if($recipient['forename'])
                            $username = $recipient['forename'];
                        if($recipient['surname'])
                        {
                            if($username)
                                $username .= ' '.$recipient['surname'];
                            else
                                $username = $recipient['surname'];
                        }    
                        if(!$username)
                            $username = $recipient['username'];

                        $emailData = array(
                            'email' => $recipient['email'],
                            'name' => $username
                        );
                        $userOrgLanguage = Yii::app()->user->language;
                        $appOrgLanguage = Yii::app()->language;
                        Yii::app()->user->language = $recipient['language'];
                        Yii::app()->language = $recipient['language'];
                        Yii::app()->mailer->systemMail(
                            $recipient['email'],    
                            Yii::t('services', 'New service added', [], null, $recipient['language']),    
                            $this->render('/categories/addToCategoryEmail', compact('item', 'recipient'), true, true),                                      
                            $emailData
                        );                            
                        Yii::app()->user->language = $userOrgLanguage;
                        Yii::app()->language = $appOrgLanguage;  
                    }    
                }
                
                if(Yii::app()->params['productionMode'] && $item->user->send_emails)
                {
                    $this->layout = 'mail';                    
                    $recipients = Social::getNewItemMessageRecipients($item);
                    foreach($recipients as $recipient)
                    {
                        $username = null;
                        if($recipient['forename'])
                            $username = $recipient['forename'];
                        if($recipient['surname'])
                        {
                            if($username)
                                $username .= ' '.$recipient['surname'];
                            else
                                $username = $recipient['surname'];
                        }    
                        if(!$username)
                            $username = $recipient['username'];

                        $emailData = array(
                            'email' => $recipient['email'],
                            'name' => $username
                        );
                        $userOrgLanguage = Yii::app()->user->language;
                        $appOrgLanguage = Yii::app()->language;
                        Yii::app()->user->language = $recipient['language'];
                        Yii::app()->language = $recipient['language'];
                        Yii::app()->mailer->systemMail(
                            $recipient['email'],    
                            Yii::t('services', 'New service added', [], null, $recipient['language']),    
                            $this->render('/services/newServiceEmail', compact('company', 'item', 'recipient'), true, true),                                      
                            $emailData
                        );  
                        Yii::app()->user->language = $userOrgLanguage;
                        Yii::app()->language = $appOrgLanguage;
                    }
                }                
                $this->redirect($this->createGlobalRouteUrl('services/show', array('name'=>$product->item->alias)));					
            }
        } else {
            if($company) {
                    $product->company_id = $company->item_id;
                    $item->category_id = $company->item->category_id;	
            }

            /*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
            $item->category_id = 302;*/
        }	

        $this->breadcrumbs = array();
        //$this->breadcrumbs []= Yii::t('services', 'Add new service');
        $this->breadcrumbs [Yii::t('navigation', 'My services')]
                = $this->createUrl('/user/items/', array('type' => 'services'));
        if($company)
                $this->breadcrumbs [$company->item->name]= Yii::app()->createUrl('companies/show', array('name'=>$company->item->alias));

        $this->render('../products/product-form', compact('product', 'item', 'categoriesCount', 'company'));
    }
    
    public function actionRemove($name, $return=true) 
    {
    	$item = Item::model()->findByAttributes(array(), array(
    			'condition'=>'alias=:alias',
    			//'condition'=>'alias=:alias and user_id=:user_id',
    			'params'=>array(
    					':alias'=>$name,
    					//':user_id'=>Yii::app()->user->id,
    			)));
    	
    	if (!$item || !Yii::app()->user->checkAccess('Services.remove')) {
    		throw new CHttpException(404);
    	}    	
    	
        /*$item = Item::model()->findByAttributes(array(
            'alias' => $name,
        ));*/
        if ($item && $item->cache_type == 's') {
            $item->delete();
            
            Yii::app()->user->setFlash('success', Yii::t('services', 'Service removed.'));
            if ($return) {
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                $this->redirect($this->createGlobalRouteUrl('/categories'));
            }
        }
        
    }
    
    public function actionEdit_gallery($name=null)
    {
    	$this->setPageTitle(Yii::app()->name.' - '.Yii::t('service', 'service').' '.$name);
    
    	$name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');
    
    	// get service
    	$service = Service::model()->with(
    			array('item'=>array(
    					'alias'=>'i'
    			),
    					'item.category'
    			))->findByAttributes(array(), array(
    					'condition'=>'i.alias=:alias',// and i.user_id=:user_id
    					'params'=>array(
    							':alias'=>$name,
    							//':user_id'=>Yii::app()->user->id,
    					)));
    
    			if (!$service || !Yii::app()->user->checkAccess('services.update', array('record'=>$service))) {
    				throw new CHttpException(404, Yii::t('services', 'Service does not exist or is inactive.'));    				
    			}
    
    			$item = $service->item;
    
    			// editor
    			/*if (Yii::app()->user->checkAccess('services.update', array('record'=>$service))) {
    				$this->editorEnabled = true;
    			}*/
    
    			// change context
    			$search = Search::model()->getFromSession();
    			$search->type = 'service';
    			$search->action = $item->sell ? 'sell' : 'buy';
    
    			if ($item->category) {
    				$this->breadcrumbs = $item->category->generateBreadcrumbs();
    			} else {
    				$this->breadcrumbs = array();
    			}
    			$this->breadcrumbs []= $item->name;
    
    
    			$this->render('editGallery', array('product'=>$service));
    }
    
}