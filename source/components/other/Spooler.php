<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Spooler extends CComponent
{
    const EXECUTION_LIMIT = 200;

    /**
     * @todo Sprawdzic czy istnieje oczekujaca wiadomosc oz tym samym spool_cache_key, 
     * jesli tak to usunac wpis z tabeli spool i spool_cache.
     * Skoro nei wyslano wiaodmosci do tej pory i pojawia sie nowa, to trzeba usunac stara.
     */
    public static function create($spoolCacheKey, $email, $subject, $body, $bcc=false)
    {
        self::deleteWithKey($spoolCacheKey);
        
        $spool = new Spool;
        $spool->spool_cache_key = $spoolCacheKey;
        $spool->email = $email;
        $spool->subject = $subject;
        if($bcc)
           $spool->bcc = $bcc; 
        $spool->save();
        
        $cache = Yii::app()->spool_cache;
        $cache->set($spoolCacheKey, $body);
    }
    
    public static function deleteWithKey($spoolCacheKey)
    {
        $spools = Spool::model()->findAllByAttributes(['spool_cache_key' => $spoolCacheKey]);
        foreach($spools as $spool)
            $spool->delete();
        
        $cache = Yii::app()->spool_cache;
        $cache->delete($spoolCacheKey);      
    }
    
    public static function sendPortion()
    {
        Yii::app()->mailer->AddEmbeddedImage('images/branding/email-logo.png', 'logo_'.Yii::app()->name);
//        $replyTo = 
        $cache = Yii::app()->spool_cache;
        $messages = self::earliestPortion();
        foreach($messages as $message)
        {
            try
            {
//                $spool = Spool::model()->findByAttributes(['spool_cache_key'=>$message])
                $body = $cache->get($message->spool_cache_key);
                if($message->bcc)
                    Yii::app()->mailer->systemMail(
                        $message->email,    
                        $message->subject,
                        $body,        
        //            $replyTo
                        null,
                        true,
                        $message->bcc
                    );
                else
                    Yii::app()->mailer->systemMail(
                        $message->email,    
                        $message->subject,
                        $body       
                    );
                $message->delete();
                $cache->delete($message->spool_cache_key);                
            } catch(CHttpException $e)
            {                
            }
        }
    }     
    
    public static function earliestPortion()
    {
        
        $spoolProvider = new ActiveDataProvider('Spool', array(
            'criteria' => array(
                'order' => 't.date',
                'limit' => self::EXECUTION_LIMIT,                                                
            ),
            'pagination' => false            
        ));
        $messages = $spoolProvider->getData();

        return $messages;
    } 
}