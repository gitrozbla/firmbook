<?php
/**
 * Kontroler akcji dla firmy.
 * 
 * @category controllers
 * @package company
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class CompaniesController extends Controller
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
        return 'show, offer, gallery, movies';//, partialupdate
    }
    
    /**
     * Wyświetla listę produktów/usług na stronie firmy.
     * @param string $name Nazwa firmy.
     * @throws CHttpException
     */
    public function actionOffer($name=null, $type='product')
    {      	
    	if($type!=='product' && $type!=='service')
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	
//     	$this->setPageTitle(Yii::app()->name.' - '.Yii::t('company', 'company').' '.$name);
        
        $name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');
        
        // get company
        $company = Company::model()->with(
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
                
        if (!$company) {
            throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
        }
        
        $item = $company->item;
        
        $this->setPageTitle(Yii::t('company', ucfirst($type).'s').' - '.$item->name.' - '.Yii::app()->name);
        
    	// editor
        if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))) {
            $this->editorEnabled = true;
        }
        
        // change context
        $search = Search::model()->getFromSession();
        $search->type = $type;
        if(!$item->buy || !$item->sell)
        	$search->action = $item->sell ? 'sell' : 'buy';
        
        if ($item->category) {
            $this->breadcrumbs = $item->category->generateBreadcrumbs();
        } else {
            $this->breadcrumbs = array();
        }
        $this->breadcrumbs [$item->name]= Yii::app()->createUrl('companies/show', array('name'=>$item->alias));
        $this->breadcrumbs []= Yii::t('company', ucfirst($type).'s');
            	
        $this->render('company-items', compact('company', 'type'));
    }
    
    /**
     * Wyświetla informacje o firmie.
     * @param string $name Nazwa firmy.
     * @throws CHttpException
     */
    public function actionShow($name=null)
    {   

        //////////////////////////////////////
//        $me = Yii::app()->user->getModel();
//        $elist = new Elist();
//    	$elist->unsetAttributes();
////        if($type!=Elist::TYPE_ELIST && $type!=Elist::TYPE_FAVORITE)
////    		$type = Elist::TYPE_FAVORITE;
////    	
////    	$elist->type = $type;
//    	$elist->item_id = $me->id;
//    	$elist->item_type = Elist::ITEM_TYPE_USER;
//        
//        $dataProvider = $elist->inverseRecipientsDataProvider();
//        $recipients = $dataProvider->getData();
//        var_dump($recipients);
//        
//        $follow = new Follow();
//    	$follow->unsetAttributes();   			 
//    	echo '<br>--------------------------------<br>';	 
//    	$follow->item_id = $me->id;
//    	$follow->item_type = Follow::ITEM_TYPE_USER;
//        $dataProvider = $follow->inverseRecipientsDataProvider();
//        $recipients = $dataProvider->getData();
//        var_dump($recipients);
        
//        var_dump(YII_DEBUG);
//        var_dump(Yii::app()->params['productionMode']);
//        echo '<br>url 7: '.$this->createUrl('/likedislike');
//        echo '<br>url 7: '.Yii::app()->getUrlManager()->createUrl('/rights');
//         $this->setPageTitle(Yii::app()->name.' - '.Yii::t('company', 'company').' '.$name);
        
        ////////////////////////////////////////////////////////////
//        $com = Company::model()->findByPk(43283);
//        echo '<br>url 7: '.$com->item_id;
        
//        echo '<br>url: '.Yii::app()->params['hostInfo'];
//        echo '<br>url: '.Yii::app()->params['creatorsUrl'];
        
        $name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');
        
        // get company
        $company = Company::model()->with(
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
                
        if (!$company) {
            throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
        }
        
        $item = $company->item;
//        $recipients = Social::getNewItemMessageRecipients($item);
//        var_dump($recipients);
        /////////////////////////////////////////////////////////
//        echo '<br>item id: '.$item->id;
//        echo '<br>--------------------------------<br>';
//        $elist->unsetAttributes();
//        $elist->item_id = $item->id;
//    	$elist->item_type = Elist::ITEM_TYPE_ITEM;
//        $dataProvider = $elist->inverseRecipientsDataProvider();
//        $recipients = $dataProvider->getData();
//        var_dump($recipients);
//        echo '<br>----------------------4----------<br>';	 
//    	$follow->item_id = $item->id;
//    	$follow->item_type = Follow::ITEM_TYPE_COMPANY;
//        $dataProvider = $follow->inverseRecipientsDataProvider();
//        $recipients = $dataProvider->getData();
//        var_dump($recipients);
        /////////////////////////////////
        
        
//        print_r($item->category);
        
        //         $this->setPageTitle(Yii::app()->name.' - '.Yii::t('company', 'company').' '.$item->name);
        //         $this->setPageTitle($item->name.' - '.Yii::t('company', 'Company').' - '.Yii::app()->name);
        $this->setPageTitle($item->name.' - '.Yii::app()->name);
        
        // editor
        if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))) {
            $this->editorEnabled = true;
        }
        
        // change context
        $search = Search::model()->getFromSession();
        $search->type = 'company';
        $search->action = $item->sell ? 'sell' : 'buy';        
        
        if ($item->category) {
            $this->breadcrumbs = $item->category->generateBreadcrumbs();
        } else {
            $this->breadcrumbs = array();
        }
        //$this->breadcrumbs []= Yii::t('common', 'Company');
        $this->breadcrumbs []= $item->name;       
		
//         var_dump($this->breadcrumbs);
        
        $productCount = Product::model()->with('item')->count(
            'company_id=:company_id and active=1',
//			'company_id=:company_id'.(!$this->editorEnabled ? ' and active=1' : ''),
            array(':company_id'=>$company->item_id)
    	);
    	$serviceCount = Service::model()->with('item')->count(
            'company_id=:company_id and active=1',
            array(':company_id'=>$company->item_id)
    	);
    	$newsCount = News::model()->count(
            'item_id=:item_id and active=1',
            array(':item_id'=>$company->item_id)
    	);
		
    	$item->saveCounters(array('view_count'=>'1'));
    	
        $this->render('company', compact('company', 'productCount', 'serviceCount', 'newsCount'));
    }
    
    /**
     * Modyfikuje informacje o firmie.
     * ID podane jest w $_GET/$_POST.
     * @param type $model Klasa modelu.
     */
    public function actionUpdate_old($model='Company')
    {
        $es = new EditableSaver($model);
        $es->scenario = 'update';
        $es->update();
    }
    
    public static function checkAccess($bizruleName, $params=array())
    {
        switch ($bizruleName) {
            case 'add':
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
                    if ($class=='Company') {
                        $item = $item->item;
                    }
                    $pk = $item->id;
                    $attribute = isset($params['attribute']) 
                            ? $params['attribute']
                            : '';
                } else {
                    $c = Yii::app()->controller;
                    switch($c->id.'.'.$c->action->id) {
                        case 'companies.update':
                            $request = Yii::app()->request;
                            $pk = $request->getParam('pk');
                            $class = $request->getParam('model');
                            $class = $class ? $class : 'Company';
                            $attribute = $request->getParam('name');
                            
                            if ($class=='Company') {
                                $item = Company::model()->findByPk($pk)->item;
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
                    case 'Company.short_description':
                    case 'Company.phone':
                    case 'Company.email':
                        return $item 
                                ? $item->user_id == Yii::app()->user->id
                                : false;
                        
                    case 'Company.':
                    case 'Item.':    
                        // for editor button
                        return $item->user_id == Yii::app()->user->id;
                        
                    default:
                        return false;
                        
                }
                
            case 'json_companies_list':
                $userId = Yii::app()->request->getParam('user_id');
                return $userId == Yii::app()->user->id;
                
                
            default:
                return false;
        }
    }
    public function actionAdd()
    {		 
        //obecnie max 4
        $categoriesCount = PackageControl::getValue(Yii::app()->user->package_id, 'categories_count');
        if($categoriesCount > 4)
            $categoriesCount = 4;
        $categoriesCount = 1;

        $company = new Company('create');
        $company->country = 'PL';
        $item = new Item('create');		
        if (isset($_POST['Company']) && isset($_POST['Item'])) {			
            $company->attributes = $_POST['Company'];				
            $item->attributes = $_POST['Item'];			
            $item->cache_package_id = Yii::app()->user->package_id;
            //$item->additionalCategories = array_filter($_POST['Item']['additionalCategories']);

            //obejscie mechanizmu kategorii
            /*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
                    $item->category_id = 255;*/

            if ($company->validate() && $item->validate()) {			
                //kupujacy, sprzedajacy				
                $item->buy = in_array(1, $item->account_type) ? 1 : 0;						    			
                $item->sell = in_array(2, $item->account_type) ? 1 : 0;					
                $item->cache_type = 'c';

                if(!in_array($item->color, Yii::app()->params->colors))
                    $item->color = NULL;

                $item->save();    

                $company->item_id = $item->id;       
                $company->payment_type = serialize($company->payment_type);
                $company->delivery_type = serialize($company->delivery_type);
                $company->save();

                Yii::app()->user->setFlash('success', Yii::t('companies', 'Company created.'));                
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
//                            Yii::t('category', 'Added to category'),
                            Yii::t('companies', 'New company added', [], null, $recipient['language']),    
                                
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
                            Yii::t('companies', 'New company added', [], null, $recipient['language']),    
                            $this->render('/companies/newCompanyEmail', compact('item', 'recipient'), true, true),                                      
                            $emailData
                        );  
                        Yii::app()->user->language = $userOrgLanguage;
                        Yii::app()->language = $appOrgLanguage;
                    }
                }
                
                // Dodanie kategorii do obserwowanych przez dodajacego uzytkownika
                if($item->category) {
                    $followedItem = Follow::model()->find(array(
                        'condition'=>'item_id=:item_id and user_id=:user_id and item_type=:item_type',
                        'params'=>array(':item_id'=>$item->category->id, ':user_id'=>Yii::app()->user->id, ':item_type'=>Follow::ITEM_TYPE_CATEGORY)
                    ));
                    if(!$followedItem)
                    {
                        $follow = new Follow();
                        $follow->item_id = $item->category->id;
                        $follow->user_id = Yii::app()->user->id;	    	
                        $follow->item_type = Follow::ITEM_TYPE_CATEGORY;
                        $follow->save();
                        $scenario = 1;
                    }    
                }
                
                $this->redirect($this->createUrl('show', 
                    array('name'=>$item->alias)));					
            }
        }

        /*$package = Package::model()->findByPk(Yii::app()->user->package_id);
        $item->color = $package->color;*/

        /*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
                $item->category_id = 5274;*/

        //echo 'typ:';
        //print_r($item->account_type);
        //$item->account_type = array(1, 2);
        /*
        $item->category_parent_id = 3;		
        $item->category_id = 302;*/	
        //$item->category_id = 255;

        /*$search = Search::model()->getFromSession();
        print_r($search);*/		

        $this->breadcrumbs = array();
        $this->breadcrumbs [Yii::t('navigation', 'My companies')]
                = $this->createUrl('/user/items/', array('type' => 'companies'));
        //$this->breadcrumbs []= Yii::t('companies', 'Add new company');		

        $this->render('company-form', compact('company', 'item', 'categoriesCount'));
    }
    
    public function actionUpdate()
    {		 
        $categoriesCount = PackageControl::getValue(Yii::app()->user->package_id, 'categories_count');
        if($categoriesCount > 4)
            $categoriesCount = 4;
        $categoriesCount = 1;

        $id = Yii::app()->request->getParam('id');	
        //$company = Company::model()->findByPk($id);
        // get company
        $company = Company::model()->with(
            array('item'=>array(
                            'alias'=>'i'
            ))
            )->findByAttributes(array(), array(
                            'condition'=>'i.id=:id',
                            //'condition'=>'i.id=:id and i.user_id=:user_id',
                            'params'=>array(
                                            ':id'=>$id,
                                            //':user_id'=>Yii::app()->user->id,
                            )));

        if (!$company || !Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))) {
            throw new CHttpException(404);
            //throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
        }	
        if(!$company->country)
            $company->country = 'PL';
        $item = $company->item;

        //w celu przechowania danych z rekordu, kóre w przypadku obiektów $company i $item mogą ulec zmianie
        $companyClone = clone $company;
        $companyClone->item = clone $company->item;

        if (isset($_POST['Company']) && isset($_POST['Item'])) {
            //print_r($_POST['Company']);
            $company->attributes = $_POST['Company'];				
            $item->attributes = $_POST['Item'];
            //$item->additionalCategories = array_filter($_POST['Item']['additionalCategories']);						

            //obejscie mechanizmu kategorii
            /*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
                    $item->category_id = 255;*/
            
//            var_dump($company->allegro);
            
            if ($company->validate() && $item->validate()) {
//                var_dump($company->allegro);
//                 exit;
                //print_r($company->attributes);
                /*if($item->alias == '')
                        $item->alias = $item->id;*/		
                //kupujacy, sprzedajacy				
                $item->buy = in_array(1, $item->account_type) ? 1 : 0;						    			
                $item->sell = in_array(2, $item->account_type) ? 1 : 0;

                if(!in_array($item->color, Yii::app()->params->colors))
                    $item->color = NULL;

                $item->save();           	

                $company->payment_type = serialize($company->payment_type);
                $company->delivery_type = serialize($company->delivery_type);
                $company->save();

                Yii::app()->user->setFlash('success', Yii::t('companies', 'Company updated.'));
                $this->redirect($this->createGlobalRouteUrl('companies/show', array('name'=>$item->alias)));  		

            }
        } else {
            if($item->alias == $item->id) 
                $item->alias='';

            $item->account_type = array();
            if($item->buy) {				
                $item->account_type[] = 1;
            }		
            if($item->sell) {				
                $item->account_type[] = 2;
            }		

            $company->payment_type = unserialize($company->payment_type);
            $company->delivery_type = unserialize($company->delivery_type);

            /*if(!$item->color) {
                    $package = Package::model()->findByPk($item->cache_package_id);
                    $item->color = $package->color;
            }*/	
        }			

        /*if($_SERVER['SERVER_NAME'] == 'firmbookeu.localhost')
                $item->category_id = 302;*/

        // editor
        /*if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))) {
            $this->editorEnabled = true;
        }*/	
        //echo 'alias'.$companyClone->item->alias;
        $this->breadcrumbs = array();
        //echo 'alias'.$itemData->name;
        //echo Yii::app()->createUrl('companies/show', array('name'=>$itemData->alias));
        $this->breadcrumbs [Yii::t('navigation', 'My companies')] 
                = $this->createUrl('/user/items/', array('type' => 'companies'));
        $this->breadcrumbs [$companyClone->item->name] = Yii::app()->createUrl('companies/show', array('name'=>$companyClone->item->alias));

        $this->render('company-form', compact('company', 'item', 'categoriesCount', 'companyClone'));
    }
	
	/**
     * Modyfikuje informacje o firmie.
     * ID podane jest w $_GET/$_POST.
     * @param type $model Klasa modelu.
     */
    public function actionPartialupdate($model='Company')
    {
        $es = new EditableSaver($model);
        $es->scenario = 'update';
        $es->update();
    }
	
    public function actionAdd_old() 
    {
        $company = new Company('create');
        $company->save();
        
        Yii::app()->user->setFlash('success', Yii::t('companies', 'Company created.'));
        $this->redirect($this->createUrl('show', 
                array('name'=>$company->item->alias)));
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
    	
    	
    	if (!$item || !Yii::app()->user->checkAccess('Companies.remove')) {
    		throw new CHttpException(404);
    		//throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	}    	
    	
        /*$item = Item::model()->findByAttributes(array(
            'alias' => $name,
        ));*/
        if ($item && $item->cache_type == 'c') {
            $item->delete();
            
            Yii::app()->user->setFlash('success', Yii::t('companies', 'Company removed.'));
            if ($return) {
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                $this->redirect($this->createUrl('/categories'));
            }            
        }
        
    }
    
    public function actionJson_companies_list($user_id, $query) 
    {
        $this->ajaxMode();
        
        echo CJSON::encode(
                Company::model()->companiesListArray($user_id, $query)
                );
    }

    /**
     * Wyświetla galerię obrazów na stronie firmy.
     * @param string $name Nazwa firmy.
     * @throws CHttpException
     */
    public function actionGallery($name=null)
    {
    	///$type='product';    	
    
//     	$this->setPageTitle(Yii::app()->name.' - '.Yii::t('company', 'company').' '.$name);
    
    	$name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');
    
    	// get company
    	$company = Company::model()->with(
    			array('item'=>array(
    					'alias'=>'i'
    			),
    					'item.category'//, 'item.files'
    			))->findByAttributes(array(), array(
    					'condition'=>'i.alias=:alias and (active or i.user_id=:user_id)',
    					'params'=>array(
    							':alias'=>$name,
    							':user_id'=>Yii::app()->user->id,
    					)));
    
    	if (!$company) {
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	}
    
    	$item = $company->item;
    
    	$this->setPageTitle(Yii::t('company', 'Gallery').' - '.$item->name.' - '.Yii::app()->name);
    	
    	// editor
    	if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))) {
    		$this->editorEnabled = true;
    	}
    
    			// change context
    			/*$search = Search::model()->getFromSession();
    			$search->type = $type;
    			if(!$item->buy || !$item->sell)
    				$search->action = $item->sell ? 'sell' : 'buy';*/
    
    	if ($item->category) {
    		$this->breadcrumbs = $item->category->generateBreadcrumbs();
    	} else {
    		$this->breadcrumbs = array();
    	}
    	$this->breadcrumbs [$item->name]= Yii::app()->createUrl('companies/show', array('name'=>$item->alias));    
    	//$this->breadcrumbs []= $item->name;    
    	$this->breadcrumbs []= Yii::t('company', 'Gallery');
    		 
    	$filesCount = UserFile::model()->count(
    			'data_id=:item_id',
    			array(':item_id'=>$company->item_id)
    	);
    	if (Yii::app()->user->isGuest) {
            $allowedFilesCount = PackageControl::getValue(null, 'item_pictures');
        } else {
	       $allowedFilesCount = PackageControl::getValue(Yii::app()->user->package_id, 'item_pictures');
        }
    	$tooLowPackage = false;
    	if(!($filesCount<$allowedFilesCount)) {
    		/*$service = PackageServiceMN::model()->with('service', 'package')->find(
    				'package_id='.Yii::app()->user->package_id.' and service.role=\'item_pictures\''
    		);*/
    	
    		$tooLowPackage = true;
    		/*$this->tooLowPackageMessage = Yii::t('site', 'Package:{package} / '.
    				'Service:{service} / '.
    				'Limit:{limit}<br />',
    				array('{package}' => Yii::t('packages', $service->package->name),
    						'{service}' => Yii::t('package.service.title', $service->service->name, array(), 'dbMessages'),
    						'{limit}' => $service->threshold
    				));*/
    	}
    	
    	$this->render('gallery', compact('company', 'tooLowPackage'));
    }
    
    /**
     * Wyświetla galerię filmów na stronie firmy.
     * @param string $name Nazwa firmy.
     * @throws CHttpException
     */
    public function actionMovies($name=null)
    {
    	$type='product';
    
//     	$this->setPageTitle(Yii::app()->name.' - '.Yii::t('company', 'company').' '.$name);
    
    	$name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');
    
    	// get company
    	$company = Company::model()->with(
            array('item'=>array(
                'alias'=>'i'
                ),
                'item.category'//, 'item.files'
            ))->findByAttributes(array(), array(
            'condition'=>'i.alias=:alias and (active or i.user_id=:user_id)',
            'params'=>array(
                            ':alias'=>$name,
                            ':user_id'=>Yii::app()->user->id,
            )));

        if (!$company) {
            throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
        }

        $item = $company->item;

        $this->setPageTitle(Yii::t('company', 'Movies').' - '.$item->name.' - '.Yii::app()->name);

        // editor
        if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))) {
            $this->editorEnabled = true;
        }

        // change context
        $search = Search::model()->getFromSession();
        $search->type = $type;
        if(!$item->buy || !$item->sell)
            $search->action = $item->sell ? 'sell' : 'buy';

        if ($item->category) {
            $this->breadcrumbs = $item->category->generateBreadcrumbs();
        } else {
            $this->breadcrumbs = array();
        }
        $this->breadcrumbs [$item->name]= Yii::app()->createUrl('companies/show', array('name'=>$item->alias));

        //$this->breadcrumbs []= $item->name;

        $this->breadcrumbs []= Yii::t('company', 'Movies');

        $this->render('movies', compact('company', 'type'));
    }
}
