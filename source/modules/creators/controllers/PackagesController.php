<?php

class PackagesController extends Controller 
{	
	public $defaultAction = 'show';
	
	/**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {    	
    	return 'show, comparison, paymentconfirm, transactionconfirm';
    	//, notifypackageexpireafter, disablepurchasedpackages
    	//return 'show, sort, paymentconfirm';        
    }
    
    /*
     * Wyświetla porównawczą tabelkę pakietów
     */
    public function actionComparison()
    {
    	
    	//wszystkie usługi dostępne w pakietach
    	$services = PackageService::model()->findAll(array('condition'=>'creators and active','order'=>'order_index'));
    	//pakiety wraz z przypisanymi usługami
    	$packagesData = Package::model()->findAll(array('condition'=>'creators and active','order'=>'order_index desc'));
    	//pakiety wraz usługami do nich przypisanymi
    	$packages = array();
    	for($i=0;$i<count($packagesData);++$i)
    	{
    		$packages[$i] = $packagesData[$i]->attributes;
    		//uslugi w pakiecie;
    		$packageServices = PackageServiceMN::model()->findAll(
    				'package_id=:package_id',
    				array(':package_id'=>$packages[$i]['id'])
    		);
    
    		foreach($packageServices as $packageService)
    			$packages[$i]['services'][$packageService['service_id']] = $packageService;
    	}    
    	
    	$this->render('//packages/comparison', compact('services', 'packages'));
    	//$this->render('show', compact('services', 'packages'));
    }
    
	/*
	 * Wyświetla prezentację pakietów 
	 */	
    public function actionShow()
    {      	    		
    	$this->render('show');   	    	
    }
    
    /*
     * formularz zmiany pakietu
     */
    /*
     * użytkownik zawsze może zmienić pakiet, o ile nie posiada oczekującego na uruchomienie opłaconego zamówienia
     * użytkownik może skorzystać z okresu testowego tylko raz do momentu uruchomienia pierwszego płatnego pakietu
     * 
     */
    public function actionChange()
    {
    	//oczekujące na uruchomienie opłacone zamówienie
    	if(PackagePurchase::model()->exists('user_id=:user_id and status=:status and creators', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])))
    		$this->redirect($this->createUrl('/packages/history'));
    		
    	$purchase = new PackagePurchase;    	
    	    		
    	$canTest = Package::canTestPackage(Yii::app()->user->id, true);	
    	
    	if (isset($_POST['PackagePurchase'])) {    		
    		$purchase->attributes = $_POST['PackagePurchase'];
    		//sprawdzamy czy istnieje okres testowy dla pakietu
    		$package = Package::model()->findByPk($purchase->package_id);    		
    		
    		if ($purchase->validate()) {
    			$purchase->creators = 1;
    			$purchase->user_id = Yii::app()->user->id;    			
    			$periodData = PackagePeriod::model()->findByPk(
    				array('package_id'=>$purchase->package_id, 'period'=>$purchase->period)
    			);    			    			
				$purchase->price = $periodData['price'];
				//jesli uzytkownik wybral opcje testu, a dla pakietu zdefiniowano okres testowy i uzytkownik moze z niego skorzystac 
				if(isset($_POST['package_test']) && $package['test_period'] && $canTest) {
					$purchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'];
					$purchase->date_start = new CDbExpression('NOW()');
	        		$currentDate = new DateTime();
	        		$currentDate->modify("+".$package['test_period']." days");
	        		$purchase->date_expire = $currentDate->format("Y-m-d  H:i"); 
				} else
					$purchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'];    			
    			
				//lista oczekujących zamówień, które stają się nieaktualne i należy je usunąć	
				$pendingPurchases = PackagePurchase::model()->findAll(
    				'user_id=:user_id and status=:status and creators',
    				array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
    			);
    			
    			$transaction = Yii::app()->db->beginTransaction();
    			try {
			    	foreach ($pendingPurchases as $pendingPurchase) {
			            $pendingPurchase->delete();
			        }
			        //Ustaw aktualny pakiet jako wygasły jeśli uruchamiany jest pakiet			 
					if($purchase->status == Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'])
					{					
			    		Yii::app()->db->createCommand()
			            	->update('tbl_package_purchase', 
			                array('status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED']),	                 
			                array('and', 'user_id='.$purchase->user_id, 'status='.Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'],
			                	'creators'	
			                ));
					}  	    			
					
	    			$purchase->save();
			    				    	           	                    
	    			if($purchase->status == Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']) {
	    				//aktualizacja rekordu uzytkownika
	    				User::model()->updateByPk($purchase->user_id, array(
						    'creators_package_id' => $purchase->package_id,
						    'creators_package_expire' => $purchase->date_expire				    
						));				        
	    			}
			    		
			    	//throw new Exception();
			    	$transaction->commit();
			    	//jesli wybrany pakiety posiada status aktualny ustaw pakiet w sesji
			    	if($purchase->status == Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'])
			    	{
			    		Yii::app()->user->setState('creators_package_id', $purchase->package_id);    
		                Yii::app()->user->setFlash('success', Yii::t('packages', 'The package has been running on a test period.'));
			    	} else
			    		Yii::app()->user->setFlash('success', Yii::t('packages', 'The package will be activated after paying for the service.'));
			    	
			    	$this->redirect($this->createUrl('/packages/history'));					
		    	}
				catch(Exception $e)
				{			
				    $transaction->rollback();
				}    				
    		}
    	}    	
    	
    	// articles
        $articleRight1 = Article::model()->find(
        		'alias="package-change"'
                //'alias="organize-your-business-online"'
        );
        $articleRight2 = Article::model()->find(
        		'alias="package-test"'
                //'alias="create-free-account"'
        );
        $packages = Package::model()->findAll(array('order'=>'order_index desc'));        
    	$this->render('//packages/change', compact('purchase', 'packages', 'canTest', 'articleRight1', 'articleRight2'));
    }
    	
    
    /*
     * historia zmian pakietu z wykazem płatności
     */
	
    public function actionHistory()
    {    	
    	$this->render('history');    	
    }
	
	/*public function beforeAction($action)
	{
	    Yii::app()->request->enableCsrfValidation = false;
	    return parent::beforeAction($action);
	}*/    
	
    
    /*
     * powrót z płatności dotpay, nie oznacza dokonania płatności
     */
    public function actionPaymentConfirm()
    {    	
    	/*echo 'actionPaymentConfirm';
    	print_r($_POST);*/
    	try {
    		if (empty($_POST) || $_POST['status'] != 'OK') 
    			throw new Exception();
    		
    		//if (!empty($_POST) && $_POST['status'] == 'OK') {
    		$purchaseId = Yii::app()->request->getParam('id');
	    	//echo '<br/>id zamowienia: '.$purchaseId;
	    	
	    	if(!isset($purchaseId))
	    		throw new Exception();

	    	$pendingPurchase = PackagePurchase::model()->findByPk($purchaseId);	
	    	if(!$pendingPurchase)
    			throw new Exception();
    			
    		$rowData = array(
    			//'date_paid' => new CDbExpression('NOW()'),
	        	'date_modified' => new CDbExpression('NOW()'),
    			//oznacza pozytywnie zakończony proces płatności, ale nie potwierdzenie płatności, zapisanej za pomocą statusu PAID=4 
	        	'paid' => 1
	        );
	        // ustawienie flagi paid dla prawdopodobnie opłaconego zamówienia
	        Yii::app()->db->createCommand()
	                    ->update('tbl_package_purchase', $rowData, 'id=:id', array(':id'=>$purchaseId));
    		Yii::app()->user->setFlash('success', Yii::t('packages', 'The package will be activated after confirmation of payment from DOTPAY.pl'));
    		
    		$this->redirect($this->createUrl('/packages/history'));    		
    	
    	}
		catch(Exception $e)
		{			
		    $this->redirect($this->createUrl('/packages/history'));
		}  
    	
    	//return;    	    	
    }
    	
    
    /*
     * akcja odbierająca komunikaty z dotpay, czy płatność zostąła przyjęta czy odrzucona
     */
	public function actionTransactionConfirm()
    {
    	//echo 'actionTransactionConfirm';
    	if (empty($_POST)) 
    		exit;

    	if (Yii::app()->params['websiteMode'] == 'creators') {
    		$creatorsMode = true;
    	} else {
    		$creatorsMode = false;
    	}
    	
    	$transaction = Yii::app()->db->beginTransaction();	
    	try {
    		$shopId = $_POST['id'];
    		$purchaseId = $_POST['control'];    		
    		$operation_number = $_POST['operation_number'];
    		$operation_status = $_POST['operation_status'];
    		$operation_amount = $_POST['operation_amount'];
    		$operation_currency = $_POST['operation_currency'];
    		$operation_original_amount = $_POST['operation_original_amount'];    		
    		$operation_original_currency = $_POST['operation_original_currency'];
    		$operation_datetime = $_POST['operation_datetime'];
    		$operation_email = $_POST['email'];
    		$operation_signature = $_POST['signature']; 
    		//walidacja PIN   		
    		$pin = Yii::app()->params['packages']['dotpayPin'];    		
    		$sign =
				$pin.
				$_POST['id'].
				$_POST['operation_number'].
				$_POST['operation_type'].
				$_POST['operation_status'].
				$_POST['operation_amount'].
				$_POST['operation_currency'].
				$_POST['operation_original_amount'].
				$_POST['operation_original_currency'].
				$_POST['operation_datetime'].
				NULL.
				//$_POST['operation_related_number'].
				$_POST['control'].
				$_POST['description'].
				$_POST['email'].
				$_POST['p_info'].
				$_POST['p_email'].
				$_POST['channel'];
			$signature = hash('sha256', $sign); 			
    		//pin validation
       		if($operation_signature !== $signature)
       			throw new Exception();
    		
       		//sprawdzenie czy istnieje zamówienie i czy jego status to oczekujące, dla odebrnaych danych, 
       		$pendingPurchase = PackagePurchase::model()->findByPk($purchaseId);       			
	    	if(!$pendingPurchase || $pendingPurchase['status']!=Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
	    		throw new Exception();
	    		    		
	    	/*
	    	 * Parametry które nas interesują:
	    	 * $operation_status, $operation_number, $operation_datetime
	    	 */
	    	//if(in_array($operation_status, Yii::app()->params['packages']['dotpayStatus']))
	    	if(array_key_exists($operation_status, Yii::app()->params['packages']['dotpayStatus'])
	    		&& $_POST['operation_amount'] == $_POST['operation_original_amount'] 
	    		&& $_POST['operation_currency'] == $_POST['operation_original_currency'])
	    	{		    		
		    	$pendingPurchase->t_id = $operation_number;	
		    	$pendingPurchase->t_status = Yii::app()->params['packages']['dotpayStatus'][$operation_status];
		    	$pendingPurchase->t_date = $operation_datetime;
		    	$pendingPurchase->save();	
	    	}
	    		
	    	if($operation_status == Yii::app()->params['packages']['dotpayStatusCompleted'])
	    	{		    		
	    		$purchaseUserData = User::model()->findByPk($pendingPurchase['user_id']);	    		
	    		Package::enablePurchasedPackage($pendingPurchase['id']);	    			    		
	    		if ((!$creatorsMode && ($purchaseUserData['package_id'] != $pendingPurchase['package_id']))
	    			|| ($creatorsMode && ($purchaseUserData['creators_package_id'] != $pendingPurchase['package_id'])) )
		    		Yii::app()->db->createCommand()
						->delete('tbl_yii_session','user_id=:user_id',array(':user_id'=>$pendingPurchase['user_id']));
						
				$this->layout = 'mail';
                Yii::app()->mailer->systemMail(
                    $purchaseUserData->email,
                    Yii::t('packages', 'Package change'),
                    $this->render('transactionConfirmEmail', 
                            compact($purchaseUserData), true)
                );		
	    	} 
	    	
	    	//throw new Exception();
			$transaction->commit();
	    	echo 'OK';
	    	
    	}
		catch(Exception $e)
		{
			$transaction->rollback();					    
		}
		exit;
    }
    
    /*
     * test pod przycisk w packageDetails, ale nie dziala poprawnie
     */
    public function renderButtons()
    {    	
    	$this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',			                	 
			                    'label' => Yii::t('packages', 'Pay'),
			                    'type' => 'primary',			                    
			                	'url' => $this->createUrl('packages/paymentconfirm/package_id/'),			                	
			                )
			            );
    }
    
    //AJAX - anulowanie oczekującego, nie opłaconego zamówienia
    public function actionCancel_Order()
    {
    	if (!Yii::app()->request->isAjaxRequest)    		
    		exit;
    		
    	try {
	    	$purchaseId = Yii::app()->request->getParam('id');	    	
	    	if(!isset($purchaseId))
	    		throw new Exception();

	    	$pendingPurchase = PackagePurchase::model()->findByPk($purchaseId);	
	    	if(!$pendingPurchase)
	    		throw new Exception();
	    			
	    	//sprawdzenie czy akcja wywolana przez wlasciciela zamowienia
	    	if($pendingPurchase['user_id'] != Yii::app()->user->id)
	    		throw new Exception();

	    	//można anulować tylko oczekujące zamówienie	
	    	if($pendingPurchase['status']!=Package::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
	    		throw new Exception();
	    		
	    	//usuniecie zamowienia
	    	$pendingPurchase->delete();	    	
	    	
	    	//test
	    	/*$pendingPurchase->price = $pendingPurchase->price+1;
	    	$pendingPurchase->save();*/	    		
    	}
    	catch(Exception $e)
		{		
			exit;		    
		}		
		//$this->redirect($this->createUrl('/packages/history'));
    }
    
    public function actionDisablePurchasedPackages()
    {
    	$viewContent = '';
    	$viewContent .= '<br/>Operacaja CRONa - wygaszanie i uruchamianie pakietów !!!<br/>';    	
    	/*
    	 * lista użytkowników z wygasającym obecnym pakietem
    	 * poprzez pobranie listy uzytkownikow z data wygasniecia mniejsza od aktualnej daty;
    	 * mozna zrobic rowniez poprzez pobranie aktywnych pakietów/zamówień 
    	 * z data wygasniecia mniejsza od aktualnej daty;  
    	 */    	
        $model = User::model();        
        $model->setDbCriteria(new CDbCriteria(array(
              'select' => array('id', 'package_id', 'email', 'username'))));
        $currentDate = date('Y-m-d');
        $users = $model->findAll(
                    'package_expire is not null '
                        . 'and package_expire != \'0000-00-00\''
                        . 'and package_expire < :current_date',
                        array(':current_date' => $currentDate)
        );
        
        foreach($users as $user) {       
        	$viewContent .= '<br/>- Użytkownik - id:'.$user['id'].', nazwa:'.$user['username'].', email:'.$user['email'];
			//$viewContent .= '<br/>--- wygasa w dniu: '.$row['package_expire'].', id zamówienia: '.$row['id'];
	        /*$transaction = Yii::app()->db->beginTransaction();
			try {*/					
				/*
				 * aktualizacja wpisów w tabeli zamówień
				 */
				//wyłączenie obecnego pakietu
				/*$currentPurchase = PackagePurchase::model()->find(
					array(
						'condition'=>'user_id=:user_id and package_id=:package_id and status=:status',
						'params'=>array(':user_id'=>$user->id, ':package_id'=>$user->package_id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']),
						//'order'=>'date_added desc',			
					)		
				);						
				if($currentPurchase) {					 
					$currentPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'];
					$currentPurchase->update(false, array('status'));					   
				}*/	
				
				//włączenie oczekującego, opłaconego pakietu
				$paidPurchase = PackagePurchase::model()->find(
					array(
						'condition'=>'user_id=:user_id and  status=:status',
						'params'=>array(':user_id'=>$user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']),
						//'order'=>'date_added desc',			
					)		
				);
				if($paidPurchase) {
					$newPackageStart = date('Y-m-d');
					$newPackageExpire = date('Y-m-d', strtotime('+'.$paidPurchase->period.' months'));					 
					$paidPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'];
					$paidPurchase->date_start = $newPackageStart;
					$paidPurchase->date_expire = $newPackageExpire;
					$paidPurchase->update(false, array('status', 'date_start', 'date_expire'));
					
					$user->package_id = $paidPurchase->package_id;
		            $user->package_expire = $newPackageExpire;	
		            $user->expire_days_msg = 0;
		            
		            $emailMsg = 'Your package has been changed.';				   
				} else {					
					$user->package_id = Package::$_packageDefault;
		            $user->package_expire = null;
		            $user->expire_days_msg = 0;
		            $emailMsg = 'Your package has expired.';	
				}			
				
		        /*// update items package cache
            	Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'cache_package_id'=>$user->package_id
                    ), 'user_id=:user_id', array('user_id'=>$user->id));*/
		        
                // wymuś zalogowanie po zmianie pakietu    
                Yii::app()->db->createCommand()
					->delete('tbl_yii_session','user_id=:user_id',array(':user_id'=>$user->id));    
                    
                /*
				 * aktualizacja rekordu uzytkownika
				 */				            
		        $user->update(false, array('package_id', 'package_expire', 'expire_days_msg'));

		        /*
				 * wysłanie wiadomości email o wygasnieciu pakietu za okres dni
				 */				
				$this->layout = 'mail';
                Yii::app()->mailer->systemMail(
                    $user->email,
                    Yii::t('packages', 'Package change'),
                    $this->render('disablePurchasedPackagesEmail', 
                            array('emailMsg'=>$emailMsg), true)
                );
                    
	            //echo '<br/>Operacja zakończona sukcesem<br/>';
	        	//print_r($users);
	    		//throw new Exception();
		    	//$transaction->commit();
			
    		/*}
			catch(Exception $e)
			{			
			    $transaction->rollback();
			}*/  	
    	
        }
        $viewContent .= '<br/><br/><br/>Operacja zakończona sukcesem !!!<br/>';	
    	$this->layout = 'main';
    	$this->render('notifyPackageExpireAfter', compact('viewContent'));
        //exit;
    }
        
    
    public function actionNotifyPackageExpireAfter()
    {
    	$viewContent = '';
    	$viewContent .= '<br/>Operacaja CRONa - powiadomienia o wygaśnięciu pakietu !!!<br/>';
    	 
    	$expireAfter = Yii::app()->params['packages']['expireAfter'];    	
    
    	for($i=0;$i<count($expireAfter);++$i)
    	{
    		$viewContent .= '<br/><br/>- Użytkownicy przed upłynięciem '.$expireAfter[$i].' dni:';    		   		 
    		 
    		$dateFrom = date('Y-m-d');    		
    		$dateTo = date('Y-m-d', strtotime('+'.($expireAfter[$i]).' days'));
    		
    		$viewContent .= '<br/>'.$dateFrom.' < data <= '.$dateTo;    		

    		//lista użytkowników z wygasającym obecnym pakietem w danym przedziale czasowym
    		$data = User::model()->findAll(
    			'package_expire > :datefrom'
    			.' and package_expire <= :dateto'
    			.' and (expire_days_msg > '.$expireAfter[$i]. ' or expire_days_msg = 0)',
    			array(
    				':dateto' => $dateTo,
    				':datefrom' => $dateFrom,
    			)
    		);   		
    		 
    		foreach($data as $row)
    		{
    			$viewContent .= '<br/>-- Użytkownik - id:'.$row->id.', nazwa:'.$row->username.', email:'.$row->email;
    			$viewContent .= '<br/>--- wygasa w dniu: '.$row->package_expire.', id zamówienia: '.$row->id;
    			//sprawdzenie czy istnieje opłacone zamówienie dla użytkownika, jeśli tak to nie ma sensu go informowac
    			if(PackagePurchase::model()->find(
    					array(
    							'condition'=>'user_id=:user_id and status=:status',
    							'params'=>array(':user_id'=>$row->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']),
    							//'order'=>'date_added desc',
    					))) 
    			{
    				$viewContent .= '<br/>--- Użytkownik z oczekującym i opłaconym przełączeniem pakietu !!!';
    				continue;
    			}
    			//$viewContent .= '<br/>';
    
    			// wysłanie wiadomości email o wygasnieciu pakietu za okres dni    			 
    			$this->layout = 'mail';
    			Yii::app()->mailer->systemMail(
    				$row->email,
    				Yii::t('packages', 'Package will expire on'),
    				$this->render('notifyPackageExpireAfterEmail',
    					array(
							'user'=>$row,
    						'package_expire'=>$row->package_expire,
    						//'username'=>$row->username
    					), 
    					true
    				)
    			);
    			
    			//zapisanie informacji o wyslaniu wiadomosci dla danego okresu w rekordzie użytkownika    			 
    			$row->expire_days_msg = $expireAfter[$i];
    			$row->update(false, array('expire_days_msg'));
    			
    		}
    	}
    	$viewContent .= '<br/><br/><br/>Operacja zakończona sukcesem !!!<br/>';
    	$this->layout = 'main';
    	$this->render('notifyPackageExpireAfter', compact('viewContent'));
    }
    	
    
    //wersja pod crona
    public function actionNotifyPackageExpireAfter_cron()
    {
    	echo '<br/>Operacaja CRONa - zawiadomienie o wygaśnięciu pakietu w tle<br/>';
    	
    	$expireAfter = Yii::app()->params['packages']['expireAfter'];
	    //$expireAfter = array('3','7','14','30');
	
		for($i=0;$i<count($expireAfter);++$i)
		{
			echo '<br/><br/>Użytkownicy dla okresu: '.$expireAfter[$i];
			//$package->notifyPackageExpireAfter($smarty, $expireAfter[$i]);	
	    	/*
	    	 * lista użytkowników z wygasającym obecnym pakietem w danym przedziale czasowym
	    	 * 
	    	 */
	    	$dateTo = date('Y-m-d', strtotime('+'.($expireAfter[$i]-1).' days'));
	    	$dateFrom = date('Y-m-d', strtotime('+'.($expireAfter[$i]).' days'));
	    	$whereTO = 'u.package_expire > :dateto';
	    	$whereFROM = 'u.package_expire <= :datefrom';
			//$whereTO = 'u.package_expire > '.date('Y-m-d', strtotime('+'.($expireAfter[$i]-1).' days'));
			//$whereFROM = 'u.package_expire <= '.date('Y-m-d', strtotime('+'.($expireAfter[$i]).' days'));
	        //$currentDate = date('Y-m-d', strtotime('+1 years'));
	        
	        //$newPackageExpire = date('Y-m-d', strtotime('+'.$paidPurchase->period.' months'));
	        $sql = 'select pp.*,u.username,u.email,u.package_expire from tbl_user u '.
	        	'inner join tbl_package_purchase pp on pp.user_id = u.id '.
    			' where pp.status = :status and pp.expire_days_msg <> '.$expireAfter[$i].' and '.$whereFROM.' and '.$whereTO;;	
	        //echo '<br/>SQL: '.$sql;		
			$command=Yii::app()->db->createCommand($sql);
			$command->bindValue(':dateto', $dateTo);
			$command->bindValue(':datefrom', $dateFrom);
			$command->bindValue(':status', Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']);
			//$command->bindValue(':user_id', $dataRow['user_id']);
			$data = $command->queryAll();
	        //print_r($data);
			foreach($data as $row)
			{
				echo '<br/>Użytkownik: '.$row['username'].' ('.$row['user_id'].')'.' ('.$row['id'].')';
				echo '<br/>wygasa dnia: '.$row['package_expire'].' '.$row['email'];
				//sprawdzenie czy istnieje opłacone zamówienie dla użytkownika, jeśli tak to nie ma sensu go informowac
				if(PackagePurchase::model()->find(
					array(
						'condition'=>'user_id=:user_id and status=:status',
						'params'=>array(':user_id'=>$row['user_id'], ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']),
						//'order'=>'date_added desc',			
				))) {
					echo '<br/>Użytkownik z oczekującym przełączeniem pakietu:';
					continue;
				}	
				
				/*
				 * wysłanie wiadomości email o wygasnieciu pakietu za okres dni
				 */
				
				$this->layout = 'mail';
                Yii::app()->mailer->systemMail(
                    $row['email'],
                    Yii::t('packages', 'Package will expire on'),
                    $this->render('notifyPackageExpireAfterEmail', 
                            array('user'=>$row,'package_expire'=>$row['package_expire']), true)
                );	
				/*
				 * zapisanie informacji o wyslaniu wiadomosci dla danego okresu w rekordzie zamowienia
				 */
				$currentPurchase = PackagePurchase::model()->findByPk($row['id']);				
				$currentPurchase->expire_days_msg = $expireAfter[$i];					
				$currentPurchase->update(false, array('expire_days_msg'));
			}
	        /*
	         	//packages expiration
	        $model = User::model();
	        // get id only (performance)
	        // package_id needed for detection in User::beforeSave().
	        $model->setDbCriteria(new CDbCriteria(array(
	              'select' => array('id', 'package_id'))));
	        $currentDate = date('Y-m-d');
	        $users = $model->findAll(
	                    'package_expire is not null '
	                        . 'and package_expire != \'0000-00-00\''
	                        . 'and package_expire < :current_date',
	                        array(':current_date' => $currentDate)
	        );*/
    	
    	}
    	echo '<br/>Operacja zakończona sukcesem<br/>';	
    }	
    
	
}