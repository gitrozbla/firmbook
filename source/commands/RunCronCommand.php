<?php


class RunCronCommand extends CConsoleCommand {
	public function run($args) {

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
