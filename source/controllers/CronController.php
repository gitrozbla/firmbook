<?php
/**
 * Kontroler z akcjami cron.
 * 
 * @category controllers
 * @package cron
 * @author
 * @copyright (C) 2015
 */
class CronController extends Controller
{
	// Pusty kontroler, potrzeby tylko do renderowania w ConsoleApplication.
	
	
	//public $defaultAction = 'index';
	
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    /*public function allowedActions()
    {
        return 'index';
        //, send_email, send_email_to_many
    }*/

    /**
     * Akcja domyślna.
     * Wyświetla stronę główną.
     * Obsługuje zapisanie do newslettera z formularza na stronie głównej.
     * @throws CHttpException
     */
    /*public function actionIndex()
    {
    	
    	$this->ajaxMode();
    	
    	$this->layout = 'mail';
    	Yii::app()->mailer->systemMail(
    			'kontakt@intercode.biz',
    			'Sprawdzenie crona',
    			'Tekst wiadomsoci firmbook.pl'
    	);
        
    	echo 'przeszlo';
    	exit;
    }*/

       
}