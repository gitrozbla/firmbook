<?php
/**
 * Model formularza kontaktowego.
 * 
 * @category models
 * @package contact
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Contact extends ActiveRecord
{
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
        return '{{contact}}';
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'forename' => Yii::t('user', 'Forename'),
            'surname' => Yii::t('user', 'Surname'),
            'phone' => Yii::t('user', 'Phone'),
            'email' => Yii::t('user', 'Email'),
            'subject' => Yii::t('contact', 'Subject'),
            'message' => Yii::t('contact', 'Message'),
        );
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules() {
        
        return array(
            array('forename, surname, subject, message','filter','filter'=>array(
                $obj=new CHtmlPurifier(),'purify')
                ),
            array('forename, surname, subject, message', 'required', 'on'=>'compose'),
            array('forename, surname', 'length', 'max'=>32, 'encoding'=>false),
            array('subject', 'length', 'max'=>128, 'encoding'=>false),
            array('phone', 'phone'),
            array('email', 'email'),
            array('phone', 'oneRequired', 'others'=>'email', 'message'=>Yii::t('contact', 'Please type phone number or email address.')),
        );
    }
    
    /**
     * Callback wykonywany przed zapisaniem zmian w bazie.
     * @return boolean
     */
    public function beforeSave() {
        $this->phone = preg_replace("/[^0-9,.]/", "", $this->phone);
        return parent::beforeSave();
    }
    
    
}