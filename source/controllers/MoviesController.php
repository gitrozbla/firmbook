<?php
/**
 * Kontroler akcji dla firmy.
 * 
 * @category controllers
 * @package company
 * @author 
 * @copyright (C) 2015
 */
class MoviesController extends Controller
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
     * Wyświetla film.
     * @param string $name Nazwa produktu.
     * @throws CHttpException
     */
    public function actionShow($id)
    {
    	$movie = Movie::model()->find(
    			'id=:id', 
    			array(
    					':id'=>$id,    					
    			));
    	
    	if (!$movie)
    		throw new CHttpException(404, Yii::t('movies', 'Movie does not exist.'));
    	        
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
    							':id'=>$movie->item_id,
    							':user_id'=>Yii::app()->user->id,
    					)));
    	
    	if (!$company)
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));    	
    	
    	// editor - nie widze sensu dla kolejnych regul, wlasciciel firmy jest wlascicielem newsa
    	if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company)))
    		$this->editorEnabled = true;   	
    	
    	$item = $company->item; 
    	
//     	$this->setPageTitle(Yii::t('company', 'Movies').' - '.$item->name.' - '.Yii::app()->name);
    	
    	if ($item->category) {
    		$this->breadcrumbs = $item->category->generateBreadcrumbs();
    	} else {
    		$this->breadcrumbs = array();
    	}
    	$this->breadcrumbs [$item->name]= Yii::app()->createUrl('companies/show', array('name'=>$item->alias));    	
    	$this->breadcrumbs [Yii::t('movies', 'Movies')] = Yii::app()->createUrl('movies/list', array('name'=>$item->alias));
    	//$this->breadcrumbs []= Yii::t('company', 'News');
    	
    	$this->render('show', compact('company', 'movie'));
    }	
    /**
     * Wyświetla galerię filmów na stronie firmy.
     * @param string $name Nazwa firmy.
     * @throws CHttpException
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
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	}
    			 
    	// editor
    	if (Yii::app()->user->checkAccess('Companies.update', array('record'=>$company)))
    		$this->editorEnabled = true;
    	    			 
    	$item = $company->item;
    	
    	$this->setPageTitle(Yii::t('movies', 'Movies').' - '.$item->name.' - '.Yii::app()->name);
    	
    	if ($item->category) {
    		$this->breadcrumbs = $item->category->generateBreadcrumbs();
    	} else {
    		$this->breadcrumbs = array();
    	}
    	$this->breadcrumbs [$item->name]= $this->createGlobalRouteUrl('companies/show', array('name'=>$item->alias));
    	$this->breadcrumbs [] = Yii::t('movies', 'Movies');
    					 
    	$this->render('list', compact('company'));
    }
    
    public function actionAdd($name)
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
    					':item_id' => $name,
    					//':user_id' => Yii::app ()->user->id
    			)
    	));
    
    	if (!$company || !Yii::app()->user->checkAccess('Movies.add', array('record'=>$company->item))) {
    		throw new CHttpException(404);
    	}
       
    	$moviesCount = Movie::model()->count(
			'item_id=:item_id',
			array(':item_id'=>$company->item_id)
		);    	
    	$allowedMoviesCount = PackageControl::getValue(Yii::app()->user->package_id, 'item_movies');    	
    	if(!($moviesCount<$allowedMoviesCount)) {
    		$service = PackageServiceMN::model()->with('service', 'package')->find(
    				'package_id='.Yii::app()->user->package_id.' and service.role=\'item_movies\''
    		);
    		
    		$this->tooLowPackage = true;
    		$this->tooLowPackageMessage = Yii::t('site', 'Package:{package} / '.
					'Service:{service} / '.
					'Limit:{limit}<br />', 
    				array('{package}' => Yii::t('packages', $service->package->name),
    					'{service}' => Yii::t('package.service.title', $service->service->name, array(), 'dbMessages'),
    					'{limit}' => $service->threshold
    				));
    	}
    	
    	$movie = new Movie('create');
    		
    	if (isset($_POST['Movie'])) {
    		$movie->attributes = $_POST['Movie'];
    		if ($movie->validate()) {
    			$movie->item_id = $company->item_id;
    			$movie->save();
    				
    			Yii::app()->user->setFlash('success', Yii::t('movies', 'Movie created.'));
    			$this->redirect($this->createGlobalRouteUrl('movies/show', array('id'=>$movie->id)));
    		}
    	}
    	
    	$this->breadcrumbs = array();
    	$this->breadcrumbs [$company->item->name]= Yii::app()->createUrl('companies/show', array('name'=>$company->item->alias));
    	$this->breadcrumbs [Yii::t('movies', 'Movies')] = Yii::app()->createUrl('movies/list', array('name'=>$company->item->alias));
    	//$this->breadcrumbs []= Yii::t('company', 'News');
    	 
    	$this->render('movieForm', compact('movie', 'company'));
    }
    
    public function actionUpdate($id)
    {
    	//$id = Yii::app()->request->getParam('id');
    	//$news = News::model()->findByPk($id);
    	$movie = Movie::model()->find(
    			'id=:id',
    			array(
    					':id'=>$id,
    			));
    	 
    	if (!$movie || !Yii::app()->user->checkAccess('Movies.update', array('record'=>$movie))) {
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
    					':item_id' => $movie->item_id,
    					//':user_id' => Yii::app ()->user->id
    			)
    	));
    	 
    	if (!$company) {    		
    		throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
    	}
    	 
    	if (isset($_POST['Movie'])) {
    		$movie->attributes = $_POST['Movie'];
    		if ($movie->validate()) {
    			$movie->save();
    
    			Yii::app()->user->setFlash('success', Yii::t('movies', 'Movie updated.'));
    			$this->redirect($this->createGlobalRouteUrl('movies/show', array('id'=>$movie->id)));
    		}
    	}
    	 
    	$this->breadcrumbs = array();
    	$this->breadcrumbs [$company->item->name]= Yii::app()->createUrl('companies/show', array('name'=>$company->item->alias));
    	$this->breadcrumbs [Yii::t('movies', 'Movies')] = Yii::app()->createUrl('movies/list', array('name'=>$company->item->alias));
    	//$this->breadcrumbs []= Yii::t('company', 'News');
    	
    	$this->render('movieForm', compact('movie', 'company'));
    }
    
    public function actionRemove($id)
    {
    	 
    	$movie = Movie::model()->with('item')->find(
    			't.id=:id',
    			array(
    					':id'=>$id
    			));
    	 
    	if (!$movie || !Yii::app()->user->checkAccess('Movies.remove', array('record'=>$movie))) {
    		throw new CHttpException(404);    		
    	}
    	 
    	$movie->delete();
    	 
    	Yii::app()->user->setFlash('success', Yii::t('movies', 'Movie removed.'));
    
    	$this->redirect( $this->createGlobalRouteUrl(
    			'/movies/list',
    			array('name'=>$movie->item->alias)
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
