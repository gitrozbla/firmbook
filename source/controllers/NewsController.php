<?php
/**
 * Kontroler akcji dla aktualności.
 * 
 * @category controllers
 * @package news
 * @author
 * @copyright (C) 2015
 */
class NewsController extends Controller
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
        return 'show, list';
    }
    
    /**
     * Wyświetla artykuł.
     * @param string $name Nazwa produktu.
     * @throws CHttpException
     */
    public function actionShow($id)
    {    	
    	
    	$news = News::model()->find(
    			'id=:id and (active or user_id=:user_id)', // and active=1 
    			array(
    					':id'=>$id,
    					':user_id'=>Yii::app()->user->id,
    			));
    	
    	if (!$news)
    		throw new CHttpException(404, Yii::t('news', 'Article does not exist or is inactive.'));
    	//warunek na właściciela przenieść do Rights
    	/*if (!$news || (!$news->active && (Yii::app()->user->isGuest || Yii::app()->user->id != $news->user_id))) {
    		throw new CHttpException(404);
    	}*/
        
    	// get company
    	$company = Company::model()->with(
    			array(
    					'item'=>array(
    						'alias'=>'i'
    					),
    					'item.category'
    			))->findByAttributes(array(), array(
    					'condition'=>'i.id=:id and (i.active or i.user_id=:user_id)',
    					'params'=>array(
    							':id'=>$news->item_id,
    							':user_id'=>Yii::app()->user->id,
    					)));
    	
    	if (!$company)
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));    	
    	
    	// editor - nie widze sensu dla kolejnych regul, wlasciciel firmy jest wlascicielem newsa
    	if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company)))
    		$this->editorEnabled = true;   	
    	
    	$item = $company->item; 
    	if ($item->category) {
    		$this->breadcrumbs = $item->category->generateBreadcrumbs();
    	} else {
    		$this->breadcrumbs = array();
    	}
    	$this->breadcrumbs [$item->name]= Yii::app()->createUrl('companies/show', array('name'=>$item->alias));    	
    	$this->breadcrumbs [Yii::t('news', 'News')] = Yii::app()->createUrl('news/list', array('name'=>$item->alias));
    	//$this->breadcrumbs []= Yii::t('company', 'News');
    	
    	$this->render('show', compact('company', 'news'));        
    }
    
    
    /*
     * Lista artykułów w kontekscie obiektu
     * obecnie tylko artykuły w kontekście firmy
     */
    public function actionList($name)
    {       	
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
    		//throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	}
    	
    	// editor
    	if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company)))
    		$this->editorEnabled = true;    	    	
    	
    	//sortowanie listy aktualności
    	/*$news = new News('view');
    	$news->unsetAttributes();
    	if(isset($_GET['News'])) {
    		$news->attributes = $_GET['News'];
    	}
    	$news->item_id = $company->item_id;*/    	
    	
    	$item = $company->item;
    	
    	$this->setPageTitle(Yii::t('news', 'News').' - '.$item->name.' - '.Yii::app()->name);
    	
    	if ($item->category) {
    		$this->breadcrumbs = $item->category->generateBreadcrumbs();
    	} else {
    		$this->breadcrumbs = array();
    	}
    	$this->breadcrumbs [$item->name]= Yii::app()->createUrl('companies/show', array('name'=>$item->alias));
    	$this->breadcrumbs [] = Yii::t('news', 'News');
    	
    	$this->render('newsList', compact('company', 'news'));
    } 
    
    
    public function actionAdd($company)
    {
		$company = Company::model ()->with ( array (
				'item' => array (
						'alias' => 'i' 
				),
				'item.category' 
		))->findByAttributes ( array (), array (
				'condition' => 'i.id=:item_id',
				//'condition' => 'i.id=:item_id and i.user_id=:user_id',
				'params' => array (
						':item_id' => $company,
						//':user_id' => Yii::app ()->user->id 
				) 
		));
    
		if (!$company || !Yii::app()->user->checkAccess('News.add', array('record'=>$company->item))) {
			throw new CHttpException(404);
		}
		
		/*if (!$company) {
			//throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
			throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
		}*/
		
    	$news = new News('create');    
		$news->item_id = $company->item_id;		
    					
    	if (isset($_POST['News'])) {    	
    		$news->attributes = $_POST['News'];
    		if ($news->validate()) {
    			$news->item_id = $company->item_id;    			
    			$news->save();
    						 
    			Yii::app()->user->setFlash('success', Yii::t('news', 'Article created.'));
    			$this->redirect($this->createGlobalRouteUrl('news/show', array('id'=>$news->id)));
    		}
   		}
   		
   		$this->render('newsForm', compact('news', 'company'));
    }
    
    public function actionUpdate()
    {    
    	$id = Yii::app()->request->getParam('id');
    	//$news = News::model()->findByPk($id);
    	$news = News::model()->find(
    			'id=:id',
    			array(
    					':id'=>$id,    					
    			));
    	
    	if (!$news || !Yii::app()->user->checkAccess('News.update', array('record'=>$news))) {
    		throw new CHttpException(404);
    		//throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
    	}    	
    	
    	$company = Company::model ()->with ( array (
    			'item' => array (
    					'alias' => 'i'
    			),
    			'item.category'
    	))->findByAttributes ( array (), array (
    			'condition' => 'i.id=:item_id',
    			//'condition' => 'i.id=:item_id and i.user_id=:user_id',
    			'params' => array (
    					':item_id' => $news->item_id,
    					//':user_id' => Yii::app ()->user->id
    			)
    	));
    	
    	if (!$company) {
    		// || !Yii::app()->user->checkAccess('Companies.update', array('record'=>$company))
    		// nie ma sensu sprawdzac uprawnien jesli uzytkownik jest wlascicielem firmy
    		//throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	}
    	
    	if (isset($_POST['News'])) {
    		$news->attributes = $_POST['News'];
    		if ($news->validate()) {    			
    			$news->save();
    				
    			Yii::app()->user->setFlash('success', Yii::t('news', 'Article updated.'));
    			$this->redirect($this->createGlobalRouteUrl('news/show', array('id'=>$news->id)));
    		}
    	}
    	
    	$this->render('newsForm', compact('news', 'company'));
    }	
    	
    public function actionRemove($id)
    {
    	
    	$news = News::model()->with('item')->find(
    			't.id=:id',
    			array(
    					':id'=>$id    					
    			));    	
    	
    	if (!$news || !Yii::app()->user->checkAccess('News.remove', array('record'=>$news))) {
    		throw new CHttpException(404);    		
    		//throw new CHttpException(404, Yii::t('news', 'Article does not exist or is inactive.'));
    	}    	    	
    	
    	$news->delete();  
    	  
    	Yii::app()->user->setFlash('success', Yii::t('news', 'Article removed.'));
    		
    	$this->redirect( $this->createGlobalRouteUrl(
                                       '/news/list',
                                       array('name'=>$news->item->alias)
                                       ));    	
    
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
    		case 'update':    			
    			$news = $params['record'];
    			return $news->user_id == Yii::app()->user->id;
    			
    		default:
    			return false;
    	}
    }
}