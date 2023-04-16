<?php
/**
 * Model formularza kontaktowego.
 * 
 * @category models
 * @package contact
 * @author
 * @copyright (C) 2015
 */
class AdContact extends Contact
{
        
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'forename' => Yii::t('ad', 'Contact name'),
            'surname' => Yii::t('ad', 'Company name'),
            'phone' => Yii::t('user', 'Phone'),
            'email' => Yii::t('user', 'Email'),
            'subject' => Yii::t('ad', 'Type of Ad.'),
            'message' => Yii::t('contact', 'Message'),
        );
    }   
    
}