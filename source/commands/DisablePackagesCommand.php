<?php

class DisablePackagesCommand extends CConsoleCommand {
    
    public function run($args) {
        set_time_limit(0);
//        echo '<br>DisablePackagesCommand START';
        
        $model = User::model();        
        $model->setDbCriteria(new CDbCriteria(array(
              'select' => array('id', 'package_id', 'email', 'username', 'language'))));
        $currentDate = date('Y-m-d');
        $users = $model->findAll(
                    'package_expire is not null '
                        . 'and package_expire != \'0000-00-00\''
                        . 'and package_expire < :current_date',
                        array(':current_date' => $currentDate)
        );
        $controller = new Controller('context');
        $controller->layout = 'mail';
        $spoolCacheKey = 'package-change-email-';
        
        foreach($users as $user) { 
//            Yii::app()->language = $user->language; 
//            $userOrgLanguage = Yii::app()->user->language;
//            $appOrgLanguage = Yii::app()->language;
//            Yii::app()->user->language = $user->language;
            Yii::app()->language = $user->language;
//            if($user->email != 'seointercode@gmail.com' && $user->email != 'przybyla.bernard@gmail.com')            
//            if($user->email != 'seointercode@gmail.com')
//                continue;
				
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

//                $emailMsg = 'Your package has been changed.';	
//                $emailMsg = Yii::t('packages', 'Your package has been changed.', [], null, $user->language);
                $emailMsg = Yii::t('packages', 'Your package has been changed.');
            } else {					
                $packagesLink = Html::link($user->packageName(), $controller->createAbsoluteUrl('/packages/comparison'));
                $user->package_id = Package::$_packageDefault;
                $user->package_expire = null;
                $user->expire_days_msg = 0;
//                $emailMsg = 'Your package {package} has expired.';	
//                $emailMsg = Yii::t('packages', 'Your package {package} has expired.', ['{package}'=>$user->packageName()], null, $user->language);
                
                $emailMsg = Yii::t('packages', 'Your package {package} has expired.', ['{package}'=>$packagesLink]);
            }			

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

            
            $subject = Yii::t('packages', 'Package change', [], null, $user->language);
            $body = $controller->render('/packages/disablePurchasedPackagesEmail', compact('user', 'emailMsg'), true, true);                                      
            Spooler::create($spoolCacheKey.$user->id, $user->email, $subject, $body, Yii::app()->params['admin']['email']);
        }
//        echo '<br>DisablePackagesCommand END';
    }
}
