<?php
/**
 * Model wiadomości newslettera.
 * 
 * @category models
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class NewsletterPost extends ActiveRecord
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
        return '{{newsletter_post}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
        return array(
            // login
            array('subject, content', 'required', 'on'=>'send'),
            array('subject', 'length', 'min'=>3, 'max'=>128, 'on'=>'update, send'),
            array('content', 'length', 'min'=>3, 'on'=>'update, send'),
            );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'subject' => Yii::t('newsletter', 'Subject'),
            'content' => Yii::t('newsletter', 'Content'),
        );
    }
    
    /**
     * Dostarcza listy znalezionych w wyszukiwaniu modeli.
     * @return \CActiveDataProvider
     */
    public function search() 
    {
        return new CActiveDataProvider('NewsletterPost', array(
            'sort' =>array(
                'defaultOrder'=>'sent ASC, datetime DESC',
            ),
            'pagination' => array(
                'pageSize' => 8,
            )
        ));
    }
    
}