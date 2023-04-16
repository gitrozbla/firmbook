<?php
/**
 * Model cech wspólnych produktu i usługi.
 * 
 * @category models
 * @package product
 * @author
 * @copyright (C) 2015
 */
class OfferItem extends ActiveRecord
{
    protected $discount;
    public static $allegroLinkPattern = '/^.*allegro\\.pl[\/]oferta[\/]([^\?]+)([\?]{1}.*)?$/i';

    /**
     * Tworzy instancję.
     * @param string $className Klasa instancji.
     * @return object Utworzona instancja zadanej klasy.
     */
    /*public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }*/
    
    /**
     * Nazwa tabeli.
     * @return string
     */
    /*public function tableName()
    {
        return '{{product}}';
    }*
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'item_id';    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
            'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'together'=>true),
        	'user' => array(self::HAS_ONE, 'User', array('user_id'=>'id'), 'through'=> 'item'),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id', 'together'=>true),
        	'currency' => array(self::BELONGS_TO, 'Dictionary', 'currency_id'),
        	'unit' => array(self::BELONGS_TO, 'Dictionary', 'unit_id'),
        );
    }
    
    public function rules()
    {
        return array(            
            array('company_id', 'integrationValidate', 'on'=>'update, create'),
            array('short_description', 'length', 'max'=>250, 'on'=>'update, create'),
            array('signature', 'length', 'max'=>50, 'on'=>'update, create'),
            array('youtube_url', 'length', 'max'=>100, 'on'=>'update, create'),   
                        array('price, promotion_price', 'match', 
                                'pattern' => '/^[1-9]{1}\d{0,9}(?:[\.,]{1}\d{1,2})?$|^[0]{1}(?:[\.,]{1}\d{1,2})?$/',
                                //'pattern' => '/^[1-9]{1}\d{0,9}([\.,]{1}\d{1,2}){0,1}$|^[0]{1}([\.,]{1}\d{1,2}){0,1}$/', 
                                'message' => 'Podaj właściwą cenę', "on"=>"update, create"),            
            array('price, promotion_price', 'length', 'max'=>13, 'on'=>'update, create'),
            array('currency_id', 'numerical', 'min'=>1, 'on'=>'update, create'),	            
//         		array('currency_id', 'filter', 'filter'=>array($this, 'ruleCurrency'), 'on'=>'update, create'),
                        array('unit_id', 'numerical', 'on'=>'update, create'),			
            array('promotion_expire', 'type', 'type'=>'date', 'dateFormat'=>'MM/dd/yyyy', 'on'=>'update, create'),
            //array('promotion_expire', 'type', 'type'=>'date', 'dateFormat'=>'yyyy-MM-dd', 'on'=>'update, create'),
            array('promotion', 'boolean', 'on'=>'update, create'),          	
            array('allegro', 'safe'),//'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>1000000000),
            array('allegro_link', 'match',
                'pattern'=>self::$allegroLinkPattern,
                'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),
        );
    }
    
    
//     public function ruleCurrency($code)
//     {
//     	echo '<br>ruleCurrency';
//     	var_dump($code);
//     	var_dump($this->price);
//     	var_dump($this->promotion_price);
//     	var_dump($this->promotion);
//     	var_dump($this->promotion_expire);
//     }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
            'company_id' => Yii::t('product', 'Offering company'),
            'short_description' => Yii::t('common', 'Short description'),
            'signature' => Yii::t('product', 'Signature'),
            'youtube_url' => Yii::t('product', 'Youtube movie'),
            'price' => Yii::t('product', 'Promotion price'),
            'currency_id' => Yii::t('common', 'Currency'),
            'unit_id' => Yii::t('product', 'Unit'),
            'promotion' => Yii::t('common', 'Promotion'),

            'promotion_price' => Yii::t('product', 'Old price'),
            //'promotion_price' => Yii::t('product', 'Promotion price'),
            'promotion_expire' => Yii::t('product', 'Promotion expire date'), 
            'discount' => Yii::t('common', 'Discount'),
//            'allegro_link' => Yii::t('company', 'Link to shop on Allegro'),
        );
    }
    
    public function integrationValidate($attribute)
    {
        switch($attribute) {
            case 'company_id':
                // company exists and has the same user
                if ($this->company_id != 0 && !Item::model()->exists(
                        'id=:id and cache_type=\'c\' and user_id=:user_id', 
                        array(
                            ':id'=>$this->company_id,
                            //':user_id'=>$this->item->user_id
                            ':user_id'=>Yii::app()->user->id))
                        ) {
                    $this->addError($attribute, Yii::t('companies', 'Company does not exists.'));
                }
                break;
        }
            
    }
    
	public function beforeSave()
    {        
    	if(empty($this->company_id) || !$this->company_id)
			$this->company_id = NULL;    	
    	
		if(isset($this->short_description) && empty($this->short_description))
			$this->short_description = NULL;	

		if(isset($this->signature) && empty($this->signature))
			$this->signature = NULL;	
			
		if(isset($this->youtube_url) && empty($this->youtube_url))
			$this->youtube_url = NULL;	
			
		if(isset($this->price) && empty($this->price))
			$this->price = NULL;			
		if(!empty($this->price))			
			$this->price = str_replace(',', '.', $this->price);	
				
		if(empty($this->currency_id) || !$this->currency_id)
			$this->currency_id = NULL;

		if(empty($this->unit_id) || !$this->unit_id)
			$this->unit_id = NULL;	

		if(isset($this->promotion_price) && empty($this->promotion_price))
			$this->promotion_price = NULL;	
		if(!empty($this->promotion_price))
			$this->promotion_price = str_replace(',', '.', $this->promotion_price);	
			
    	if (empty($this->promotion_expire)) 			
	        $this->promotion_expire = NULL;	    
    	
		if(!empty($this->promotion_expire))
			$this->promotion_expire = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->promotion_expire);
					
        return true;
    }
    
	public function afterSave()
    {        
		if(!empty($this->promotion_expire))
			$this->promotion_expire = Yii::app()->dateFormatter->format('MM/dd/yyyy', $this->promotion_expire);

		if ($this->isNewRecord) {
			//echo '<br/>'.'nowy rekord';
			$item = Item::model()->findByPk($this->item_id);
//			if($item->cache_type == 'p')
//				$alertItemType = Alert::ITEM_TYPE_PRODUCT;
//			else
//				$alertItemType = Alert::ITEM_TYPE_SERVICE;
            $alertItemType = Alert::ITEM_TYPE_ITEM;
			// powiadomienia dla obserwowanych
			// pobieramy listę użytkowników obserwujących firmę, która dodała produkt
			if(isset($this->company_id) && $this->company_id) {
				$followRows = Follow::model()->findAll(
						'item_id=:item_id and item_type=:item_type',
						array(':item_id'=>$this->company_id, ':item_type'=>Follow::ITEM_TYPE_COMPANY)
				);
				//print_r($followRows);
				if($followRows) {
					foreach($followRows as $follow) {
						$alert = new Alert;
						$alert->user_id = $follow->user_id;
						$alert->context_id = $this->company_id;
						$alert->context_type = Alert::CONTEXT_TYPE_COMPANY;
						$alert->item_id = $this->item_id;
						$alert->item_type = $alertItemType;
						$alert->event = Alert::EVENT_ADD;
						$alert->save();
						unset($alert);
					}
				}
			}
		
			// pobieramy listę użytkowników obserwujących użytkownika, który dodał produkt
			$followRows = Follow::model()->findAll(
					'item_id=:item_id and item_type=:item_type',
					array(':item_id'=>$item->user_id, ':item_type'=>Follow::ITEM_TYPE_USER)
			);
			if($followRows) {
				foreach($followRows as $follow) {
					$alert = new Alert;
					$alert->user_id = $follow->user_id;
					$alert->context_id = $item->user_id;
					$alert->context_type = Alert::CONTEXT_TYPE_USER;
					$alert->item_id = $this->item_id;
					$alert->item_type = $alertItemType;
					$alert->event = Alert::EVENT_ADD;
					$alert->save();
					unset($alert);
				}
			}
			
			// pobieramy listę użytkowników obserwujących kategorię, do której dodano produkt
			$followRows = Follow::model()->findAll(
					'item_id=:item_id and item_type=:item_type',
					array(':item_id'=>$item->category_id, ':item_type'=>Follow::ITEM_TYPE_CATEGORY)
			);
			if($followRows) {
				foreach($followRows as $follow) {
					$alert = new Alert;
					$alert->user_id = $follow->user_id;
					$alert->context_id = $item->category_id;
					$alert->context_type = Alert::CONTEXT_TYPE_CATEGORY;
					$alert->item_id = $this->item_id;
					$alert->item_type = $alertItemType;
					$alert->event = Alert::EVENT_ADD;
					$alert->save();
					unset($alert);
				}
			}
		}
		
        return true;
    }
    
    /*public function beforeSave_org()
    {
        if ($this->isNewRecord) {
            $item = new Item('create');
            $item->cache_type = 'p';
            $item->save();
            
            $this->item_id = $item->id;
        }
        
        return true;
    }*/
    
    /*
     * usuwa promocje dla produktow i uslug
     * w przyszlosci powinno byc obslugiwane poprzez CRONa
     */
	public function afterFind()
	{
		$currentDate = date('Y-m-d');	
		
		if(!empty($this->promotion) && $this->promotion 
		&& !empty($this->promotion_expire) && $this->promotion_expire 
		&& ($this->promotion_expire < $currentDate))
		{			
			$this->promotion = 0;
			if(!empty($this->promotion_price)) {
				$currentPrice = $this->price; 
				$this->price = $this->promotion_price;
				$this->promotion_price = $currentPrice;								
			}	
			$this->promotion_expire = Yii::app()->dateFormatter->format('MM/dd/yyyy', $this->promotion_expire);
			$this->save();	
		}
	}
    
	public function getDiscount()
	{
		if($this->price && $this->promotion_price && $this->price < $this->promotion_price) {
			return ($this->promotion_price-$this->price)*100/$this->promotion_price;			
		}
			
		return '';
	}
    
    public function getAllegro_link() {
        return !empty($this->allegro)
            ? 'https://allegro.pl/oferta/' . $this->allegro
            : '';
    }

    public function setAllegro_link($value) {
        // extract id from link
        if ($value !== null) {
            if (empty($value)) $this->allegro = null;
            else {
                preg_match(self::$allegroLinkPattern, $value, $matches);
                if (isset($matches[1])) {
                    $this->allegro = $matches[1];
                }
            }
        }
    }
}
