<?php
/**
 * Kontroler akcji dla produktu.
 * 
 * @category controllers
 * @package product
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class ProductsController extends Controller
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
        return 'show';//, edit_gallery, partialupdate
    }
    
    /**
     * Wyświetla informacje o produkcie.
     * @param string $name Nazwa produktu.
     * @throws CHttpException
     */
    public function actionShow($name=null)
    {       	
//         $this->setPageTitle(Yii::app()->name.' - '.Yii::t('product', 'product').' '.$name);        
        $name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');

        // get product
        $product = Product::model()->with(
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
        
        if (!$product) {
            throw new CHttpException(404, Yii::t('products', 'Product does not exist or is inactive.'));
        }
        
        $item = $product->item;
        
        $this->setPageTitle($item->name.' - '.Yii::app()->name);
        
        // editor
        if (Yii::app()->user->checkAccess('Products.update', array('record'=>$product))) {
            $this->editorEnabled = true;
        }
        
        // change context
        $search = Search::model()->getFromSession();
        $search->type = 'product';
        $search->action = $item->sell ? 'sell' : 'buy';
        
        if ($item->category) {
            $this->breadcrumbs = $item->category->generateBreadcrumbs($search);
        } else {
            $this->breadcrumbs = array();
        }
        $this->breadcrumbs []= $item->name;        
        
        $item->saveCounters(array('view_count'=>'1'));
        
        $this->pageDescription = $product->short_description;

        $this->render('product', compact('product'));
    }
    
    /**
     * Modyfikuje informacje o produkcie.
     * ID podane jest w $_GET/$_POST.
     * @param type $model Klasa modelu.
     */
    public function actionPartialupdate($model='Product')
    {
        $es = new EditableSaver($model);
        $es->scenario = 'update';
        $es->update();
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
                    if ($class=='Product') {
                        $item = $item->item;
                    }
                    $pk = $item->id;
                    $attribute = isset($params['attribute']) 
                            ? $params['attribute']
                            : '';
                } else {
                    $c = Yii::app()->controller;
                    switch($c->id.'.'.$c->action->id) {
                        case 'products.update':
                            $request = Yii::app()->request;
                            $pk = $request->getParam('pk');
                            $class = $request->getParam('model');
                            $class = $class ? $class : 'Product';
                            $attribute = $request->getParam('name');
                            
                            if ($class=='Product') {
                                $item = Product::model()->findByPk($pk)->item;
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
                    case 'Product.company_id':
                        return $item 
                                ? $item->user_id == Yii::app()->user->id
                                : false;
                        
                    case 'Product.':
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
        $product = new Product('create');
        $product->save();
        
        Yii::app()->user->setFlash('success', Yii::t('products', 'Product created.'));
        $this->redirect($this->createUrl('show', 
                array('name'=>$product->item->alias)));
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
    	
    	if (!$item || !Yii::app()->user->checkAccess('Products.remove')) {
    		throw new CHttpException(404);    		
    	}    	
    	
        /*$item = Item::model()->findByAttributes(array(
            'alias' => $name,
        ));*/
        if ($item && $item->cache_type == 'p') {
            $item->delete();
            
            Yii::app()->user->setFlash('success', Yii::t('products', 'Product removed.'));
            if ($return) {            	
                $this->redirect(Yii::app()->user->returnUrl);
            } else {            	
                $this->redirect($this->createGlobalRouteUrl('/categories'));
            }
        }        
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

            if (!$company || !Yii::app()->user->checkAccess('Products.add', array('record'=>$company->item))) {
                throw new CHttpException(404);
                //throw new CHttpException(404, Yii::t('products', 'Product does not exist or is inactive.'));
            }          

        }

        $product = new Product('create');
        $item = new Item('create');	

        if (isset($_POST['Product']) && isset($_POST['Item'])) {			

            $product->attributes = $_POST['Product'];				
            $item->attributes = $_POST['Item'];
            //$item->additionalCategories = array_filter($_POST['Item']['additionalCategories']);

            $item->cache_package_id = Yii::app()->user->package_id;			

            if ($product->validate() && $item->validate()) {			
                //kupujacy, sprzedajacy				
                $item->buy = in_array(1, $item->account_type) ? 1 : 0;						    			
                $item->sell = in_array(2, $item->account_type) ? 1 : 0;					
                $item->cache_type = 'p';
                $item->save(); 

                $product->item_id = $item->id;            	
                $product->save();
                
                Yii::app()->user->setFlash('success', Yii::t('products', 'Product created.'));
                
                if(Yii::app()->params['productionMode'] && $item->category && $item->user->send_emails) {
                    $this->layout = 'mail';                    
                    $recipients = Social::getNewItemInCategoryMessageRecipients($item->category->id);
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
                            Yii::t('products', 'New product added', [], null, $recipient['language']),    
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
                            Yii::t('products', 'New product added', [], null, $recipient['language']),    
                            $this->render('/products/newProductEmail', compact('company', 'item', 'recipient'), true, true),                                      
                            $emailData
                        );  
                        Yii::app()->user->language = $userOrgLanguage;
                        Yii::app()->language = $appOrgLanguage;
                    }
                }    
                $this->redirect($this->createGlobalRouteUrl('products/show', array('name'=>$product->item->alias)));					
            }
        } else {
            if($company) {
                $product->company_id = $company->item_id;
                $item->category_id = $company->item->category_id;	
            }			
        }   						

        $this->breadcrumbs = array();
        $this->breadcrumbs [Yii::t('navigation', 'My products')]
            = $this->createUrl('/user/items/', array('type' => 'products'));		
        if($company)
            $this->breadcrumbs [$company->item->name]= Yii::app()->createUrl('companies/show', array('name'=>$company->item->alias));
        //$this->breadcrumbs []= Yii::t('products', 'Add new product');
        $this->setLoadRecaptchaAPI(true);
        $this->render('product-form', compact('product', 'item', 'categoriesCount', 'company'));
    }
	
    public function actionUpdate()
    {		 
            //obecnie max 4
            $categoriesCount = PackageControl::getValue(Yii::app()->user->package_id, 'categories_count');
            if($categoriesCount > 4)
                    $categoriesCount = 4;
            $categoriesCount = 1;

            $id = Yii::app()->request->getParam('id');

            // get product
            $product = Product::model()->with(
                            array('item'=>array(
                                            'alias'=>'i'
                            )/*,
                                            'item.category'*/
                            ))->findByAttributes(array(), array(
                                            'condition'=>'i.id=:id',
                                            //'condition'=>'i.id=:id and i.user_id=:user_id',
                                            'params'=>array(
                                                            ':id'=>$id,
                                                            //':user_id'=>Yii::app()->user->id,
                                            )));

            if (!$product || !Yii::app()->user->checkAccess('Products.update', array('record'=>$product))) {
                    throw new CHttpException(404);
                    //throw new CHttpException(404, Yii::t('products', 'Product does not exist or is inactive.'));
            }		

            $item = $product->item;		
            $itemClone = clone $item;		

            if (isset($_POST['Product']) && isset($_POST['Item'])) {

                    $product->attributes = $_POST['Product'];				
                    $item->attributes = $_POST['Item'];

                    //$item->additionalCategories = array_filter($_POST['Item']['additionalCategories']);

                    if ($product->validate() && $item->validate()) {				
                            //kupujacy, sprzedajacy				
                            $item->buy = in_array(1, $item->account_type) ? 1 : 0;						    			
                            $item->sell = in_array(2, $item->account_type) ? 1 : 0;					

            $item->save();            	
                    $product->save();    			

                    Yii::app()->user->setFlash('success', Yii::t('products', 'Product updated.'));
                    $this->redirect($this->createGlobalRouteUrl('products/show', array('name'=>$product->item->alias)));					
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

                    $product->promotion_expire = Yii::app()->dateFormatter->format('MM/dd/yyyy', $product->promotion_expire);	
            }			

            $company = $product->company;

            $this->breadcrumbs = array();
            $this->breadcrumbs [Yii::t('navigation', 'My products')]
                    = $this->createUrl('/user/items/', array('type' => 'products'));
            if($company)
                    $this->breadcrumbs [$company->item->name]= Yii::app()->createUrl('companies/show', array('name'=>$company->item->alias));
            $this->breadcrumbs [$itemClone->name] = $this->createUrl('show', array('name' => $itemClone->alias));
            //$this->breadcrumbs []= Yii::t('product', 'Edit product data');

            $this->render('product-form', compact('product', 'item', 'categoriesCount', 'company'));
    }

    public function actionEdit_gallery($name=null) 
    {
            $this->setPageTitle(Yii::app()->name.' - '.Yii::t('product', 'product').' '.$name);

            $name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');

            // get product
            $product = Product::model()->with(
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

            if (!$product || !Yii::app()->user->checkAccess('Products.update', array('record'=>$product))) {
                    throw new CHttpException(404, Yii::t('products', 'Product does not exist or is inactive.'));
            }

            $item = $product->item;

            // editor
            /*if (Yii::app()->user->checkAccess('Products.update', array('record'=>$product))) {
                    $this->editorEnabled = true;
            }*/

            // change context
            $search = Search::model()->getFromSession();
            $search->type = 'product';
            $search->action = $item->sell ? 'sell' : 'buy';

            if ($item->category) {
                    $this->breadcrumbs = $item->category->generateBreadcrumbs();
            } else {
                    $this->breadcrumbs = array();
            }

            $this->breadcrumbs [$item->name] = Yii::app()->createUrl('products/show', array('name'=>$item->alias));
            $this->breadcrumbs [] = Yii::t('product', 'Edit gallery');

            $this->render('editGallery', compact('product'));
    }	

    public function actionDelete($id)
    {
            $file = CreatorsFile::model()->with('company.item.user')->findByPk($id);
            if (!$file) {
                    throw new CHttpException(404);
            } else if ($file->company->item->user->id != Yii::app()->user->id) {
                    throw new CHttpException(403);
            }

            if ($file->delete()) {
                    Yii::app()->user->setFlash('success', Yii::t('CreatorsModule.file', 'File removed.'));

                    $this->redirect(Yii::app()->user->returnUrl);
            }
    }	
	
}