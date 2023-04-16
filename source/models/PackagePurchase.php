<?php

class PackagePurchase extends ActiveRecord
{    
	
    //wykorzystane w adminSearch	
    public $date_expire;
    public $user_username;
    public $package_id;
	
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
        return '{{package_purchase}}';
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
        	'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        	'package' => array(self::BELONGS_TO, 'Package', 'package_id'),
            //'user' => array(self::HAS_MANY, 'User', 'package_id'),
        );
    }
    
    public function rules()
    {
        return array(
        	array('package_id, period', 'required'),
        	array('package_id, period', 'numerical', 'integerOnly'=>true),
        	array('package_id, period', 'integrationValidate'),
        	array('force_activation', 'boolean'),
        	//array('package_id', 'safe'),
        	//array('period', 'safe'),
        );
    }
    
    public function integrationValidate($attribute)
    {
        switch($attribute) {
            case 'package_id':
                if ($this->package_id != 0 
                    && 
                    (
                        $this->creators && !Package::model()->exists(
                            'id=:id AND active=1 && creators=1', 
                            array(':id'=>$this->package_id)
                        ) 
                        ||
                        !$this->creators && !Package::model()->exists(
                            'id=:id AND active=1 && creators=0', 
                            array(':id'=>$this->package_id)
                        )
                    )
                ) {
                    $this->addError($attribute, Yii::t('user', 'Package does not exists.'));
                }
                break;
            case 'period':         
				// 0 - okres testowy
                if ($this->period != 0 && !PackagePeriod::model()->exists(
                    'package_id=:package_id and period=:period', 
                    array(':package_id'=>$this->package_id, ':period'=>$this->period))
                ) {
                    $this->addError($attribute, Yii::t('user', 'Package does not exists.'));
                }
            	break;    
        }            
    }
    
    public function integrationValidate_20211028($attribute)
    {
        switch($attribute) {
            case 'package_id':
                if ($this->package_id != 0 && !Package::model()->exists(
                    'id=:id AND active=1', 
                    array(':id'=>$this->package_id))
                ) {
                    $this->addError($attribute, Yii::t('user', 'Package does not exists.'));
                }
                break;
            case 'period':         
				// 0 - okres testowy
                if ($this->period != 0 && !PackagePeriod::model()->exists(
                    'package_id=:package_id and period=:period', 
                    array(':package_id'=>$this->package_id, ':period'=>$this->period))
                ) {
                    $this->addError($attribute, Yii::t('user', 'Package does not exists.'));
                }
            	break;    
        }
            
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'package_id' => Yii::t('packages', 'Package'),
            'period' => Yii::t('packages', 'Period (months)'),
        	'force_activation' => Yii::t('packages', 'Do not wait until the expiry of the current package'),
        	'name' => Yii::t('packages', 'Package'),       
        	'price' => Yii::t('packages', 'Price'),
        	'status' => Yii::t('packages', 'Status'),
        	'date_start' => Yii::t('packages', 'Date from'),
        	'date_expire' => Yii::t('packages', 'Expires on'),    
        );
    }
       
    
    public function adminSearch()
    {
    	$criteria = new CDbCriteria;
    
    	$criteria->compare('id', $this->id);
    	$criteria->compare('t.creators', $this->creators ? 1 : 0);
    	$criteria->together  =  true;
    	$criteria->with = array('user', 'package');
    	//$criteria->with = array('item', 'user');
    	
    	if($this->date_expire)
    	{
    		$criteria->compare('date_expire', $this->date_expire, true);
    	}
    	if($this->user_username)
    	{
    		$criteria->compare('user.username', $this->user_username, true);
    	}
    	if($this->package_id)
    	{
    		$criteria->compare('package_id', $this->package_id, true);
    	}
    	// Create a custom sort
    	$sort=new CSort;
    	$sort->attributes=array(
    			
    			'user_username' => array(
    					'asc' => 'user.username',
    					'desc' => 'user.username DESC',
    					'label' => 'Użytkownik',
    			),
    			'package_id' => array(
    					'asc' => 't.package_id',
    					'desc' => 't.package_id DESC',
    					'label' => 'Pakiet',
    			),
    			/*'date_expire' => array(
    					'asc' => 'date_expire',
    					'desc' => 'date_expire DESC',
    					'label' => 'Wygasa',
    			),*/
    			'*',
    	);
    	$sort->defaultOrder = 't.date_added DESC, t.package_id DESC';
    	//$sort->defaultOrder = 'date DESC, package_id DESC';
    
    
    	return new CActiveDataProvider('PackagePurchase', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
    					'pageSize' => 50 ,
    			),
    	));
    
    }
    
}
