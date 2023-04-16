<?php


class NewItemsEmailCommand extends CConsoleCommand {
    
    public function run($args) {
        set_time_limit(0);
//        echo '<br>newItemsEmailCommand START';
        $testMode = false;
        $spoolCacheKey = 'new-items-email-';
        
        if($testMode)
            $date = new DateTime('2019-11-20');
        else
            $date = new DateTime('now');

        $date->modify('-1 day');
        $params = ['date'=>$date->format('Y-m-d')];
        $condition = 'active = 1 AND date >= :date';
//        $items = Item::model()->with('company', 'product', 'service', 'category', 'package', 'product.company', 'service.company', 'product.company.item', 'service.company.item')->findAll($condition, $params);
        $items = Item::model()->with('company', 'product', 'service', 'category', 'package', ['product.company' => 'product_company'], ['product_company.item' => 'product_company_item'], ['service.company' => 'service_company'], ['service_company.item' => 'service_company_item'])->findAll($condition, $params);
        if(!$items)
        {    
            return;
        }    
        
//        $itemsGrouped = ['companies'=>[],'products'=>[],'services'=>[]];
//        $search = new Search('insert');
        $search = new Search();
         
        
        
//        $controller = new CController('context');
        $controller = new Controller('context');
        $controller->layout = 'mailWithResign';

        $condition = 'active = 1 AND verified=1 AND ban = 0 AND send_emails = 1';
        $users = User::model()->findAll($condition);
        
        
        $counts = [];
        foreach($items as $item)
        {
            if($item->cache_type != 'c')
                continue;
            $productCount = Product::model()->with('item')->count(
                'company_id=:company_id and active=1',
                array(':company_id'=>$item->id)
            );
            $serviceCount = Service::model()->with('item')->count(
                'company_id=:company_id and active=1',
                array(':company_id'=>$item->id)
            );
            $counts[$item->id] = ['products' => $productCount, 'services' => $serviceCount];
        }
        
        foreach($users as $user)
        {       
            
//            if($user->email != 'seointercode@gmail.com' && $user->email != 'przybyla.bernard@gmail.com')
            if($testMode && $user->email != 'seointercode@gmail.com')
                continue;

            if(!$user->sign_out_verification_code)
            {    
                $user->generateSignOutVerificationCode();
                $user->save(false);
            }
            Yii::app()->language = $user->language; 
            
            $itemsGrouped = ['companies'=>[],'products'=>[],'services'=>[]];
            foreach($items as $item)
            {   
    //            var_dump($item->category);
                $search->type = $item->typeName();
                $categories = [];
                if($item->sell) {
                    $search->action = 'sell';                    
                    $categories[$item->category->createUrl($search)] = $item->category->getNameLocal();
                }
                if($item->buy) {
                    $search->action = 'buy';
                    $categories[$item->category->createUrl($search)] = $item->category->getNameLocal();
                }
                
                if($item->cache_type=='c')
                    $itemsGrouped['companies'][] = ['item'=>$item, 'categories'=>$categories, 'counts' => $counts[$item->id]];
                elseif($item->cache_type=='p')
                    $itemsGrouped['products'][] = ['item'=>$item, 'categories'=>$categories];
                else
                    $itemsGrouped['services'][] = ['item'=>$item, 'categories'=>$categories];
            }
            
            $subject = Yii::t('item', 'New on Firmbook', [], null, $user->language);
            $body = $controller->render('/other/newItemsEmail', compact('user', 'itemsGrouped'), true, true);                                      
            Spooler::create($spoolCacheKey.$user->id, $user->email, $subject, $body);
        }
//        echo '<br>newItemsEmailCommand END';
    }
    
    public function run_20200128($args) {
        set_time_limit(0);
//        echo '<br>newItemsEmailCommand START';
        $testMode = true;
        $spoolCacheKey = 'new-items-email-';
        
        if($testMode)
            $date = new DateTime('2019-11-20');
        else
            $date = new DateTime('now');

        $date->modify('-1 day');
        $params = ['date'=>$date->format('Y-m-d')];
        $condition = 'active = 1 AND date >= :date';
//        $items = Item::model()->with('company', 'product', 'service', 'category', 'package', 'product.company', 'service.company', 'product.company.item', 'service.company.item')->findAll($condition, $params);
        $items = Item::model()->with('company', 'product', 'service', 'category', 'package', ['product.company' => 'product_company'], ['product_company.item' => 'product_company_item'], ['service.company' => 'service_company'], ['service_company.item' => 'service_company_item'])->findAll($condition, $params);
        if(!$items)
        {    
            return;
        }    
        
        $itemsGrouped = ['companies'=>[],'products'=>[],'services'=>[]];
//        $search = new Search('insert');
        $search = new Search();
         
        foreach($items as $item)
        {   
//            var_dump($item->category);
            $search->type = $item->typeName();
            $categories = [];
            if($item->sell) {
                $search->action = 'sell';
                echo '<br>kategoria 1: '.$item->category->createUrl($search);
                $categories[$item->category->createUrl($search)] = $item->category->getNameLocal();
            }
            if($item->buy) {
                $search->action = 'buy';
                echo '<br>kategoria 2: '.$item->category->createUrl($search);
                $categories[$item->category->createUrl($search)] = $item->category->getNameLocal();
            }
            
            if($item->cache_type=='c')
                $itemsGrouped['companies'][] = ['item'=>$item, 'categories'=>$categories];
            elseif($item->cache_type=='p')
                $itemsGrouped['products'][] = ['item'=>$item, 'categories'=>$categories];
            else
                $itemsGrouped['services'][] = ['item'=>$item, 'categories'=>$categories];
        }
        
//        $controller = new CController('context');
        $controller = new Controller('context');
        $controller->layout = 'mailWithResign';

        $condition = 'active = 1 AND verified=1 AND ban = 0 AND send_emails = 1';
        $users = User::model()->findAll($condition);
        
        foreach($users as $user)
        {       
            
//            if($user->email != 'seointercode@gmail.com' && $user->email != 'przybyla.bernard@gmail.com')
            if($testMode && $user->email != 'seointercode@gmail.com')
                continue;

            if(!$user->sign_out_verification_code)
            {    
                $user->generateSignOutVerificationCode();
                $user->save(false);
            }
            Yii::app()->language = $user->language; 
            $subject = Yii::t('item', 'New on Firmbook', [], null, $user->language);
            $body = $controller->render('/other/newItemsEmail', compact('user', 'itemsGrouped'), true, true);                                      
            Spooler::create($spoolCacheKey.$user->id, $user->email, $subject, $body);
        }
//        echo '<br>newItemsEmailCommand END';
    }
    
	public function run_20191129($args) {

//		Yii::app()->controller->ajaxMode();
//
//		Yii::app()->cron->run();
        echo "\nOK\n";
		
        $com = Company::model()->findByPk(43283);
        echo '<br>url 7: '.$com->item_id;
//        $this->layout = 'mail';      
//        echo '<br>email: '.Yii::app()->user->id;
//        $user = User::model()->findByPk(Yii::app()->user->id);
        $user = User::model()->findByPk(1);
        
        $controller = new CController('context');
        $controller->layout = 'mail';
        $body = $controller->renderInternal(__DIR__.'/../views/other/newItemsEmail.php', compact('user'), true);
//        echo '<br>'.$body;
        
        $cache = Yii::app()->spool_cache;
//        var_dump(Yii::app()->cache);
//        Yii::app()->cache->set('mojeimie', 'Blazej');
        $cache->set('new-items-email-'.$user->id, $body);
        echo '<br>body: '.$cache->get('new-items-email-'.$user->id);
        
//        Yii::app()->mailer->systemMail(
//            Yii::app()->params['admin']['email'],
//            'Test mail',
//            'Content'
//        );
//        var_dump($user);
//        Yii::app()->mailer->systemMail(
//            'seointercode@gmail.com',    
//            Yii::t('elists', 'Added to elist', [], null, $user->language),
//            $this->render('/other/newItemsEmail', compact('user'), true, true)        
////            $emailData
//        );
        
        
        
//        return 1;
	}
    
    
    public function run_org($args) {

		Yii::app()->controller->ajaxMode();

		Yii::app()->cron->run();
		echo 'ok';

	}
}

?>
