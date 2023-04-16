<?php

class AdminController extends Controller 
{
    
    public function actionStatistics()
    {
        $this->render('statistics');
    }
    
    public function actionUsers()
    {
        $user = new User('adminSearch');
        $user->unsetAttributes();
        if(isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        } else 
        	$user->register_source = User::REGISTER_SOURCE_FIRMBOOK;
        
        $params =array(
            'user'=>$user,
        );        
        
        $this->render('users', $params);
    }
    
	public function actionCompanies()
    {
        $company = new Company('adminSearch');
        $company->unsetAttributes();
        if(isset($_GET['Company'])) {
            $company->attributes = $_GET['Company'];
        }
        
        $params =array(
            'company'=>$company,
        );
        
        $this->render('companies', $params);
    }
    
    /*
     * pakiety
     */
    public function actionPackages()
    {  		 
  		$this->render('packages');
    }
	public function actionPackagesServices()
    {  		 
  		$this->render('packages-services');
    }
	public function actionPackagesPeriods()
    {  		 
  		$this->render('packages-periods');
    }
    
	//Ajax - aktualizacja kolejności z GridView
	public function actionPackagesSortPackages()
	{
	    if (Yii::app()->request->isAjaxRequest && isset($_POST['items']) && is_array($_POST['items'])) {
	        $i = 1;
	        foreach ($_POST['items'] as $item) {
	        	$package = Package::model()->findByPk($item);	            
	            $package->order_index = $i;
	            $package->save();
	            $i++;
	        }
	    }
	}
	
	public function actionPackagesAddPackage()
	{		 
		$package = new Package;		
		if (isset($_POST['Package'])) {
			$package->attributes = $_POST['Package'];
			
			$criteria = new CDbCriteria;
			$criteria->condition = '!creators';
			$criteria->order = 'order_index DESC';
			$row = Package::model()->find($criteria);
			if($row)
				$package->order_index = ++$row->order_index;			
			else
				$package->order_index = 1;	
						
			if ($package->validate()) {    			
    			$package->save();
    			$this->redirect($this->createUrl('/admin/packages'));
    		}
		}			
		$this->render('packages-package-form', compact('package'));
	}
	
	public function actionPackagesUpdatePackage()
	{		
        $id = Yii::app()->request->getParam('id');		
       	$package = Package::model()->findByPk($id);		
		if (isset($_POST['Package'])) {			
			$package->attributes = $_POST['Package'];    		
    		if ($package->validate()) {    			    			
    			$package->save();    			
			   	$this->redirect($this->createUrl('/admin/packages'));
    		}	
		}				
		$this->render('packages-package-form', compact('package'));
	}
	
	//AJAX - usunięcie pakietu
	public function actionPackagesRemovePackage()
	{			
		$id = Yii::app()->request->getParam('id');		
       	$package = Package::model()->findByPk($id);
       	
       	//aktualizacja order index dla wierszy z wyzszym order_index
       	Package::model()->updateCounters(array('order_index'=>-1), 'order_index>:order_index and !creators', array(':order_index'=>$package['order_index']));
       	//PackageService::model()->updateAll('order_index=order_index-1', 'order_index>:order_index', array(':order_index'=>$service['order_index']));       		
	    $package->delete();
	       	
		//echo "<div class='flash-success'>Ajax - Deleted Successfully</div>";
		//echo "<div class='flash-error'>Ajax - error message</div>"; //for ajax
		//Yii::app()->user->setFlash('success', Yii::t('packages', 'Element has been removed.'));		
	}
	
    //Ajax - aktualizacja kolejności z GridView
	public function actionPackagesSortServices()
	{
	    if (Yii::app()->request->isAjaxRequest && isset($_POST['items']) && is_array($_POST['items'])) {
	        $i = 1;
	        foreach ($_POST['items'] as $item) {
	        	$service = PackageService::model()->findByPk($item);	            
	            $service->order_index = $i;
	            $service->save();
	            $i++;
	        }
	    }
	}
	
	public function actionPackagesAddService()
	{		 
		$service = new PackageService;		
		if (isset($_POST['PackageService'])) {
			$service->attributes = $_POST['PackageService'];
			
			$criteria = new CDbCriteria;
			$criteria->condition = '!creators';
			$criteria->order = 'order_index DESC';
			$row = PackageService::model()->find($criteria);
			if($row)
				$service->order_index = ++$row->order_index;
			else
				$service->order_index = 1;			
						
			if ($service->validate()) {    			
    			$service->save();    			
    			$this->redirect($this->createUrl('/admin/packagesservices'));
    		}
		}			
		$this->render('packages-service-form', compact('service'));
	}	
	
	public function actionPackagesUpdateService()
	{		
        $id = Yii::app()->request->getParam('id');		
       	$service = PackageService::model()->findByPk($id);        
		if (isset($_POST['PackageService'])) {			
			$service->attributes = $_POST['PackageService'];    		
    		if ($service->validate()) {    			    			
    			$service->save();    			
			   	$this->redirect($this->createUrl('/admin/packagesservices'));
    		}	
		}				
		$this->render('packages-service-form', compact('service'));
	}
	
	//AJAX - usunięcie usługi
	public function actionPackagesRemoveService()
	{			
		$id = Yii::app()->request->getParam('id');		
       	$service = PackageService::model()->findByPk($id);       	
       	
       	//zaktualizowac order index dla wierszy z wyzszym order_index
       	PackageService::model()->updateCounters(array('order_index'=>-1), 'order_index>:order_index and !creators', array(':order_index'=>$service['order_index']));
       	$service->delete();
	    
       	//Yii::app()->user->setFlash('success', Yii::t('packages', 'Element has been removed.'));		
	}
	
	public function actionPackagesAddPeriod()
	{				 
		$period = new PackagePeriod;		
		if (isset($_POST['PackagePeriod'])) {			
			$period->attributes = $_POST['PackagePeriod'];			
			if ($period->validate()) {    			
    			$period->save();
    			$this->redirect($this->createUrl('/admin/packagesperiods'));
    		}
		}		
		$this->render('packages-period-form', compact('period'));
	}
	
	public function actionPackagesUpdatePeriod()
	{		
        $id = Yii::app()->request->getParam('id');		
        $periodId = Yii::app()->request->getParam('period');
       	$period = PackagePeriod::model()->findByPk(array('package_id'=>$id, 'period'=>$periodId));        
		if (isset($_POST['PackagePeriod'])) {			
			$period->attributes = $_POST['PackagePeriod'];    		
    		if ($period->validate()) {    			    			
    			$period->save();    			
			   	$this->redirect($this->createUrl('/admin/packagesperiods'));
    		}	
		}				
		$this->render('packages-period-form', compact('period'));
	}
	
	//AJAX - usunięcie okresu
	public function actionPackagesRemovePeriod()
	{			
		$id = Yii::app()->request->getParam('id');	
		$periodId = Yii::app()->request->getParam('period');	
		
       	$period = PackagePeriod::model()->findByPk(array('package_id'=>$id, 'period'=>$periodId));       	       		       		
	    $period->delete();	           			
	}
	
	public function actionPackagesPackageServices()
	{		
		$id = Yii::app()->request->getParam('id');		
       	$package = Package::model()->findByPk($id);       	
		$selectedIds = array();
		$selectedValue = array();		
		
		if (!empty($_POST)) {			
			if (isset($_POST['selectedIds']))
				$selectedIds = $_POST['selectedIds'];			
			
			if (isset($_POST['selectedValue']))
				$selectedValue = $_POST['selectedValue'];				
				
			$transaction = Yii::app()->db->beginTransaction();
			try { 
				Yii::app()->db->createCommand()
					->delete('tbl_package_service_mn','package_id=:package_id',array(':package_id'=>$package->id));

				foreach($selectedIds as $selectedId) {					
					$tempRow = array(
			        		'package_id' => $package->id,
			        		'service_id' => $selectedId,			        					        		     		
			        	);  
			        	
			        if(isset($selectedValue[$selectedId]) && $selectedValue[$selectedId])			        	
			        	$tempRow['threshold'] = $selectedValue[$selectedId];
			        	
					Yii::app()->db->CreateCommand()
			        	->insert('tbl_package_service_mn', $tempRow);	
				}    	
				
				$transaction->commit();			
			}
			catch(Exception $e)
			{
				$transaction->rollback();			
			}    
		} else {
			//uslugi w pakiecie;
	    	$packageServices = PackageServiceMN::model()->findAll(
	    		'package_id=:package_id',
	    		array(':package_id'=>$package->id)
	    	);
			foreach($packageServices as $packageService) {
				$selectedIds[] = $packageService['service_id'];
				if($packageService['threshold'])
					$selectedValue[$packageService['service_id']] = $packageService['threshold'];	    		
			}
		}
		
		$selectedData = array('id'=>$selectedIds, 'value'=>$selectedValue);
		$this->render('packages-package-services', compact('selectedData', 'package'));		
	}
    
	public function actionTranslations()
    {    	
    	
    	$id =  Yii::app()->getRequest()->getParam('id');
    	$model =  Yii::app()->getRequest()->getParam('model');
    	
    	$classes = array(
            'article' => 'Article',
            'packageservice' => 'PackageService',
    		'package' => 'Package',
    		'category' => 'Category',
    		'ad' => 'Ad',
    		'adsbox' => 'AdsBox'
        );                
        
        $class = $classes[$model];         
        $translations = $class::translationDataProvider($id);
    	//$translations = PackageService::translationDataProvider($id);
    	
    	$this->renderPartial('_translations', array(    	
			'id' => Yii::app()->getRequest()->getParam('id'),
    		'translations' => $translations, 
			'model' => $model
		));		
    }
	public function actionTranslationsAdd()
	{
		$objectId = Yii::app()->getRequest()->getParam('id');
		$model =  Yii::app()->getRequest()->getParam('model');
    	$modelParams = TranslationForm::$modelParams[$model];
        $aliasWarning = null;

		$class = $modelParams['model'];
		$itemData = $class::model()->findByPk($objectId);
		$modelParams['itemTitle'] = $itemData[$modelParams['title_column']];
		$modelParams['itemContent'] = $itemData[$modelParams['content_column']];

		$translation = new TranslationForm;
		$translation->setScenario('insert');
		if (isset($_POST['TranslationForm'])) {
			$translation->attributes = $_POST['TranslationForm'];
			if ($translation->validate()) {
				$sourceMessages = SourceMessage::model()->findAll(
					'object_id=:object_id and (category=\''.$modelParams['title'].'\' or category=\''.$modelParams['content'].'\')',
					array(':object_id'=>$objectId)
				);
				foreach($sourceMessages as $source)
				{
					$message = new Message;
					$message->id = $source->id;
					$message->language = $translation->language;
					if($source->category==$modelParams['title'])
						$message->translation = $translation->title;
					elseif($source->category==$modelParams['content'])
						$message->translation = $translation->content;

                    if ($modelParams['model'] == 'Category'
                            && $source->category==$modelParams['content']) {

                        $transaction = Yii::app()->db->beginTransaction();

                        $aliasWarning = $this->validateAlias($message);
                        $translation->content = $message->translation;

                        if(!Message::model()->exists(
    						'id=:id and language=:language',
    						array(':id'=>$message->id, ':language'=>$message->language)
    					))
                        $message->save('translation');

                        $transaction->commit();
                    } else {
                        $message->save('translation');
                    }

                    if ($aliasWarning) {
                        Yii::app()->user->setFlash('warning', $aliasWarning);
                    }
				}

				$this->redirect($this->createGlobalRouteUrl($modelParams['url']));
    		}
		}

		$this->render('translations-form', array(
			'translation' => $translation,
			'modelParams' => $modelParams));
	}

	public function actionTranslationsUpdate()
	{
		$objectId = Yii::app()->getRequest()->getParam('id');
		$lang = Yii::app()->getRequest()->getParam('lang');
		$model =  Yii::app()->getRequest()->getParam('model');
		$modelParams = TranslationForm::$modelParams[$model];
        $aliasWarning = null;

		$class = $modelParams['model'];
		$itemData = $class::model()->findByPk($objectId);
		$modelParams['itemTitle'] = $itemData[$modelParams['title_column']];
		$modelParams['itemContent'] = $itemData[$modelParams['content_column']];

		$sourceMessages = SourceMessage::model()->findAll(
			'object_id=:object_id and (category=\''.$modelParams['title'].'\' or category=\''.$modelParams['content'].'\')',
			array(':object_id'=>$objectId)
		);

		$translation = new TranslationForm;
		$translation->setScenario('update');

		if (isset($_POST['TranslationForm'])) {
			$translation->attributes = $_POST['TranslationForm'];
			if ($translation->validate()) {
				foreach($sourceMessages as $source)
				{
					$message = Message::model()->find(
						'id=:id and language=:language',
						array(':id'=>$source->id, ':language'=>$lang)
					);
					if($source->category==$modelParams['title'])
						$message->translation = $translation->title;
					elseif($source->category==$modelParams['content'])
						$message->translation = $translation->content;

                    if ($modelParams['model'] == 'Category'
                            && $source->category==$modelParams['content']) {

                        $transaction = Yii::app()->db->beginTransaction();

                        $aliasWarning = $this->validateAlias($message);
                        $translation->content = $message->translation;

                        $message->save('translation');

                        $transaction->commit();
                    } else {
                        $message->save('translation');
                    }
				}
				//$this->redirect($this->createUrl($modelParams['url']));
    		}
		} else {
			foreach($sourceMessages as $source)
			{
				$message = Message::model()->find(
								'id=:id and language=:language',
		    					array(':id'=>$source->id, ':language'=>$lang)
							);
				if($source->category==$modelParams['title'])
					$translation->title = $message->translation;
				elseif($source->category==$modelParams['content'])
					$translation->content = $message->translation;
			}
			$translation->language = $lang;
		}

		$this->render('translations-form', array(
			'translation' => $translation,
			'modelParams' => $modelParams,
            'aliasWarning' => $aliasWarning));
	}
	
	//AJAX - usunięcie tłumaczenia usługi
	public function actionTranslationsRemove()
	{			
		$objectId = Yii::app()->getRequest()->getParam('id');
		$lang = Yii::app()->getRequest()->getParam('lang');
		$model =  Yii::app()->getRequest()->getParam('model');
		$modelParams = TranslationForm::$modelParams[$model];
    	/*$category = array(
            'article' => array('title'=>'article.title', 'content'=>'article.content'),
            'packageservice' => array('title'=>'package.service.title', 'content'=>'package.service.content'),
    		'package' => array('title'=>'package.title', 'content'=>'package.content')
        );*/		
        $sourceMessages = SourceMessage::model()->findAll(
			'object_id=:object_id and (category=\''.$modelParams['title'].'\' or category=\''.$modelParams['content'].'\')',
		    array(':object_id'=>$objectId)
		);		
		foreach($sourceMessages as $source)
		{
			$message = Message::model()->find(
								'id=:id and language=:language',
				array(':id'=>$source->id, ':language'=>$lang)
			);
			$message->delete();			
		}		       			
	}
	
	protected function validateAlias($message)
    {
        // VALIDATE ALIAS
        // cut special chars
        $message->translation = preg_replace('/[^A-Za-z0-9\-]/', '', $message->translation);

        // search for duplicates
        $categories =  Yii::app()->db->createCommand()
            ->select('c.id as id, c.name as text, '
                    . 'm.translation as translation')
            ->from('tbl_category c')
            ->leftJoin('tbl_source_message s', 's.message=c.alias')
            ->leftJoin('tbl_message m', 'm.id=s.id')
            ->where('m.id!=:id '
                    . 'AND (c.name LIKE :name OR m.translation LIKE :name) '
                    . 'AND (s.category=\'category.alias\' OR s.category IS NULL) '
                    . 'AND (m.language=:language OR m.language IS NULL)',
                    array(
                        ':id' => $message->id,
                        ':name' => $message->translation.'%',
                        ':language' => $message->language,
                ))
            ->order('name, translation')
            ->queryAll();

        if (!empty($categories)) {
            $i = 1;
            $alias = $message->translation;
            $available = false;
            do {
                $available = true;

                foreach($categories as $category) {
                    if ($category['translation'] === $alias) {
                        $available = false;

                        $i++;
                        $alias = $message->translation . '-' .$i;

                        break;
                    }
                }
            } while(!$available);

            if ($message->translation !== $alias) {
                $message->translation = $alias;
                return 'Taki alias już istnieje. '
                    . 'Alias został zmieniony na podobny.';
            }
        }

        return null;
    }
	
    public function actionArticles()
    {
    	$this->render('articles');
    }
	public function actionAddArticle()
	{		 
		$article = new Article;		
		if (isset($_POST['Article'])) {			
			$article->attributes = $_POST['Article'];			
			if ($article->validate()) {				 
	    		$article->save();    		    		
    			$this->redirect($this->createUrl('/admin/articles'));
    		}
		}			
		$this->render('article-form', compact('article'));
	}
	public function actionUpdateArticle()
	{		
        $id = Yii::app()->request->getParam('id');		
       	$article = Article::model()->findByPk($id);		
		if (isset($_POST['Article'])) {			
			$article->attributes = $_POST['Article'];    		
    		if ($article->validate()) {    			    			    			
    			$article->save();    			  			
			   	//$this->redirect($this->createUrl('/admin/articles'));
    		}	
		}				
		$this->render('article-form', compact('article'));
	}
	
	//AJAX - usunięcie artykułu
	public function actionRemoveArticle()
	{			
		$id = Yii::app()->request->getParam('id');		
       	$article = Article::model()->findByPk($id);       	       		       		
	    $article->delete();	    			
	}    
	
	public function actionPackagesPurchases()
	{
		$purchase = new PackagePurchase('adminSearch');
		$purchase->unsetAttributes();
		if(isset($_GET['User'])) {
			$purchase->attributes = $_GET['PackagePurchase'];
		}
	
		$params =array(
				'purchase'=>$purchase,
		);
	
		$this->render('purchases', $params);
	}
	
	/*
	 * AJAX - wymuszenie uruchomienia pakietu 
	 */
	public function actionPackagesPaymentConfirm($id)
	{
		try {			
		
			if(!isset($id))
				throw new Exception();
		
			$pendingPurchase = PackagePurchase::model()->with('user')->findByPk($id);
			if(!$pendingPurchase 
			|| $pendingPurchase->status!=Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
				throw new Exception();
			 
			$newPackageExpire = date('Y-m-d', strtotime('+'.$pendingPurchase->period.' months'));
			
			$user = $pendingPurchase->user;
			$oldPackageId = $user->package_id;
			$user->package_id = $pendingPurchase->package_id;
			$user->package_expire =  $newPackageExpire;
			$user->update(false, array('package_id', 'package_expire'));		
			
			$pendingPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'];
			//$newPackageStart = date('Y-m-d');
			$pendingPurchase->date_start =  new CDbExpression('NOW()');
			$pendingPurchase->date_expire = $newPackageExpire;
			$pendingPurchase->update(false, array('status', 'date_start', 'date_expire'));
			
			//wymuszamy ponowne logowanie po zmianie pakietu
			if ($user->package_id != $oldPackageId)
				Yii::app()->db->createCommand()
				->delete('tbl_yii_session','user_id=:user_id',array(':user_id'=>$user->id));
					 
		}
		catch(Exception $e)	{}
		
		$this->redirect($this->createUrl('/admin/packagespurchases'));
	}
	
	/*
	 * AJAX - usunięcie rekordu z historii zmian pakietów
	 * Nie przęłącza pakietu!!!! 
	 */
	public function actionPackagesRemovePurchase($id)
	{		
		if(isset($id)) {			
			$purchase = PackagePurchase::model()->findByPk($id);			
			$purchase->delete();			
		}		
		
		$this->redirect($this->createUrl('/admin/packagespurchases'));
	}
	
	/*
	 * kategorie
	 */
	public function actionCategories($category=NULL)
	{	
		if($category)
			$category = Category::model()->findByPk($category);

		if(!$category)
			$category = Category::model();
		
		$this->render('categories', compact('category'));
	}
	
	public function actionAddCategory($category=NULL)
	{
// 		echo 'actionAddCategory';
// 		$cacheOutput = new COutputCache();
// 		$cacheDB = new CDbCache();
// 		$action = 'sell';
// 		$type = 'company';
// 		echo '<br>'.'category-menu-'.Yii::app()->language.'-'.$action.'-'.$type;

		// to dizala ale czysci calego kesza
// 		Yii::app()->cache->flush();
		
		// tutaj probuje testowac, ale potrzebuje wygenerowac klucz odpowiadajacy temu w bazie, szukac w klasie CDBCache i CCache
// 		Yii::app()->cache->delete_item('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type);
		
// 		Yii::app()->cache->generateUniqueKey('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type);
// 		Yii::app()->cache->deleteValue('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type);
// 		Yii::app()->cache->flushValues('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type);
		
// 		$cacheDB->flush();
// 		$cacheDB->delete('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type);
// 		var_dump(Yii::app()->cache->getCache());
// 		Yii::app()->cache->delete('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type);
// 		Yii::app()->cache->set('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type, 'dupa', array('duration'=>10));
// 		if($cacheOutput->beginCache('category-menu-'.Yii::app()->language.'-'.$action.'-'.$type, array('duration'=>10))) {
// 			echo '<br>keszowanie';
// 			$cacheOutput->endCache();
// 		}
		
		if($category)
			$category = Category::model()->findByPk($category);
		
		if(!$category)
			$category = Category::model();		
		
		$formCategory = new Category;
		if (isset($_POST['Category'])) {
			
			//print_r($_POST['Category']);
			$formCategory->attributes = $_POST['Category'];
			//print_r($formCategory);
			
			
			/*$criteria = new CDbCriteria;
			$criteria->order = 'order_index DESC';
			$row = Category::model()->find($criteria);
			if($row)
				$formCategory->order_index = ++$row->order_index;
			else
				$formCategory->order_index = 1;*/
			
	
			if ($formCategory->validate()) {
				if($category->id)
					$formCategory->appendTo($category);
				else 
					$formCategory->saveNode();
				Yii::app()->cache->flush();
				//$formCategory->save();
				if($category->id)
					$this->redirect($this->createGlobalRouteUrl('/admin/categories', array("category"=>$category->id)));
				else
					$this->redirect($this->createGlobalRouteUrl('/admin/categories'));
			}
		}
		$this->render('category-form', compact('category', 'formCategory'));
	}
	
	public function actionUpdateCategory($id)
	{
		//$id = Yii::app()->request->getParam('id');
		$formCategory = Category::model()->findByPk($id);
		/*print_r($formCategory);
		echo '<br/><br/><br/>';
		$parent = $formCategory->parent()->find();
		print_r($parent);
		echo '<br/><br/><br/>';
		$ancestors = $formCategory->ancestors();
		print_r($ancestors);*/
		
		if (isset($_POST['Category'])) {
			$formCategory->attributes = $_POST['Category'];
			if ($formCategory->validate()) {
				$formCategory->saveNode(false, array('name', 'alias'));
				//$formCategory->save();
				//$formCategory->update(array('name', 'alias'));
				
				Yii::app()->cache->flush();
				
				if($formCategory->level==1)
					$this->redirect($this->createGlobalRouteUrl('/admin/categories'));
				else {
					$parent = $formCategory->parent()->find();
					//print_r($parent);
					$this->redirect($this->createGlobalRouteUrl('/admin/categories', array("category"=>$parent->id)));
				}
			}
		}
		$this->render('category-form', compact('formCategory'));
	}
	
	//AJAX - usunięcie usługi
	public function actionRemoveCategory($id)
	{
		//$id = Yii::app()->request->getParam('id');
		$category = Category::model()->findByPk($id);		
		$category->deleteNode();
		Yii::app()->cache->flush();
		
	}
	
	//Ajax - aktualizacja kolejności z GridView
	public function actionSortCategories()
	{
		if (Yii::app()->request->isAjaxRequest && isset($_POST['items']) && is_array($_POST['items'])) {
			
			
			$items = $_POST['items']; 
			
			$current = Category::model()->findByPk($items[0]);
			
			if($current->level > 1) {
			
				$first = true;
				
				foreach ($items as $item) {					
					
					if($first) {					
						
						$parent = $current->parent()->find();
						
						$current->moveAsFirst($parent);						
											
					} else {
						
						$current = Category::model()->findByPk($item);
						$current->moveAfter($previous);
											 
					}	
					
					$previous = $current;
					
					$first = false;
					
				}
			
			} else {
				
				//roots - wezly z poziomu 1 wymagaja dodatkowego mechanizmu ustawiania kolejnosci 
				
				$i = 1;
				
				foreach ($items as $item) {
					
					Yii::app()->db->createCommand()
					->update('tbl_category', array(
							'order_index'=>$i++
					), 'root='.$item);				
					
				}
			}
			
			Yii::app()->cache->flush();
		}
	}
	
	public function actionAds()
	{
		$this->render('ads');
	}
	
	public function actionAdsAdd()
	{
		$ad = new Ad;
		
		if (isset($_POST['Ad'])) {
				
			$ad->attributes = $_POST['Ad'];
			//print_r($ad->attributes);
			if ($ad->validate()) {
				//print_r($ad->attributes);
				//exit;
				$ad->save();
				$this->redirect($this->createUrl('/admin/ads'));
		
			}
		}
		
		$this->render('adForm', compact('ad'));
	}
	
	public function actionAdsUpdate($id)
	{
		$ad = Ad::model()->findByPk($id);
		
		if (isset($_POST['Ad'])) {
			$ad->attributes = $_POST['Ad'];
			if ($ad->validate()) {
				//print_r($ad->attributes);
				$ad->save();
				$this->redirect($this->createUrl('/admin/ads'));
			}
		}
		
		if(!empty($ad->date_from))
			$ad->date_from = Yii::app()->dateFormatter->format('MM/dd/yyyy', $ad->date_from);
		
		if(!empty($ad->date_to))
			$ad->date_to = Yii::app()->dateFormatter->format('MM/dd/yyyy', $ad->date_to);
		
		$this->render('adForm', compact('ad'));
	}
	
	public function actionAdsRemove($id)
	{
		$ad = Ad::model()->findByPk($id);	
		$ad->delete();
	}
	
	public function actionAdsBoxes()
	{		
		$this->render('adsBoxes');
	}
	
	public function actionAdsAddBox()
	{
		$adbox = new AdsBox;
		
		if (isset($_POST['AdsBox'])) {
			
			$adbox->attributes = $_POST['AdsBox'];		
		
			if ($adbox->validate()) {
				
				$adbox->save();
				$this->redirect($this->createUrl('/admin/adsboxes'));
				
			}
		}
		
		$this->render('adsBoxForm', compact('adbox'));
	}
	
	public function actionAdsUpdateBox($id)
	{		
		$adbox = AdsBox::model()->findByPk($id);
		
		if (isset($_POST['AdsBox'])) {
			//print_r($_POST['AdsBox']);
			$adbox->attributes = $_POST['AdsBox'];
			
			if ($adbox->validate()) {
				
				$adbox->save();
				$this->redirect($this->createUrl('/admin/adsboxes'));
				
			}
		}		
		
		$this->render('adsBoxForm', compact('adbox'));
	}
	
	public function actionAdsRemoveBox($id)
	{
		$adbox = AdsBox::model()->findByPk($id);	
		$adbox->delete();		
	}
	
	public function actionAdsOrders()
	{
		$order = new AdOrder('adminSearch');
		$order->unsetAttributes();
		
		if(isset($_GET['AdOrder'])) {
			$order->attributes = $_GET['AdOrder'];
		}	
		
		$this->render('adsOrders', compact('order'));
	}
	

    public function actionNotify($username)
    {
        $user = User::model()->findByAttributes(array(
            'username' => $username
        ));
        if (!$user) {
            throw new CHttpException(404, Yii::t('user', 'User does not exists.'));
        }
        $this->layout = 'mail';
        $userOrgLanguage = Yii::app()->user->language;
        $appOrgLanguage = Yii::app()->language;
        Yii::app()->user->language = $user->language;
        Yii::app()->language = $user->language;
        if ($user->package_id) {
            $packageName = Yii::t('packages', $user->package->name);
            $title = Yii::t('packages', ':name package for Your profile',
                array(':name' => $packageName));
            $packagesLink = Html::link(Yii::t('packages', $user->package->name), $this->createAbsoluteUrl('/packages/comparison'));
            $emailMsg = Yii::t('packages', 'Currently Your profile is equipped with package')
//                . ' <b>' . Yii::t('packages', $user->package->name) . ', ';
                . ' <b>' . $packagesLink . ', ';
            if ($user->package_expire) {
                $emailMsg .= Yii::t('packages', 'valid until') . ' ' . $user->package_expire . '</b>.';
            } else {
                $emailMsg .= Yii::t('packages', 'valid without time limit') . '</b>.';
            }
        } else {
            $title = Yii::t('packages', 'No package selected for Your profile');
            $emailMsg = Yii::t('packages', 'Currently you don\'t have any active package.');
        }

        
        Yii::app()->mailer->systemMail(
            $user->email,
            $title,
            $this->render('//packages/disablePurchasedPackagesEmail',
                array(
                    'user' => $user,
                    'emailMsg'=>$emailMsg
                ), true),
            null,
            true,
            Yii::app()->params['admin']['email']
        );
        Yii::app()->user->language = $userOrgLanguage;
        Yii::app()->language = $appOrgLanguage;
        Yii::app()->user->setFlash('success', Yii::t('admin', 'Notification sent.'));

        $this->redirect(Yii::app()->user->returnUrl);
    }
	
}