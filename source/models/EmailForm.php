<?php

class EmailForm extends CFormModel
{    
	public $recipientName;
	public $recipientItemName;
	public $subject;
	public $message;
	//hidden fields
	public $recipientId;		
	public $recipientType;	
	public $recipientItemId;
	public $recipientItemType;
	// creators fields
	public $recipientAddresses;
	public $recipientAll;
	
	
    public function rules()
    {
        return array(        	
        	array('recipientId, recipientType, recipientName, subject, message', 'required', 'on'=>'send'),        	
        	array('recipientId, recipientName, subject, message', 'required', 'on'=>'send_to_many'),
        		
        	array('recipientItemId, recipientItemType, recipientItemName', 'safe', 'on'=>'send'),
        	array('recipientType, recipientItemId, recipientItemType, recipientItemName', 'safe', 'on'=>'send_to_many'),

			array('subject, message', 'required', 'on'=>'creators-send'),
			array('recipientAll, recipientAddresses', 'safe', 'on'=>'creators-send'),
			array('recipientAddresses', 'correctWhenNotAll', 'on'=>'creators-send'),
        );
    }	
	
	public function correctWhenNotAll()
	{
		if (!empty($this->recipientAll)) return;

		if(!$this->recipientAddresses)
		  $this->addError('recipientAddresses', Yii::t('contact', 'No recipients.'));

			$addresses = explode(',', $this->recipientAddresses);
		$validator = new CEmailValidator();
		foreach($addresses as $address) {
			$trimmed = trim($address);
			if (!$validator->validateValue($trimmed))
				$this->addError('recipientAddresses', Yii::t('contact',
					'Please type correct email addresses, separated with commas.'));
		}
	}

    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'recipientName' => Yii::t('contact', 'To'),
            'subject' => Yii::t('contact', 'Subject'),
        	'message' => Yii::t('contact', 'Message'),
        	'recipientItemName' => Yii::t('contact', 'Concerns'),     
			'recipientAddresses' => Yii::t('contact', 'Email addresses'),
			'recipientAll' => Yii::t('contact', 'Send to all'),			
        );
    }
    
    
}
