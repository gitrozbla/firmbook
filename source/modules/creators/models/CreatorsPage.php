<?php
/**
 * Model konfiguracji generowania strony.
 * 
 * @category models
 * @package user
 */
class CreatorsPage extends ActiveRecord
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
        return '{{creators_page}}';
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
     * Relacje bazodanowe.
     * @return array
     */
    public function relations()
    {
        return array(
            'website' => array(self::BELONGS_TO, 'CreatorsWebsite', 'website_id', 'together'=>true)
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('CreatorsModule.page', 'Title'),
            'alias' => Yii::t('CreatorsModule.page', 'Alias'),
            'type' => Yii::t('CreatorsModule.page', 'Type'),
            'items_per_page' => Yii::t('CreatorsModule.page', 'Products/services/news per page'),
			'company_data' => Yii::t('CreatorsModule.page', 'Company data'),
            'content' => Yii::t('CreatorsModule.page', 'Content'),
			'comments' => Yii::t('CreatorsModule.page', 'Comments and opinions'),
			'comments_from_firmbook' => Yii::t('CreatorsModule.page', 'Comments from Firmbook'),
			'buttons' => Yii::t('CreatorsModule.page', 'Additional buttons'),
        );   
    }
    
    /**
     * Lista reguł walidacji.
     * @return arraywelcome$
     */
    public function rules()
    {
    	return array(
            array('scenario', 'in', 'range'=>array('empty', 'create', 'update', 'remove')),
            array('position', 'numerical', 'integerOnly'=>true),
            array('type', 'in', 'range'=>array_keys(self::getTypes()), 'allowEmpty'=>false),
            array('items_per_page', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>100),
            array('title, alias', 'length', 'max'=>70),
            array('title, alias', 'required', 'on'=>'create, update'),
            array('alias', 'match', 'pattern' => '/^[a-zA-Z0-9\-_.\s]+$/', 
                'message' => Yii::t('CreatorsModule.page', 'Only latin letters, digits and "-", "_", "." are allowed.')),
            array('alias', 'unique', 'criteria'=>array('condition'=>'website_id='.$this->website_id), 
                'caseSensitive'=>false, 'on'=>'create, update'),
			array('company_data', 'boolean'),
            array('content', 'length', 'max'=>1500),
            array('content', 'filter', 'filter'=>array($obj=new CHtmlPurifier(),'purify')),
			
			array('comments', 'boolean'),
			array('comments_from_firmbook', 'boolean'),
			
			array('buttons', 'safe'),
        );
    }
    
    
    public static function getTypes($translated=false)
    {
        if ($translated) {
            return array(
                'custom' => Yii::t('CreatorsModule.page', 'Custom'),
                'about' => Yii::t('CreatorsModule.page', 'About company'),
                'news' => Yii::t('CreatorsModule.page', 'News'),
                'products' => Yii::t('CreatorsModule.page', 'Products'),
                'services' => Yii::t('CreatorsModule.page', 'Services'),
                'contact' => Yii::t('CreatorsModule.page', 'Contact'),
            );
        } else {
            return array(
                'custom' => 'Custom',
                'about' => 'About company',
                'news' => 'News',
                'products' => 'Products',
                'services' => 'Services',
                'contact' => 'Contact',
            );
        }
    }
	
	public static function getAllButtons()
	{
		return array(
			'currency_converter' => Yii::t('CreatorsModule.page', 'Currency converter'),
			'delivery_services_comparison' => Yii::t('CreatorsModule.page', 'Delivery services comparison'),
			'exchange_rates' => Yii::t('CreatorsModule.page', 'Exchange rates'),
		);
	}
	
	public function afterFind()
	{
		if ($this->buttons === null) {	// init
			$this->buttons = array('exchange_rates', 'delivery_services_comparison', 'currency_converter');
			//$this->buttons = array();
		} else {
			$this->buttons = explode(',', $this->buttons);
		}

		return parent::afterFind();
	}

	public function beforeSave()
	{
		if (is_array($this->buttons)) {
			$this->buttons = implode(',', $this->buttons);
		}

		return parent::beforeSave();
	}
    
}
