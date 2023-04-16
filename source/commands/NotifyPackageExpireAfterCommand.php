<?php

class NotifyPackageExpireAfterCommand extends CConsoleCommand {
    
    public function run($args) {
        set_time_limit(0);
//        echo '<br>NotifyPackageExpireAfterCommand START';
         
    	$expireAfter = Yii::app()->params['packages']['expireAfter'];    	
    
        $controller = new Controller('context');
        $controller->layout = 'mail';
        
    	for($i=0;$i<count($expireAfter);++$i)
    	{    		 
    		$dateFrom = date('Y-m-d');    		
    		$dateTo = date('Y-m-d', strtotime('+'.($expireAfter[$i]).' days'));
    	
    		//lista użytkowników z wygasającym obecnym pakietem w danym przedziale czasowym
    		$data = User::model()->with('package')->findAll(
    			'package_expire > :datefrom'
    			.' and package_expire <= :dateto'
    			.' and (expire_days_msg > '.$expireAfter[$i]. ' or expire_days_msg = 0)',
    			array(
    				':dateto' => $dateTo,
    				':datefrom' => $dateFrom,
    			)
    		);   		
    		
            $spoolCacheKey = 'package-expire-after-email-';
    		foreach($data as $row)
    		{
//                if($row->email != 'seointercode@gmail.com' && $row->email != 'przybyla.bernard@gmail.com')                            
//                if($row->email != 'seointercode@gmail.com')
//                    continue;

    			//sprawdzenie czy istnieje opłacone zamówienie dla użytkownika, jeśli tak to nie ma sensu go informowac
    			if(PackagePurchase::model()->find(
    					array(
    							'condition'=>'user_id=:user_id and status=:status',
    							'params'=>array(':user_id'=>$row->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']),
    							//'order'=>'date_added desc',
    					))) 
    			{
    				continue;
    			}
   
    			// wysłanie wiadomości email o wygasnieciu pakietu za okres dni    			 

//                Yii::app()->user->language = $row->language;
                Yii::app()->language = $row->language;
                $emailMsg = '';
                if ($row->package_id) {
                    $packageName = Yii::t('packages', $row->package->name);
//                    $title = Yii::t('packages', ':name package for Your profile',
//                        array(':name' => $packageName));
                    $packagesLink = Html::link(Yii::t('packages', $row->package->name), $controller->createAbsoluteUrl('/packages/comparison'));
                    $emailMsg = Yii::t('packages', 'Currently Your profile is equipped with package')
                        . ' <b>' . $packagesLink . ', ';
                    if ($row->package_expire) {
                        $emailMsg .= Yii::t('packages', 'valid until') . ' ' . $row->package_expire . '</b>.';
                    } else {
                        $emailMsg .= Yii::t('packages', 'valid without time limit') . '</b>.';
                    }
                }
//                else {
//                    $title = Yii::t('packages', 'No package selected for Your profile');
//                    $emailMsg = Yii::t('packages', 'Currently you don\'t have any active package.');
//                }
                $subject = Yii::t('packages', 'Package will expire on', [], null, $row->language);
//                $body = $controller->render('/packages/notifyPackageExpireAfterEmail', array(
                $body = $controller->render('/packages/disablePurchasedPackagesEmail', array(    
                    'user'=>$row,
                    'package_expire'=>$row->package_expire,
                    'emailMsg' => $emailMsg
                    //'username'=>$row->username
                ), true, true);                                      
                Spooler::create($spoolCacheKey.$row->id, $row->email, $subject, $body);
    			
    			//zapisanie informacji o wyslaniu wiadomosci dla danego okresu w rekordzie użytkownika    			 
    			$row->expire_days_msg = $expireAfter[$i];
    			$row->update(false, array('expire_days_msg'));
    			
    		}
    	}
//        echo '<br>NotifyPackageExpireAfterCommand END';
    }
}
