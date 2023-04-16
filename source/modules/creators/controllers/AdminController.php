<?php

class AdminController extends Controller 
{   
    
    
    public function actionUsers()
    {
        $user = new User('adminSearch');
        $user->unsetAttributes();
        if(isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        } else 
        	$user->register_source = User::REGISTER_SOURCE_CREATORS;
        
        $params =array(
            'user'=>$user,
        );       
        
        $this->render('users', $params);
    }
    
    public function actionArticles()
    {
    	$this->render('//admin/articles', array('creators'=>true));
    }
    public function actionAddArticle()
    {
    	$article = new Article;
    	if (isset($_POST['Article'])) {
    		$article->attributes = $_POST['Article'];
    		if ($article->validate()) {
    			$article->creators = 1;
    			$article->save();
    			$this->redirect($this->createUrl('/admin/articles'));
    		}
    	}
    	$this->render('//admin/article-form', compact('article'));
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
    	$this->render('//admin/article-form', compact('article'));
    }
    
    //AJAX - usunięcie artykułu
    public function actionRemoveArticle()
    {
    	$id = Yii::app()->request->getParam('id');
    	$article = Article::model()->findByPk($id);
    	$article->delete();
    }
    
    /*
     * pakiety
     */
    public function actionPackages()
    {      	
    	$this->render('//admin/packages', array('creators'=>true));
    	//$this->render('packages');
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
    		$criteria->condition = 'creators';
    		$criteria->order = 'order_index DESC';
    		$row = Package::model()->find($criteria);
    		if($row)
    			$package->order_index = ++$row->order_index;
    		else
    			$package->order_index = 1;
    
    		if ($package->validate()) {
    			$package->creators = 1;
    			$package->save();
    			$this->redirect($this->createUrl('/admin/packages'));
    		}
    	}
    	$this->render('//admin/packages-package-form', compact('package'));
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
    	$this->render('//admin/packages-package-form', compact('package'));
    }
    
    //AJAX - usunięcie pakietu
    public function actionPackagesRemovePackage()
    {
    	$id = Yii::app()->request->getParam('id');
    	$package = Package::model()->findByPk($id);
    
    	//aktualizacja order index dla wierszy z wyzszym order_index
    	Package::model()->updateCounters(array('order_index'=>-1), 'order_index>:order_index and creators', array(':order_index'=>$package['order_index']));
    	//PackageService::model()->updateAll('order_index=order_index-1', 'order_index>:order_index', array(':order_index'=>$service['order_index']));
    	$package->delete();
    	 
    	//echo "<div class='flash-success'>Ajax - Deleted Successfully</div>";
    	//echo "<div class='flash-error'>Ajax - error message</div>"; //for ajax
    	//Yii::app()->user->setFlash('success', Yii::t('packages', 'Element has been removed.'));
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
    	$this->render('//admin/packages-package-services', compact('selectedData', 'package'));
    }
    
    public function actionPackagesServices()
    {
    	$this->render('//admin/packages-services', array('creators'=>true));
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
    		$criteria->condition = 'creators';
    		$criteria->order = 'order_index DESC';
    		$row = PackageService::model()->find($criteria);
    		if($row)
    			$service->order_index = ++$row->order_index;
    		else
    			$service->order_index = 1;    		
    
    		if ($service->validate()) {
    			$service->creators = 1;
    			$service->save();
    			$this->redirect($this->createUrl('/admin/packagesservices'));
    		}
    	}
    	$this->render('//admin/packages-service-form', compact('service'));
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
    	$this->render('//admin/packages-service-form', compact('service'));
    }
    
    //AJAX - usunięcie usługi
    public function actionPackagesRemoveService()
    {
    	$id = Yii::app()->request->getParam('id');
    	$service = PackageService::model()->findByPk($id);
    
    	//zaktualizowac order index dla wierszy z wyzszym order_index
    	PackageService::model()->updateCounters(array('order_index'=>-1), 'order_index>:order_index and creators', array(':order_index'=>$service['order_index']));
    	$service->delete();
    	 
    	//Yii::app()->user->setFlash('success', Yii::t('packages', 'Element has been removed.'));
    }
    
    public function actionPackagesPeriods()
    {
    	$this->render('//admin/packages-periods', array('creators'=>true));
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
    	$creators = true;
    	$this->render('//admin/packages-period-form', compact('period', 'creators'));
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
    	$this->render('//admin/packages-period-form', compact('period'));
    }
    
//AJAX - usunięcie okresu
	public function actionPackagesRemovePeriod()
	{			
		$id = Yii::app()->request->getParam('id');	
		$periodId = Yii::app()->request->getParam('period');	
		
       	$period = PackagePeriod::model()->findByPk(array('package_id'=>$id, 'period'=>$periodId));       	       		       		
	    $period->delete();	           			
	}
    
	public function actionPackagesPurchases()
	{
		$purchase = new PackagePurchase('adminSearch');
		$purchase->unsetAttributes();
		if(isset($_GET['User'])) {
			$purchase->attributes = $_GET['PackagePurchase'];
		}
		$purchase->creators = true; 
		
		$params =array(
			'purchase'=>$purchase,
			//'creators'=>true
		);
	
		$this->render('//admin/purchases', $params);
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
    	 
    	$this->renderPartial('//admin/_translations', array(
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
    					
    				if(!Message::model()->exists(
    						'id=:id and language=:language',
    						array(':id'=>$message->id, ':language'=>$message->language)
    				))
    					$message->save();
    			}
    			$this->redirect($this->createUrl($modelParams['url']));
    		}
    	}
    		
    	$this->render('//admin/translations-form', array(
    			'translation' => $translation,
    			'modelParams' => $modelParams));
    }
    
    public function actionTranslationsUpdate()
    {
    	$objectId = Yii::app()->getRequest()->getParam('id');
    	$lang = Yii::app()->getRequest()->getParam('lang');
    	$model =  Yii::app()->getRequest()->getParam('model');
    	$modelParams = TranslationForm::$modelParams[$model];
    
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
    				$message->save('translation');
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
    
    	$this->render('//admin/translations-form', array(
    			'translation' => $translation,
    			'modelParams' => $modelParams));
    }
    
    //AJAX - usunięcie tłumaczenia usługi
    public function actionTranslationsRemove()
    {
    	$objectId = Yii::app()->getRequest()->getParam('id');
    	$lang = Yii::app()->getRequest()->getParam('lang');
    	$model =  Yii::app()->getRequest()->getParam('model');
    	$category = array(
    		'article' => array('title'=>'article.title', 'content'=>'article.content'),
    		'packageservice' => array('title'=>'package.service.title', 'content'=>'package.service.content'),
    		'package' => array('title'=>'package.title', 'content'=>'package.content')
    	);
    	$sourceMessages = SourceMessage::model()->findAll(
    		'object_id=:object_id and (category=\''.$category[$model]['title'].'\' or category=\''.$category[$model]['content'].'\')',
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
	

	public function actionCalendar()
	{
		$this->render('calendar');
	}

	
	public function actionSendMail($to=null, $all=false) {

		$email = new EmailForm('creators-send');

		if ($to) $email->recipientAddresses = $to;
		if ($all) $email->recipientAll = true;

		$allEmailsWhere = 'active=1 AND ban=0 AND verified=1
			AND creators_tou_accepted=1 AND register_source='.User::REGISTER_SOURCE_CREATORS;
		
		if(isset($_POST['EmailForm']))
		{
			$email->attributes = $_POST['EmailForm'];

			if($email->validate())	{

				$user = User::model()->findByPk(Yii::app()->user->id);

				$this->layout = 'mail';
				if ($email->recipientAll) {
					$addresses = User::getAllEmails($allEmailsWhere);
					var_dump($addresses);exit();
				} else {
					$addresses = explode(', ', $email->recipientAddresses);
				}
				foreach($addresses as $address) {
					$trimmed = trim($address);
					//$this->render('emailFormMail', compact('email', 'user'));
					//echo 'to:'.$address.'; ';
					Yii::app()->mailer->ClearAttachments();
					Yii::app()->mailer->systemMail(
						$trimmed,
						/*Yii::app()->params['branding']['title'].' - '.*/$email->subject,
						$this->render('emailFormMail', compact('email', 'user'), true),
						array(
							'email' => $user->email,
							'name' => $user->forename.' '.$user->surname
						)
					);
					
					Yii::app()->user->setFlash('success', Yii::t('contact', 'Message sent.'));
				}

				$this->redirect('/admin/users');
				//return;
			}
		}

		$allCount = User::getAllEmails($allEmailsWhere, array(), true);

		$this->render('emailForm', compact('to', 'all', 'email', 'allCount'));
	}
    
}