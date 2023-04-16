<?php

class PackagePeriod extends ActiveRecord
{   
    /**
     * Domyślna pakiet - id w db.
     * @var int
     */
    public static $_periods = array(3, 6, 12, 24);
    	
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
        return '{{package_period}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
        return array('package_id', 'period');    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
            'package' => array(self::BELONGS_TO, 'Package', 'package_id'),
        );
    } 
    
	public function rules()
    {
        return array(   
        	array('package_id, period, price', 'required'),
        	array('package_id, period', 'numerical', 'integerOnly'=>true),        	     	
        	array('price', 'numerical'),        	
        	//array('package_id, period', 'unique'),
        	array('period', 'integrationValidate', 'on'=>'insert'),        	
        	array('description', 'safe'),
        	
        	//array('package_id, period, description', 'safe'),     	
        );
    }
    
	public function integrationValidate($attribute)
    {
        switch($attribute) {
            //case 'package_id':                
            case 'period':            	
        		if (PackagePeriod::model()->exists(
                        'package_id=:package_id and period=:period', 
                        array(':package_id'=>$this->package_id, ':period'=>$this->period))
                        ) {
                    $this->addError($attribute, Yii::t('packages', 'Package already has an entry for the indicated period.'));
                }
            	break;    
        }
            
    }
    
	public function attributeLabels() {
        return array(            
            'package_id' => Yii::t('packages', 'Package'),
        	
        	'period' => Yii::t('packages', 'Period'),
        	'price' => Yii::t('packages', 'Price'),
        	
        );
    }
    
    public static function periodsToSelect()
    {
    	return array_combine(self::$_periods, self::$_periods);
    }
    
	public static function periodsDataProvider($creators=false)
	{
		$criteria = new CDbCriteria();
		$criteria->join = 'INNER JOIN tbl_package p on p.id=package_id';
		$criteria->condition = $creators ? 'p.creators' : '!p.creators';
		$criteria->order = 'package_id, period';
		
		return new CActiveDataProvider('PackagePeriod', array(
			'criteria'=> $criteria,
			/*array(				
				//'select' => 't.*, p.name as name',
				'join' => 'INNER JOIN tbl_package p on p.id=package_id',
				//'condition'=>'user_id='.$userId,
			    'order'=>'package_id, period',
				//'with'=>array('package'),				
			),*/
			/*'sort'=>array(
				'attributes'=>array(
					'name'=>array(
						'asc'=>'name',
						'desc'=>'name DESC'
					),					
					'*',	
				)				
			),*/
			'pagination' => false,            
        ));
        
    }
    
}
