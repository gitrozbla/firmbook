<?php

class CronService extends CComponent
{
    public function init() {}

    public function run()
    {
		// url test
        /*$tempController = new PackagesController('packages');
        $url = $tempController->createAbsoluteUrl('packages/history');
        file_put_contents(dirname(__FILE__) . '/test.txt', $url);*/
		
    	if (Yii::app()->params['websiteMode'] == 'creators') {    		
    		   		
    		$this->runCronCreators();
    		    		
    	} else {   		
    		    		
    		$this->runCronFirmbook();
    		
    	}
        //$this->runCron();
    }
    
    public function runCronFirmbook()
    {
		$testController = new PackagesController('test');
        $testController->layout = 'mail';

        // test mail
        /*Yii::app()->mailer->systemMail(
            'wojciech.alaszewski@gmail.com',
            'Firmbook - Cron test',
            $testController->render('testEmail', array(), true)
        );*/
    	
        $db = Yii::app()->db;
        /*$lastCronRun = $db->createCommand()
                ->select('last_cron_run')
                ->from('tbl_settings')
                ->limit(1)
                ->queryScalar();*/
        
        $currentDate = date('Y-m-d');
        //if ($lastCronRun != $currentDate) {
		if (true) {
            // once a day

        	Yii::import('application.controllers.PackagesController', true);
        	$tempController = new PackagesController('packages');
        	$tempController->layout = 'mail';        	
        	
            //packages expiration
            $model = User::model();
            // get id only (performance)
            // package_id needed for detection in User::beforeSave().
            $model->setDbCriteria(new CDbCriteria(array(
                'select' => array('id', 'username', 'email', 'package_id'))));
            $users = $model->findAll(
                    'package_expire is not null '
                        . 'and package_expire != \'0000-00-00\''
                        . 'and package_expire < :current_date',
                        array(':current_date' => $currentDate)
            );
            foreach($users as $user) {
            	            	
            	//włączenie oczekującego, opłaconego pakietu
            	$paidPurchase = PackagePurchase::model()->find(
            		array(
            			'condition'=>'user_id=:user_id and status=:status and !creators',
            			'params'=>array(':user_id'=>$user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])
            		)
            	);
            	
            	if($paidPurchase) {
            		
            		$newPackageStart = date('Y-m-d');
            		$newPackageExpire = date('Y-m-d', strtotime('+'.$paidPurchase->period.' months'));
            		            			
            		$user->package_id = $paidPurchase->package_id;
            		$user->package_expire = $newPackageExpire;
            		$user->expire_days_msg = 0;           		
            		$user->update(false, array('package_id', 'package_expire', 'expire_days_msg'));
            		
            		$paidPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'];
            		$paidPurchase->date_start = $newPackageStart;
            		$paidPurchase->date_expire = $newPackageExpire;
            		$paidPurchase->update(false, array('status', 'date_start', 'date_expire'));
            		
            		$emailMsg = 'Your package has been changed.';
            		
            	} else {
            		
            		$user->package_id = null; // this will force updating cache
            		// These fields should be updated automatically, but if
                    // the package was somehow default, it will skip updating fields
                    // and system will send email over and over.
            		$user->package_expire = null;
            		$user->expire_days_msg = 0;

            		
            		// @see User::beforeSave();
            		$user->update(false, array('package_id'));
            		
            		$emailMsg = 'Your package has expired.';
            		
            	}
            	
            	//$user->package_id = null;
            	// this mode will automagically update items package cache
            	// @see User::beforeSave();
            	//$user->update(false, array('package_id', 'package_expire', 'expire_days_msg'));
            	
            	/*
            	 * wysłanie wiadomości email o wygasnieciu pakietu za okres dni
            	 */
            	//$this->layout = 'mail';
            	//$tempController->layout = 'mail';
            	Yii::app()->mailer->systemMail(
            			$user->email,
            			Yii::t('packages', 'Package change'),
            			$tempController->render('disablePurchasedPackagesEmail',
            				array(
                                'user' => $user,
                                'emailMsg'=>$emailMsg
                            ), true),
                        null,
                        true,
                        Yii::app()->params['admin']['email']
            	);           	
            	
            	//$user->update(false, array('package_id'));
            	
                /*$user->package_id = null;
                $user->package_expire = null;
                // this mode will automagically update items package cache
                // @see User::beforeSave();
                $user->update(false, array('package_id', 'package_expire'));*/
            }            
                       
            /*
             * powiadomienia o nadchodzącym wygaśnięciu pakietu
             */
            
            /*Yii::import('application.controllers.PackagesController', true);
            $tempController = new PackagesController('packages');*/            
            
            $expireAfter = Yii::app()->params['packages']['expireAfter'];
            for($i=0;$i<count($expireAfter);++$i)
            {
            	$dateFrom = date('Y-m-d');
            	$dateTo = date('Y-m-d', strtotime('+'.($expireAfter[$i]).' days'));
                        
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
	            	//sprawdzenie czy istnieje opłacone zamówienie dla użytkownika, jeśli tak to nie ma sensu go informowac
	            	if(PackagePurchase::model()->find(
	            			array(
	            					'condition'=>'user_id=:user_id and status=:status and !creators',
	            					'params'=>array(':user_id'=>$row->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']),            
	            			)))
	            	{            		
	            		continue;
	            	}            
	            	// wysłanie wiadomości email o wygasnieciu pakietu za okres dni
	            	
	            	//$tempController->layout = 'mail';
	            	Yii::app()->mailer->systemMail(
	            			$row->email,
	            			Yii::t('packages', 'Package will expire on'),
	            			$tempController->render('notifyPackageExpireAfterEmail',
	            					array(
										'user'=>$row,
										'package_expire'=>$row->package_expire
	            					),
	            					true
	            			)
	            	);	            	
	            	           	 
	            	//zapisanie informacji o wyslaniu wiadomosci dla danego okresu w rekordzie użytkownika
	            	$row->expire_days_msg = $expireAfter[$i];
	            	$row->update(false, array('expire_days_msg'));
	            	 
	            }
            }
                       
            // done. update last cron run date
            $db->createCommand()
            ->update('tbl_settings', array('last_cron_run'=>$currentDate));
            
        }
    }
    
    public function runCronCreators()
    {       	
    	
    	$db = Yii::app()->db;
    	/*$lastCronRun = $db->createCommand()
		    	->select('creators_last_cron_run')
		    	->from('tbl_settings')
		    	->limit(1)
		    	->queryScalar();*/
    
    	$currentDate = date('Y-m-d');
    	//if ($lastCronRun != $currentDate) {
		if (true) {
    		// once a day
       		
    		Yii::import('application.modules.creators.CreatorsModule', true);
    		Yii::import('application.modules.creators.controllers.PackagesController', true);
    		   
    		$tempController = new PackagesController('../modules/creators/views/packages');
    		$tempController->layout = 'mail';
    		//$tempController->layout = Yii::getPathOfAlias('application.modules.creators.views').'/layouts/mail';
    		//$tempController->layout = '/../modules/creators/views/layouts/mail';
    		
    		
    		//creators packages expiration
    		$model = User::model();
    		// get id only (performance)
    		// package_id needed for detection in User::beforeSave().
    		$model->setDbCriteria(new CDbCriteria(array(
    				'select' => array('id', 'username', 'email', 'creators_package_id'))));
    		$users = $model->findAll(
    				'creators_package_expire is not null '
    				. 'and creators_package_expire != \'0000-00-00\''
    				. 'and creators_package_expire < :current_date',
    				array(':current_date' => $currentDate)
    		);
    		foreach($users as $user) {
    			
    			//włączenie oczekującego, opłaconego pakietu
    			$paidPurchase = PackagePurchase::model()->find(
    					array(
    							'condition'=>'user_id=:user_id and status=:status and creators',
    							'params'=>array(':user_id'=>$user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])
    					)
    			);
    
    			if($paidPurchase) {
    				
    				$newPackageStart = date('Y-m-d');
    				$newPackageExpire = date('Y-m-d', strtotime('+'.$paidPurchase->period.' months'));
    				 
    				$user->creators_package_id = $paidPurchase->package_id;
    				$user->creators_package_expire = $newPackageExpire;
    				$user->creators_expire_days_msg = 0;
    				$user->update(false, array('creators_package_id', 'creators_package_expire', 'creators_expire_days_msg'));
    
    				$paidPurchase->status = Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT'];
    				$paidPurchase->date_start = $newPackageStart;
    				$paidPurchase->date_expire = $newPackageExpire;
    				$paidPurchase->update(false, array('status', 'date_start', 'date_expire'));
    
    				$emailMsg = 'Your package has been changed.';
    
    			} else {
    				
    				$user->creators_package_id = null; // this will force updating cache
    				// These fields should be updated automatically, but if
                    // the package was somehow default, it will skip updating fields
                    // and system will send email over and over.
            		$user->package_expire = null;
            		$user->expire_days_msg = 0;

    				 
    				$user->update(false, array('creators_package_id'));
    				 
    				$emailMsg = 'Your package has expired.';
    
    			}    	
   
    			/*
    			 * wysłanie wiadomości email o wygasnieciu pakietu za okres dni
    			*/    			
    			Yii::app()->mailer->systemMail(
    					$user->email,
    					Yii::t('packages', 'Package change'),
    					$tempController->render('disablePurchasedPackagesEmail',
    						array(
                                'user' => $user,
                                'emailMsg'=>$emailMsg
                            ), true),
                        null,
                        true,
                        Yii::app()->params['admin']['email']
    			);    
    			
    		}
    
    
    		/*
    		 * powiadomienia o nadchodzącym wygaśnięciu pakietu
    		 */
       
    		$expireAfter = Yii::app()->params['packages']['expireAfter'];
    		    
    		//creators
    		for($i=0;$i<count($expireAfter);++$i)
    		{
    			$dateFrom = date('Y-m-d');
    			$dateTo = date('Y-m-d', strtotime('+'.($expireAfter[$i]).' days'));
    
    			$data = User::model()->findAll(
    					'creators_package_expire > :datefrom'
    					.' and creators_package_expire <= :dateto'
    					.' and (creators_expire_days_msg > '.$expireAfter[$i]. ' or creators_expire_days_msg = 0)',
    					array(
    							':dateto' => $dateTo,
    							':datefrom' => $dateFrom,
    					)
    			);
    			foreach($data as $row)
    			{
    				//sprawdzenie czy istnieje opłacone zamówienie dla użytkownika, jeśli tak to nie ma sensu go informowac
    				if(PackagePurchase::model()->find(
    						array(
    								'condition'=>'user_id=:user_id and status=:status and creators',
    								'params'=>array(':user_id'=>$row->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']),
    						)))
    				{
    					continue;
    				}
    				
    				// wysłanie wiadomości email o wygasnieciu pakietu za okres dni    				
    				Yii::app()->mailer->systemMail(
    						$row->email,
    						Yii::t('packages', 'Package will expire on'),    						
    						//$tempController->render('application.modules.creators.views.packages.notifyPackageExpireAfterEmail',
    						$tempController->render('notifyPackageExpireAfterEmail',    						    						
    							array(
									'user'=>$row,
    								'package_expire'=>$row->creators_package_expire
    							),
    							true
    						)
    				);
    				    				
    				//zapisanie informacji o wyslaniu wiadomosci dla danego okresu w rekordzie użytkownika
    				$row->creators_expire_days_msg = $expireAfter[$i];
    				$row->update(false, array('creators_expire_days_msg'));
    				 
    			}
    		}
    
    		// done. update last cron run date
    		$db->createCommand()
    		->update('tbl_settings', array('creators_last_cron_run'=>$currentDate));
    
    	}
    }
    
}
