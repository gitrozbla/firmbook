<?php

/**
 * This is the model class for table "tbl_post_like".
 *
 * The followings are the available columns in table '{{likedislike}}':
 * @property integer $id
 * @property integer $post_id
 * @property integer $post_type
 * @property integer $user_id
 * @property integer $status
 * @property string $created
 * @property string $modified
 */
class Likedislike extends CActiveRecord {

    public $count;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Likedislike the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_post_like';
    }

    public function behaviors() {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'timestampExpression' => new CDbExpression('NOW()'),
                'createAttribute' => 'created',
                'updateAttribute' => 'modified',
            )
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_id, user_id, status,post_type', 'required'),
            array('post_id, user_id, status', 'numerical', 'integerOnly' => true),
            array('created, modified', 'length', 'max' => 25),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, post_id, user_id, status, created, modified', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'post_id' => 'Post',
            'post_type' => 'Post Type',
            'user_id' => 'User',
            'status' => 'Status',
            'created' => 'Add Datetime',
            'modified' => 'Edit Datetime',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('post_id', $this->post_id);
        $criteria->compare('post_type', $this->post_type);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('modified', $this->modified, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function inverseDataProvider()
    {
//        echo '<br>->inverseDataProvider()';
    	$sql = '(SELECT t.id as id, t.username as name'.
            ', f.class, f.data_id, f.hash, f.extension, 2 as item_type'.
            ' FROM tbl_user t INNER JOIN tbl_post_like e on e.user_id=t.id'.
            //     			' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.item_id='.$this->item_id.' and e.type='.$this->type.' and e.item_type='.$this->item_type.')';
            ' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.post_id='.$this->post_id.' and e.post_type=\''.$this->post_type.'\')';
    	 
    	
//    echo '<br>sql: '.$sql;
    	$sort=new CSort;
    	$sort->defaultOrder = 'e.created DESC';
    	 
    	return new CSqlDataProvider($sql, array(
    	//     			'totalItemCount'=>$count,
    	//     			'sort'=> $sort,
    			/*array(
    			 'attributes'=>array(
    			 		'cache_type', 'name', 'date',
    			 ),
    			),*/
    	//     			'pagination'=>array(
    			//     					'pageSize'=>40,
    			//     			),
    	//     			'criteria'=>$criteria,
    			'sort'=>$sort
    	));
    
    }
}