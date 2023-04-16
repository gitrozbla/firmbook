<?php
/**
 * Model czytelnika newslettera.
 * 
 * @category models
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class NewsletterReader extends ActiveRecord
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
        return '{{newsletter_reader}}';
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
        return array(
            // login
            array('email', 'required', 'on'=>'create, update'),
            array('email', 'email', 'on'=>'create, update'),
            array('email', 'unique', 'on'=>'create, update', 'message'=>Yii::t('newsletter', 
                    'This user is already subscribed.')),
            );
    }
    
    
    /**
     * Dostarcza listy znalezionych w wyszukiwaniu modeli.
     * @return \CActiveDataProvider
     */
    public function search() 
    {
        return new CActiveDataProvider('NewsletterReader', array(
            'sort' =>array(
                'defaultOrder'=>'email ASC',
            ),
            'pagination' => array(
                'pageSize' => 30,
            )
        ));
    }
    
}