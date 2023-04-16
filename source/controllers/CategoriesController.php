<?php
/**
 * Kontroler z akcjami przeglądania kategorii.
 * 
 * @category controllers
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class CategoriesController extends Controller 
{
    public $defaultAction = 'show';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return strings
     */
    public function allowedActions()
    {
        return 'index, show, search, clear_search, search_form, json_get_subcategories';
    }
    
    /**
     * Akcja ogólna, przekierowuje na stronę główną.
     */
    /*public function actionIndex() {
        $this->redirect(array('site/index'));
    }*/
    
    /**
     * Wyświetla listę subkategorii (dla pierwszego poziomu kategorii)
     * lub listę produktów/usług/firm (dla dalszych poziomów).
     * @param string $name Nazwa kategorii.
     * @throws CHttpException
     */
//     public function actionShow($username=null, $name=null, $context=null, $search=false, $query=null)
    public function actionShow($username=null, $name=null, $context=null, $search_context=false, $query=null)
    {      
//        echo '<br>CategoriesController->actionShow()';
        
    	// Jeśli w adresie brak członu /szukaj to czyścimy parametr query, aby wyszukiwał tylko gdy adres poprawny
//     	if(!$search_context) 
//     		$query = null;    		

    	/*
    	 * Jeśli context = null to powinno wyrzucic ze strona nei istnieje, ale ze musimy naprawic stare linki to
    	 * moze przekierowac na kontekst domyslny firmy/sprzedajace/name
    	 */    	
    	$request = Yii::app()->request;

    	// wyszukiwarka
    	$searchObject = Search::model()->getFromSession();
		
    	if($search_context && !trim($query)) {
            $search_context = null;
            unset($_GET['search_context']);
            unset($_GET['query']);
            // skoro nastąpi przekierowanie, to nie chcemy aby użyto podczas tworzenia url-a kategorii zachowanej w sesji 
            $searchObject->category = null;
            if($context) {
                unset($_GET['context']);
                $context_options = Search::contextToActionTypeContext($context);	    		
                if($name) {
                    $correct_url = $searchObject->createUrl(null, array($context_options[1].'_context'=>$context_options[0], 'name'=>$name));
                } else 
                    $correct_url = $searchObject->createUrl('site/index', array($context_options[1].'_context'=>$context_options[0]));
            }
            else {	
//     			$correct_url = $searchObject->createUrl('site/index', array('companies_context'=>Search::getContextUrlAction('sell','company'), 'name'=>$name));
            }
			
    	} else {		
            $correct_params = array();

            if($context) {
                    // usuwamy parametr context, aby podczas tworzenia urli niedodawał tego parametru, np. pager samsobie z tym nie poradzi
                    unset($_GET['context']);	    		
                    $context_options = Search::contextToActionTypeContext($context);

                    if($name) {    			
                            if($search_context && $query) {
                                    $correct_params = array('search_context'=>Yii::t('url', 'search'), $context_options[1].'_context'=>$context_options[0], 'name'=>$name);
                            } elseif($query) {		    			
// 		    			$correct_params = array('search_context'=>Yii::t('url', 'search'), $context_options[1].'_context'=>$context_options[0], 'name'=>$name);
                                    $correct_params = array('search_context'=>Yii::t('url', 'search'), 'query'=>$query, $context_options[1].'_context'=>$context_options[0], 'name'=>$name);
                            } else {		    			
                                    $correct_params = array($context_options[1].'_context'=>$context_options[0], 'name'=>$name);
                            }
                    } else {
                            $searchObject->category = null;
                            if($search_context && $query) {	    			
                                    $correct_params = array('search_context'=>Yii::t('url', 'search'), $context_options[1].'_context'=>$context_options[0]);
                            } elseif($query) {
                                    $correct_params = array('search_context'=>Yii::t('url', 'search'), 'query'=>$query, $context_options[1].'_context'=>$context_options[0]);
                            } else {	    				
                                    $correct_params = array($context_options[1].'_context'=>$context_options[0]);
                            }
                    }
            } else {
                    // jesli stary adres _kategorie/pokaz/nazwa/hurt-hurtownie bez kontekstu to przekierowujemy na firmy/sprzedajace/nazawa_kategorii
                    if($query) {
                            $correct_params = array('search_context'=>Yii::t('url', 'search'), 'query'=>$query, 'companies_context'=>Search::getContextUrlAction('sell','company'), 'name'=>$name);
                    } else {
                            $correct_params = array('companies_context'=>Search::getContextUrlAction('sell','company'), 'name'=>$name);
                    }	
            }
            $correct_url = $searchObject->createUrl(null, $correct_params);
    	} 	

        $request_url = $request->getUrl();
        $hostInfo = $request->getHostInfo();
        
    	if('/'.Yii::app()->request->pathInfo != $correct_url) {
            $correct_url = $correct_url.($search_context && Yii::app()->request->queryString ? '?'.Yii::app()->request->queryString : '');
            $redirect_url = $hostInfo.$correct_url;
            Yii::app()->request->redirect(
                            $correct_url,
                            301);
            exit();
    	} else {
            $correct_url = $correct_url.($search_context && Yii::app()->request->queryString ? '?'.Yii::app()->request->queryString : '');
            $redirect_url = $hostInfo.$correct_url;
    	}
   	
    	$page = isset($_GET['Item_page']) ? (int)$_GET['Item_page'] : 1;   	
    	
        if($name !== null) {
            // get category
            $name = Yii::t('inv.category.alias', $name, null, 'dbMessages');
            $category = Category::model()->findByAttributes(array('alias' => $name));
            if (!$category) {
                throw new CHttpException(404, Yii::t('categories', 'Category does not exist.'));
            }           
            
            // translate param back (for multilanguage url)
            $_GET['name'] = $name;
            Yii::app()->urlManager->registerParamToTranslate(
                    'name', array('Category', 'translate'));
        } else {
            $category = null;
            
        }
       
        // wyszukiwarka
//         $searchObject = Search::model()->getFromSession();
        $searchObject->assignParams(array(
            'category' => $name,
            'context' => $context,
            'query' => $query,
            'username' => $username,
            ));
        $searchObject->addLastSearch();
        $searchObject->saveToSession();
        $search = $searchObject;
        
        if($category) {
            $this->setPageTitle(Yii::t('category.name', $category->alias, array($category->alias=>$category->name), 'dbMessages').' - '.Yii::t('search', Search::getContextLongLabel($searchObject->action,$searchObject->type)).' - '.Yii::app()->name.($page>1 ? ' - '.Yii::t('common', 'Page').' '.$page : ''));
        } else {
            $this->setPageTitle((!empty($search->query) ? ucfirst($search->query): '').' '.Yii::t('search', 'in').' '.Yii::t('search', Search::getContextLongLabel($searchObject->action,$searchObject->type)).' - '.Yii::app()->name.($page>1 ? ' - '.Yii::t('common', 'Page').' '.$page : ''));
        }
        
        // ajax support
        if (Yii::app()->request->getParam('ajax') == 'item-list') {
            $this->renderPartial('_list', compact('category', 'search'));
            exit();
        }
       
        // breadcrumbs
        if ($category) {
            $this->breadcrumbs = $category->generateBreadcrumbs($search);
//            print_r($this->breadcrumbs);
            if(!empty($search->query))
            	$this->breadcrumbs[] = '';
        } else 
            $this->breadcrumbs = array('');
        
        // display mode: products or menu
        $settings = Settings::model()->find();
        if ($search !== null || ($category && $category->level >= $settings->items_from_level)) {
            // products view
            $view = 'show';
            // edit mode
            if (!Yii::app()->user->isGuest) {
                $this->editorEnabled = true;
            }
        } else {
            // menu view
            $view = 'menu';
        }

        $itemsDataProvider = Item::model()->searchDataProvider($search, $category);
        
        // w przypadku tej akcji "czyste" url-e (canonical) mogą zawierać jedynie parametr numeru podstrony i sortowania        
        if(!empty($search->query)) {
            $correct_params['query'] = $search->query;
        }
        
        if($page > 1) {
            $correct_params[$itemsDataProvider->getPagination()->pageVar] = $page;
        }
        
        if(isset($itemsDataProvider->getSort()->params['sort']))
            $correct_params['sort'] = $itemsDataProvider->getSort()->params['sort'];
        
        $canonical_url = $searchObject->createUrl(null, $correct_params);                        
        if($canonical_url != $correct_url)
            $this->setCanonicalUrl($canonical_url);      
        
//        if($category) {
//            echo '<br>category.id: '.$category->id;
//            echo '<br>category.id: '.$category->alias;
//            echo '<br>category.id: '.Yii::t('category.name', $category->alias, array($category->alias=>$category->name), 'dbMessages');
//            echo '<br>category.id: '.Yii::t('category.name', Yii::t('category.alias', $category->alias, null, 'dbMessages'), array($category->alias=>$category->name), 'dbMessages');
////            Social::getNewItemInCategoryMessageRecipients($category->id);
//        }    
        $this->render($view, compact('category', 'search', 'itemsDataProvider'));
    }      
    
    public function actionSearch_form($type)
    {
        $search = Search::model()->getFromSession();
        if ($type != 'advanced') {
            $type = 'simple';
        }
        
        $form = $this->renderPartial(
                '/layouts/mainParts/search'.ucfirst($type), 
                compact('search'),
                true
                );
        // search expand/collapse
        
        $this->runScript("searchToggleEnable('".$type."', ".json_encode($form).");");
        $this->endCOolAjax();
        
    }
    
	public function actionJson_get_subcategories_test($id=null, $query='', $src=null)
    {
    	$this->ajaxMode();
    	if(isset($id) && !$id) {
    		echo CJSON::encode(array());
    		return;
    	}	
    	if($id)
    		$id -= 1;
    	else
    		$id = null;
    	$kategorie1 = Category::kategorieData();
    	$kategorie2 = Category::podkategorieData();
    	/*$kategorie1 = array (
    		array('id'=>1, 'text'=>'Rolnictwo'),
    		array('id'=>2, 'text'=>'Motoryzacja'),
    		array('id'=>3, 'text'=>'Zdrowie'),
    		array('id'=>4, 'text'=>'Przemysł'),
    		array('id'=>5, 'text'=>'Gastro'),
    		array('id'=>6, 'text'=>'Dzieci'),
    		array('id'=>7, 'text'=>'Meble'),
    		array('id'=>8, 'text'=>'AGD'),
    		array('id'=>9, 'text'=>'Chemia'),    		
    		array('id'=>10, 'text'=>'WWW'),
    		array('id'=>11, 'text'=>'Prawo'),
    		array('id'=>12, 'text'=>'Market'),
    		array('id'=>13, 'text'=>'Dom'),
    		array('id'=>14, 'text'=>'Ogród'),
    		array('id'=>15, 'text'=>'Medycyna'),
    		array('id'=>16, 'text'=>'Rynki'),
    	);
    	$kategorie2 = array (
    		array(
	    		array('id'=>101, 'text'=>'Uslugi rolnicze'),
	    		array('id'=>102, 'text'=>'Nawozy'),
	    		array('id'=>103, 'text'=>'Narzedzia'),
	    	),
	    	array(
	    		array('id'=>201, 'text'=>'Samochody'),
	    		array('id'=>202, 'text'=>'Czesci'),
	    		array('id'=>203, 'text'=>'Uslugi moto'),
	    	),
	    	array(
	    		array('id'=>301, 'text'=>'Leki'),
	    		array('id'=>302, 'text'=>'Porady'),
	    		array('id'=>303, 'text'=>'Gabinety'),
	    	)	
	    	
    	);*/
    	/*echo '<br/>id: '.$id;
    	echo '<br/>query: '.$query;
    	echo '<br/>';*/
        
    	if(isset($id))
    	{
    		if(array_key_exists($id, $kategorie2))
    			$kategorie = $kategorie2[$id];
    		else
    			$kategorie = array();	
    	} else	
    		$kategorie = $kategorie1;

    		
    	if($query)
    	{
    		$kategorieTemp = array();
    		for($i=0;$i<count($kategorie);++$i)
    		{
    			if(strpos(strtolower($kategorie[$i]['text']), strtolower($query)) !== false)
    			{
    				$kategorieTemp[] = $kategorie[$i];
    			}
    		}
    		$kategorie = $kategorieTemp;
    	}	
    		
        
        echo CJSON::encode($kategorie);
    }
    
    public function actionJson_get_subcategories($id=null, $query='')
    {
    	/*echo '<br/>id: '.$id;
    	echo '<br/>query: '.$query;
    	echo '<br/>';*/
        $this->ajaxMode();
        
        $category = Category::model();
        if (!empty($id)) {
            $category = $category->findByPk($id);
        }
        
        echo CJSON::encode($category->getSubcategoriesData($query, true));
    }
}
