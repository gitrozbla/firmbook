<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Social extends CComponent
{
    
    public static function getNewItemInCategoryMessageRecipients($categoryId)
    {
//        echo '<br>getNewItemInCategoryMessageRecipients';        
        $category = Category::model()->findByPk($categoryId);        
        $followingDataProvider = $category->followingDataProvider();
        $recipients = $followingDataProvider->getData();        
        $itemInCategoryAdderDataProvider = $category->itemInCategoryAdderDataProvider();
        $adders = $itemInCategoryAdderDataProvider->getData();
        $recipientsIds = [];
        foreach ($recipients as $recipient)        
            $recipientsIds[$recipient['id']] = 1;
            
        foreach ($adders as $adder)        
            if(!array_key_exists($adder['id'], $recipientsIds))
            {
                $recipients[] = $adder;
                $recipientsIds[$adder['id']] = 1;
            }                    
        
        return $recipients;
    }       
    
    public static function getNewItemMessageRecipients($item)
    {
        /*
         * pobierz wszystkich uytkownikow, ktorzy polubili lub obserwuja dodajacego uzytkownika
         */        
        $elist = new Elist();
    	$elist->unsetAttributes();
    	$elist->item_id = $item->user->id;
    	$elist->item_type = Elist::ITEM_TYPE_USER;        
        $dataProvider = $elist->inverseRecipientsDataProvider();
        $recipients = $dataProvider->getData();                
        $recipientsIds = [];
        $recipientsRes = [];
        foreach ($recipients as $recipient)        
            if(!array_key_exists($recipient['id'], $recipientsIds))
            {
                $recipientsRes[] = $recipient;
                $recipientsIds[$recipient['id']] = 1;
            }         
        
        $follow = new Follow();
    	$follow->unsetAttributes();   			     	
    	$follow->item_id = $item->user->id;
    	$follow->item_type = Follow::ITEM_TYPE_USER;
        $dataProvider = $follow->inverseRecipientsDataProvider();
        $recipients = $dataProvider->getData();      
        foreach ($recipients as $recipient)        
            if(!array_key_exists($recipient['id'], $recipientsIds))
            {
                $recipientsRes[] = $recipient;
                $recipientsIds[$recipient['id']] = 1;
            }
        
        /*
         * jesli dodano w kontekscie firmy, to pobierz wszystkich uzytkownikow, 
         * ktorzy polubili, dodali do elity lub obserwuja firme dodajaca
         */
        /*
         * polacz listy uzytkownikow
         */        
        $elist->unsetAttributes();
        $elist->item_id = $item->id;
    	$elist->item_type = Elist::ITEM_TYPE_ITEM;
        $dataProvider = $elist->inverseRecipientsDataProvider();
        $recipients = $dataProvider->getData();
        foreach ($recipients as $recipient)        
            if(!array_key_exists($recipient['id'], $recipientsIds))
            {
                $recipientsRes[] = $recipient;
                $recipientsIds[$recipient['id']] = 1;
            }  
            
    	$follow->item_id = $item->id;
    	$follow->item_type = Follow::ITEM_TYPE_COMPANY;
        $dataProvider = $follow->inverseRecipientsDataProvider();
        $recipients = $dataProvider->getData();
        foreach ($recipients as $recipient)        
            if(!array_key_exists($recipient['id'], $recipientsIds))
            {
                $recipientsRes[] = $recipient;
                $recipientsIds[$recipient['id']] = 1;
            }
        
        return $recipientsRes;
    }        
}