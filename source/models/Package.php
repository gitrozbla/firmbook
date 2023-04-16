<?php

class Package extends ActiveRecord
{
    protected static $_allAsArray = null;
    
    /* ic start */
    protected static $_packageMaxResources = 1000;
    /**
     * Domyślna pakiet - id w db.
     * @var int
     */
    public static $_packageDefault = 1;
	public static $_creatorsPackageDefault = 5;
    
    /**
     * Domyślny okres obowiązywania pakietu - id w db.
     * @var int
     */    
    public static $_packageDefaultPeriod = 3;
    
    /**
     * Domyślna pakiet - id w db.
     * @var int
     */
    public static $_packagePurchaseStatus = array(
    	'PURCHASE_STATUS_PENDING'=>0,
    	'PURCHASE_STATUS_CURRENT'=>1,
    	'PURCHASE_STATUS_CANCELED'=>2,
    	'PURCHASE_STATUS_EXPIRED'=>3,
    	'PURCHASE_STATUS_PAID'=>4,
    	);
    /* ic end */
    
    /**
     * Tworzy instancję.
     * @param string $className Klasa instancji.
     * @return object Utworzona instancja zadanej klasy.
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Nazwa tabeli.
     * @return string
     */
    public function tableName()
    {
        return '{{package}}';
    }
    
	/**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
            'user' => array(self::HAS_MANY, 'User', 'package_id'),
        	/*'services' => array(self::HAS_MANY, 'PackageServiceMN', 'package_id',
                'with'=>'service',
                'together'=>false),*/
        	'services_mn'=>array(self::HAS_MANY,'PackageServiceMN','package_id'),
        	//'services' => array(self::HAS_MANY, 'PackageService', array('service_id'=>'id'), 'through'=> array('Package' => 'roles')),
        	'services' => array(self::HAS_MANY, 'PackageService', array('service_id'=>'id'), 'through'=>'services_mn'),
        	//'services' => array(self::HAS_MANY, 'PackageService', 'tbl_package_service(package_id, service_id'),
        	'purchase' => array(self::HAS_ONE, 'PackagePurchase', 'package_id'),

            'periods' => array(self::HAS_MANY, 'PackagePeriod', 'package_id')
        );
    }
    
	public function rules()
    {
        return array(        	
        	array('order_index', 'numerical', 'integerOnly'=>true),
        	array('name', 'required'),        	
        	array('test_period', 'numerical', 'integerOnly'=>true),
        	array('description, css_name, badge_css, item_css, stats_color, color, creators', 'safe'),  
                array('active', 'boolean'),
        );
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(            
            'name' => Yii::t('packages', 'Name'),
        	'order_index' => '#',
            //'order_index' => Yii::t('packages', 'Order'),
        	'description' => Yii::t('packages', 'Description'),        	        	
        	'test_period' => Yii::t('packages', 'Test period'),
            'active' => Yii::t('packages', 'Aktywny'),
        );
    }
    
    public function findAllAsArray($creators=null)
    {
        if (!self::$_allAsArray) {       	
        	
            /*$packages = Yii::app()->db->createCommand()
                    ->select('id, name, css_name, stats_color, description')
                    ->from('tbl_package')
                    ->where('creators=0')
                    ->order('id ASC')
                    ->queryAll();*/
            
            $command = new CDbCommand(Yii::app()->db);
            $command->select = 'id, name, css_name, stats_color, description';
            $command->from = 'tbl_package';
            if(isset($creators)) {
            	if($creators)
            		$command->where = 'creators=1';
            	else
            		$command->where = 'creators=0';
            }	
            $command->order = 'id ASC';
            $packages = $command->queryAll();
            
            $packagesNumeric = array();
            foreach($packages as $package) {
                $packagesNumeric[$package['id']] = $package;
            }
                
            self::$_allAsArray = $packagesNumeric;
        }
        return self::$_allAsArray;
    }
    
    public static function packagesToSelect($creators=null)
    {
    	if(!isset($creators)) {    		
            $packages = self::model()->findAll(array('condition'=>'active','order'=>'order_index desc'));
    	} elseif($creators)
            $packages = self::model()->findAll(array('condition'=>'creators and active','order'=>'order_index desc'));
    	else 
            $packages = self::model()->findAll(array('condition'=>'!creators and active','order'=>'order_index desc'));
    	
    	$packagesToSelect = array();
    	foreach($packages as $package)
    		if($package['id']!=self::$_packageDefault && $package['id'] != Yii::app()->params['packages']['defaultPackageCreators'])
        		$packagesToSelect[$package['id']] = Yii::t('packages', $package['name'])
    			.( empty($creators) && $package['creators'] ? ' - Creators' : '');
        return $packagesToSelect;    	
    }
    
	public static function statusToLabel($status, $paid=0)
    {    	
    	if($status == self::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING']) {
    		if($paid)
    			return Yii::t('packages', 'Unconfirmed');
    		else
    			return Yii::t('packages', 'Unpaid');
    			//return Yii::t('packages', 'Pending');	
    	} elseif($status == self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'])
    		return Yii::t('packages', 'Current');
    	elseif($status == self::$_packagePurchaseStatus['PURCHASE_STATUS_CANCELED'])
    		return Yii::t('packages', 'Canceled');
    	elseif($status == self::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'])
    		return Yii::t('packages', 'Expired');
    	elseif($status == self::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])
    		return Yii::t('packages', 'Paid');
    }
    
    /*
     * uruchamia pakiet po odebraniu potwierdzenia z DOTPAY w akcji actionTransactionConfirm
     */
	public static function enablePurchasedPackage($purchaseId)
    {    	
    	
    	if (Yii::app()->params['websiteMode'] == 'creators') {
    		$creatorsMode = true;
    	} else {
    		$creatorsMode = false;
    	}
    	/*$transaction = Yii::app()->db->beginTransaction();
		try {*/    	
	    	/*
	    	 * szczegóły oczekującego zamówienia
	    	 */
	    	$purchaseData = PackagePurchase::model()->findByPk($purchaseId);
	    	if($purchaseData['status'] != self::$_packagePurchaseStatus['PURCHASE_STATUS_PENDING'])
	    		throw new Exception();
	    		
	        $rowData = array(
	        	'date_modified' => new CDbExpression('NOW()'),
	        	'paid' => 1
	        );
	        /*
	         * obecnie aktywny pakiet
	         */
	        $userData = User::model()->findByPk($purchaseData['user_id']);
	        if(!$userData)
	        	throw new Exception();
	        	
	        //$currentPackage = User::model()->getCurrentPackage(Yii::app()->user->id, Yii::app()->user->package_id);
	        //$currentPackage = self::getCurrentPackage($purchaseData['user_id']);
	        
	        /*
	         * Jeśli opłacony pakiet i aktualny to ten sam pakiet, to przedłużenie aktualnego pakietu
	         * w takm przypadku należy ustawić zamówienie jako opłacone, ale z datą uruchomienia taką
	         * jak data wygaśnięcia aktualnego zamówienia i datą wygaśnięcia wyliczoną od tak przesuniętej daty uruchomienia
	         * 
	         * TODO: Można dodać opcję wymuszenia natychmiastowego uruchomienia pakietu, Klient nasz Pan, 
	         * ale tylko w przypadku, gdy wykupiony pakiet jest wyższy od aktualnego. 
	         * Dodatkowo można ograniczyć wykupywanie pakietów "na zapas do przodu/na przyszłość" do jednego zamówienia
	         * 
	         * W przeciwnym wypadku ustaw nowo wykupiony pakiet jako aktualny z datą uruchomienia od teraz 
	         * do daty wyliczonej na podstawie okresu
	         */	        
				
	        /*
	         * Szukamy ostatniego zamówienia ze statusem aktualny lub opłacony (czyli opłaconego,
	         * ale oczekującego na wygaśnięcie aktualnego/aktywnego pakietu).
	         * Znalezione zamówienie posłuży do wyliczenia daty uruchomienia
	         * (identycznej z datą wygaśnięcia znalezionego zamówienia)
	         * i daty wygaśniecia wyliczonej od tak otrzymanej daty uruchomienia
	         */
	        //lista operacji zmiany pakietu dla użytkownika
	        $history = User::model()->getPurchasedPackages($purchaseData['user_id'], $creatorsMode);
	        //$history = self::getPurchasedPackages($purchaseData['user_id']);
	        $lastPaid = NULL;
	        $h = 0;
	        while(!$lastPaid && ($h < count($history)))
	        {
	        	if($history[$h]['status'] == self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'] || $history[$h]['status'] == self::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])
	        	$lastPaid = $history[$h];
	        	++$h;
	        }

	        if((!$creatorsMode && ($userData['package_id'] == Package::$_packageDefault))
			|| ($creatorsMode && ($userData['creators_package_id'] == Yii::app()->params['packages']['defaultPackageCreators']))
	        || $purchaseData['force_activation'])
	        {
	        	$rowData['status'] = self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'];
	        	$rowData['date_start'] = new CDbExpression('NOW()');
	        	$currentDate = new DateTime();
	        	//$date = new DateTime("2006-12-12");	        		
	        	//$currentDate->modify("+1 month");	        	
	        } else {	        		
	        	$rowData['status'] = self::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'];
	        	$rowData['date_start'] = $lastPaid['date_expire'];
	        	$currentDate = new DateTime($lastPaid['date_expire']);	        		        		
	        }
	        $currentDate->modify("+".$purchaseData['period']." month");
	        $rowData['date_expire'] = $currentDate->format("Y-m-d  H:i");
	        $rowData['date_paid'] = new CDbExpression('NOW()');	        		    
			
			
			//Ustaw aktualny pakiet jako wygasły			 
			if($purchaseData['force_activation'])
			{					
	    		Yii::app()->db->createCommand()
	            	->update('tbl_package_purchase', 
	                array('status'=>self::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED']),	                 
	                array('and', 'user_id='.$purchaseData['user_id'], 'status='.self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']));
			}            
	    	
	    	
	    	//Odpowiednio zmodyfikuj inofrmacje dla opłaconego zamówienia	    	 
	    	Yii::app()->db->createCommand()
	                    ->update('tbl_package_purchase', $rowData, 'id=:id', array(':id'=>$purchaseId));
			
	        /*
	         * Aktualizacja pakietu i daty wygasniecia w rekordzie użytkownika
	         * wykonaj jeśli pakiet aktualizowanego zamówienia jest różny od ustawionego w rekordzie użytkownika
	         * a status modyfikowanego zamówienia ustawiany jest na aktualny.
	         */    
	    	if(!$creatorsMode) {
		        if($purchaseData['package_id'] != $userData->package_id 
		        	&& $rowData['status'] == self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'])
		        {	
		        	User::model()->updateByPk($userData['id'], array(
					    'package_id' => $purchaseData['package_id'],
					    'package_expire' => $rowData['date_expire']				    
					));            
					// update items package cache
	            	Yii::app()->db->createCommand()
	                    ->update('tbl_item', array(
	                        'cache_package_id'=>$purchaseData['package_id']
	                    ), 'user_id=:user_id', array('user_id'=>$userData['id']));			
		        }  
	    	} else {
	    		if($purchaseData['package_id'] != $userData->creators_package_id
	    				&& $rowData['status'] == self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'])
	    		{
	    			User::model()->updateByPk($userData['id'], array(
	    					'creators_package_id' => $purchaseData['package_id'],
	    					'creators_package_expire' => $rowData['date_expire']
	    			));	    			
	    		}
	    	}        
            // echo ' Completed8 ';               
			/*$transaction->commit();			
    	}
		catch(Exception $e)
		{			
		    $transaction->rollback();
		}  */
		/*
             * TODO Przemyśleć wyzwalanie tej funkcji przez CRON-a, aby oddzielić rządania zewnętrzne
             * od operacji wykonywanych na bazie, np. poprzez zapisanie informacji zwracanych z systemu płatności, 
             * a dla reszty ustawiając flagę pending_activation w rekordzie zamówienia 	
             */
    }
    
    public static function badge($packageName, $packageCssName, $showFree=false)
    {
//	if (!$showFree && $packageName=='FREE') return;
        if (!$showFree && $packageName=='STARTER') return;

	return '<span class="package-badge-'.$packageCssName.'">'.Yii::t('packages', $packageName).'</span>';
    }
    
    public static function packagesDataProvider($creators=false)
    {
        $criteria = new CDbCriteria;		
        $criteria->condition = $creators ? 'creators' : '!creators';
        $criteria->order = 'order_index';

        return new CActiveDataProvider('Package', array(
            'criteria'=> $criteria,
            /*array(				
                    //'select' => 't.*, p.name as name',
                    //'join' => 'INNER JOIN tbl_package p on p.id=package_id',
                    //'condition'=>'user_id='.$userId,
                'order'=>'order_index',
                    //'with'=>array('package'),				
            ),*/
            'sort'=>array(
                'attributes'=>array(
                    'name'=>array(
                        'asc'=>'name',
                        'desc'=>'name DESC'
                    ),					
                    '*',	
                )				
            ),
            'pagination' => false,            
        ));
        
    }
    
    //użkownik może testować pakiety tylko jeśli pierwszy raz wybiera płatny pakiet, czyli w historii nie znajdują się wpisy
	//ze statusem aktualny, opłacony, wygasły
    public static function canTestPackage($userId, $creators=false)
    {
    	return !PackagePurchase::model()->exists(
            'user_id=:user_id and (status=:status_c or status=:status_p or status=:status_e)'
            .($creators ? ' and creators' : ' and !creators'), 
            array(
                ':status_c'=>self::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'],
                ':status_p'=>self::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'],
                ':status_e'=>self::$_packagePurchaseStatus['PURCHASE_STATUS_EXPIRED'],
                ':user_id'=>$userId, 
                //':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']
            )
        );
    } 
    
    public static function translationDataProvider($object_id)
    {
    	$translations = SourceMessage::model()->with('translations')->findAll(
            array(
                'condition'=>'object_id=:object_id and (category="package.title" or category="package.content")',
                'params'=>array(':object_id'=>$object_id),
            )
    	);
    	$articles = array();
    	foreach(Yii::app()->params->languages as $lang)
    	{
            $article = array();
            foreach($translations as $part) {
                foreach($part->translations as $message) {
                    if(!array_key_exists($message->language, $articles)) {
                        $articles[$message->language] = array('object_id'=>$object_id,'language'=>$message->language);
                    }
                    if($part->category=='package.title')
                        $articles[$message->language]['title'] = $message->translation;
                    elseif($part->category=='package.content')
                        $articles[$message->language]['content'] = $message->translation;
                }
            }
    	}
    	$articles = array_values($articles);
    	$dataProvider = new CArrayDataProvider($articles, array(
            'keyField'=>'language',
            'pagination'=>array(
                'pageSize'=>10,
            ),
    	));
    	return $dataProvider;
    }
    
    public function afterSave()
    {
    	if ($this->isNewRecord) {
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'package.title';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = $this->name;
    		//dodanie źródła tłumaczeń tytułu artykułu
    		$sourceMessage->save();
    		unset($sourceMessage);
    		$sourceMessage = new SourceMessage;
    		$sourceMessage->category = 'package.content';
    		$sourceMessage->object_id = $this->id;
    		$sourceMessage->message = '{'.$this->name.'}';
    		//$sourceMessage->message = '{description}';
    		//$sourceMessage->message = '{'.$article->alias.'}';
    		//dodanie źródła tłumaczeń treści artykułu
    		$sourceMessage->save();
    	} else {
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'package.title', 'object_id'=>$this->id));
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'package.title';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = $this->name;
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = $this->name;
    			$sourceMessage->save('message');
    		}
    		unset($sourceMessage);
    		$sourceMessage = SourceMessage::model()->findByAttributes(array('category'=>'package.content', 'object_id'=>$this->id));
    		//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    		if(!$sourceMessage)
    		{
    			//mozna usunac, jesli wszystkie rekordy beda dodane na nowo
    			$sourceMessage = new SourceMessage;
    			$sourceMessage->category = 'package.content';
    			$sourceMessage->object_id = $this->id;
    			$sourceMessage->message = '{'.$this->name.'}';
    			$sourceMessage->save();
    		} else {
    			//koniecznie pozostawic
    			$sourceMessage->message = '{'.$this->name.'}';
    			$sourceMessage->save('message');
    		}
    	}
    	return parent::afterSave();
    }
    
    public function beforeDelete()
    {
    	//remove translation
    	$sourceMessages = SourceMessage::model()->findAll(
    			'object_id=:object_id and (category=\'package.title\' or category=\'package.content\')',
    			array(':object_id'=>$this->id)
    	);
    	foreach($sourceMessages as $source)
    	{
    		$messages = Message::model()->findAll('id=:id',	array(':id'=>$source->id));
    		foreach($messages as $message)
    			$message->delete();
    		$source->delete();
    	}
    	return parent::beforeDelete();
    }
}
