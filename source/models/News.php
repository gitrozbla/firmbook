<?php
/**
 * Model podstrony (artykułu).
 *
 * @category models
 * @package news
 * @author
 * @copyright (C) 2015
 */
class News extends ActiveRecord
{
		protected $lastPhoto_file_id;
		protected $updateUserFileData_id = false;
		public $photoUpload;

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
        return '{{news}}';
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
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
    	return array(
    			'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'together'=>true),
    			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
    			//'user' => array(self::HAS_ONE, 'User', 'user_id', 'together'=>true)
					'photo' => array(self::BELONGS_TO, 'UserFile', 'photo_file_id', 'together'=>true),
    	);
    }

		/**
	     * Nazwy atrybutów.
	     * @return array
	     */
	    public function attributeLabels() {
	        return array(
	        	'title' => Yii::t('common', 'Title'),
	            'description' => Yii::t('news', 'Description'),
	        	'content' => Yii::t('news', 'Content'),
	        	'active' => Yii::t('common', 'Active'),

	        	'date' => Yii::t('common', 'Date'),
						'photo_file_id' => Yii::t('news', 'Photo'),
						'photoUpload' => Yii::t('news', 'Upload photo'),
	        );
	    }

	public function rules()
    {
        return array(
        	array('title', 'required', 'on'=>'update, create'),
        	array('active', 'boolean', 'on'=>'update, create'),
        	array('description', 'length', 'max'=>500, 'on'=>'update, create'),
        	array('content', 'safe'),
        	array('item_id, user_id', 'safe'),
        	//array('active', 'safe', 'on'=>'view'),
        	/*array('description, content, active', 'safe'),
        	//sortowanie na liście artykułów
        	array('date', 'on'=>'view'),*/
					array('photo_file_id', 'ownPhotoValidate'),
					array('photoUpload', 'file', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>5000000,
							'safe'=>true, 'allowEmpty'=>true),
        );
    }

		public function ownPhotoValidate($attribute)
		{
			$value = $this->$attribute;

			if ($value === 'none') return;	// allowed

			if ($value === 'other') {
				//if (!$this->photoUpload) $this->addError('photoUpload', 'Wybierz plik.');
				return;
			}

			$file = UserFile::model()->findByPk($value);
			if (!$file) {
				$this->addError($attribute, 'File not found.');
				return;
			}

			if (($file->class == 'News' && $file->data_id == $this->id)
				||
				($file->class == 'Item' && $file->data_id == $this->item_id)) {
					return;	// allowed
			}

			$this->addError($attribute, 'File not allowed.');
		}

		public function afterFind()
		{
			$this->lastPhoto_file_id = $this->photo_file_id;
		}

    public function beforeSave()
    {
    	if ($this->isNewRecord) {
    		if (empty($this->user_id)) {
    			$user = Yii::app()->user;
    			if ($user->isGuest) {
    				return false;
    			}

    			$this->user_id = $user->id;
    		}
    	}

			if ($this->photo_file_id === 'none') $this->photo_file_id = null;

    	return parent::beforeSave();
    }

		public function afterSave()
		{
			if ($this->photo_file_id === 'other') {
				if (!isset($_FILES['News'])) {
					$this->photo_file_id = null;
				} else {
					$photo = new UserFile('create');
					$attributes = array(
							'class' => 'News',
							'data_id' => $this->id,
							'hash' => UserFile::generateRandomHash(),
							'extension' => 'jpeg',
					);

					foreach(UserFile::getImageSizes() as $size=>$postfix) {
							$attributes[$size] = 1;
					}
					$photo->setAttributes($attributes, false);
					// data saved separately, because it's not an attribute
					$photo->data = $_FILES['News'];
					$photo->save();
					//$this->photo_file_id = $photo->id;
					// Poprawiamy photo_file_id bezpośrednio w bazie,
					// aby ominąć powtórzenie walidacji i before/after save
					Yii::app()->db->createCommand()
						->update('tbl_news',
							array('photo_file_id' => $photo->id),
							'id=:id', array(':id' => $this->id)
						);
				}
			}

			// Stary plik usuwamy po dodaniu nowego, aby uniknąć błędu przy ponownym tworzeniu folderu.
			if ($this->photo_file_id !== $this->lastPhoto_file_id) {
				$oldPhoto = UserFile::model()->findByAttributes(array(
					'class' => 'News',
					'id' => $this->lastPhoto_file_id
				));
				if ($oldPhoto) $oldPhoto->delete();
			}

			return parent::afterSave();
		}

		public function beforeDelete()
		{
			$photos = UserFile::model()->findAllByAttributes(array(
				'class' => 'News',
				'data_id' => $this->id
			));
			foreach($photos as $photo) {
				$photo->delete();	// removes files in UserFile::beforeRemove()
			}

			return parent::beforeDelete();
		}

    public static function newsDataProvider($item_id)
    {
    	$criteria = new CDbCriteria;
    	$criteria->condition = "item_id = ".$item_id;

    	if (!Yii::app()->user->isGuest) {
    		$criteria->condition .= ' and (active=1 or user_id=:user_id)';
    		$criteria->params = array(':user_id'=>Yii::app()->user->id);
    	} else {
    		$criteria->condition .= ' and active=1';
    	}

    	$sort = new Sort;
    	$sort->defaultOrder = 'date DESC';

    	return new CActiveDataProvider('News', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
							'class' => 'Pagination',
    					'pageSize' => 20,
    			),
    	));
    }
    /*public function newsDataProvider()
    {
    	$criteria = new CDbCriteria;
    	$criteria->condition = "item_id = ".$this->item_id;

    	if (!Yii::app()->user->isGuest) {
    		$criteria->condition .= ' and (active=1 or user_id=:user_id)';
    		$criteria->params = array(':user_id'=>Yii::app()->user->id);
    	} else {
    		$criteria->condition .= ' and active=1';
    	}

    	$sort = new CSort;
    	$sort->defaultOrder = 'date DESC';

    	return new CActiveDataProvider('News', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
    				'pageSize' => 20 ,
    			),
    	));
    }*/

    public function newsDataProvider_old()
    {
    	$criteria = new CDbCriteria;
    	$criteria->condition = "item_id = ".$this->item_id;
    	if($this->active)
    		$criteria->condition .= " active = ".$this->item_id;
    	$additionalCriteria = array();
    	if($limit)
    		$additionalCriteria['limit'] = $limit;

    	return new CActiveDataProvider('News', array(
    			'criteria' => array_merge(array(
    					'condition' =>
    					'item_id=:item_id '
    					. ($active ? 'AND active=1' : '')
    					. " AND cache_type=:cache_type",
    					'params' => array(
    							':item_id' => $itemId,
    							':cache_type' => $type,
    					),
    					//'limit' => $limit,
    					'order' => 'date DESC',
    			),
    					$additionalCriteria
    			),

    			'pagination' => true,
    	)
    	);
    }


    public function elistDataProvider()
    {
    	$criteria = new CDbCriteria;

    	$criteria->together  =  true;
    	$criteria->with = array('item');

    	$criteria->condition = "t.user_id = ".$this->user_id;
    	//$criteria->compare('user_id', $this->user_id);

    	if($this->name)
    	{
    		$criteria->compare('item.name', $this->name, true);
    	}
    	if($this->type)
    	{
    		//echo 'typ z modelu: '.$this->type;
    		$criteria->compare('type', $this->type);
    	}
    	if($this->cache_type)
    	{
    		$criteria->compare('item.cache_type', $this->cache_type);
    	}

    	// Create a custom sort
    	$sort=new CSort;
    	$defaultOrder = ', t.date DESC';
    	$sort->attributes=array(
    			// For each relational attribute, create a 'virtual attribute' using the public variable name
    			'name' => array(
    					'asc' => 'item.name'.$defaultOrder,
    					'desc' => 'item.name DESC'.$defaultOrder,
    					'label' => 'Nazwa',
    			),
    			'cache_type' => array(
    					'asc' => 'item.cache_type'.$defaultOrder,
    					'desc' => 'item.cache_type DESC'.$defaultOrder,
    					'label' => 'Typ',
    			),
    			'date' => array(
    					'asc' => 't.date',
    					'desc' => 't.date DESC',
    					'label' => 'Dodano',
    			),
    			'*',
    	);
    	//$sort->defaultOrder = 'item_id DESC, item.cache_package_id DESC';
    	$sort->defaultOrder = 't.date DESC';


    	return new CActiveDataProvider('Elist', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
    					'pageSize' => 20 ,
    			),
    	));

    }


		public function getAllowedPhotos() {
			$companyPhotos = UserFile::model()->findAll('class="Item" AND data_id=:item_id', array('item_id' => $this->item_id));
			$ownPhotos = UserFile::model()->findAll('class="News" AND data_id=:item_id', array('item_id' => $this->id));
			$result = array();
			foreach(array_merge($companyPhotos, $ownPhotos)  as $photo) {
				//var_dump($photo->id);
				$result[(int)($photo->id)]= $photo->generateUrl('small');
			}
			$ownPhotos = UserFile::model()->findAll('class="News" AND data_id=:item_id', array('item_id' => $this->id));

			//var_dump($result);
			return $result;
		}
}
