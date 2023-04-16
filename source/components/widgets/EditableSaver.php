<?php

Yii::import('bootstrap.widgets.TbEditableSaver', true);

/**
 * Helper zapisujący zmiany z edytowalnych pól.
 * 
 * Dodana obsługa wysyłania plików z walidacją i base64.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableSaver extends TbEditableSaver 
{
    public $scenario = 'update';
    
    /**
     * Ścieżka do starego pliku.
     * Potrzebne do usunięcia poprzedniego pliku.
     * @var string|null
     */
    protected $oldFile = null;
    /**
     * Dane pliku (zdekodowane). String lub obiekt z listy $_FILES.
     * @var string|resource
     */
    protected $fileData = null;
    
    protected $fileHash = null;
    /**
     * Rozszerzenie pliku.
     * @var string|null
     */
    protected $fileExtension = null;
    
    protected $fileMode = false;
    
    protected $newFileId = null;
    
    
    /**
     * Aktualizacja danych, wraz z uprzednią walidacją.
     */
    public function update()
    {
        $request = Yii::app()->request;
        $this->attribute = $request->getParam('name');
        $this->primaryKey = $request->getParam('pk');
        $this->value = $request->getParam('value');
                
        if($this->modelClass == 'User' && $this->attribute == 'username')
        {
        	Yii::app()->user->setState('username', $this->value);
        	Yii::app()->user->setState('name', $this->value);
        }	
        
        /*if($this->modelClass == 'User' && $this->attribute == 'package_id')
        {
        	Yii::app()->user->setState('package_id', $this->value);        	
        }*/
        
        if ($request->getParam('fileMode')) {
            $this->fileMode = true;
            $this->model = CActiveRecord::model($this->modelClass)->findByAttributes(array(
                'hash' => $request->getParam('fileHash'),
                'class' => $request->getParam('ownerClass'), 
                'data_id' => $request->getParam('ownerPk')
            ));
            $isFile = true;
        } else {
            $this->model = CActiveRecord::model($this->modelClass)->findByPk($this->primaryKey);
            
            $attribute = $this->attribute;
            $validators = $this->model->getValidators($attribute);
            foreach($validators as $validator) {
                if (get_class($validator) == 'userFileValidator' or is_subclass_of($validator, 'userFileValidator')) {
                    $isFile = true;
                    break;
                }
            }
        }
            
        if (!empty($isFile)) 
        {  
            // is file
            
            $sep = '/';//DIRECTORY_SEPARATOR;   <- backslash will not work with html
            
            // get extension
            if (!empty($_FILES)) {
            	//echo '<br/>jest plik w $_FILES<br/>';
                $this->fileData = reset($_FILES);
                $filename = $this->fileData['name'][$this->attribute];
                $this->fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

                $this->fileHash = $_POST['hash'];
            } else {
            	//echo '<br/>nie ma pliku w $_FILES<br/>';
                $this->fileHash = substr($_POST['value'], 0, 16);
                
                $basePart = substr($_POST['value'], 17, 32);
				/*var_dump($basePart);
				var_dump($this->value);
				return;*/

                $parts = explode(';', substr($basePart, 11, 15));
                $this->fileExtension = $parts[0];

                if (!empty($this->value)) {

					if (substr($basePart, 0, 11) != 'data:image/') {
							$this->error(Yii::t('editable', 'No image.'));
					}
					
                    //data
                    $this->fileData = substr($_POST['value'], 17);
                    // POST data, used across with fileValidator
                    $post = get_class($this->model).'['.$this->attribute.'.base64]';
                    $_POST[$post] = $this->fileData;
                    
                    $base64CleanData = preg_replace('#^data:image/[^;]+;base64,#', '',$this->fileData);
                    $this->fileData = base64_decode($base64CleanData);
                } else {
                    $this->value = null;    // should save null, but saves empty string?
                }
            }
            
            // remember old file
            //$this->oldFile = $this->model->$attribute;
            if ($this->model) {
                if ($this->fileMode) {
                    $this->oldFile = $this->model->primaryKey;
                } else {
                    $this->oldFile = $this->model->$attribute;
                }
            }
            
            // remove and save file before update
            $this->attachEventHandler('onBeforeUpdate', array($this, 'beforeFileUpdate'));
            // save file after update
            //$this->attachEventHandler('onAfterUpdate', array($this, 'afterFileUpdate'));
            
        }
        
        //jeśli pole package_expire, to ustaw datę wygaśnięcia w odpowiadającym mu zamówieniu
        /*if($this->attribute == 'package_expire')
        {
        	Yii::app()->db->createCommand()
	            	->update('tbl_package_purchase', 
	                array('date_expire'=>$this->value),	                 
	                array('and', 'user_id='.$this->model->id, 'status='.Package::$_packagePurchaseStatus['PURCHASE_STATUS_CURRENT']));
        }*/	
//        if($this->modelClass == 'User' && $this->attribute == 'verified')
//        {
//            $this->model->verification_code = null;  
//            $this->setAttribute('verification_code', null);
//        }
        
        if ($this->fileMode) {
            // zapisujemy tylko UserFile
            $this->beforeFileUpdate();
            echo $this->newFileId;
        } else {
            parent::update();
        }
        
        if (Yii::app()->request->isAjaxRequest == false) {
            Yii::app()->controller->redirect(Yii::app()->user->returnUrl);
        }
    }
    
    
    /**
     * Usuwa stary plik i zapisuje nowy.
     * Wywołane przez zmianą pliku (po walidacji).
     */
    public function beforeFileUpdate() {
    	
        // remove old file
        if (!empty($this->oldFile)) {
            UserFile::model()->findByPk($this->oldFile)->delete();
        }
        
        // save new file
        if (!empty($this->fileData) and !empty($this->attribute)) {
            $file = new UserFile('create');
            
            if ($this->fileMode) {
                // FILE_MODE
                $attributes = array(
                    'class' => Yii::app()->request->getParam('ownerClass'),
                    'data_id' => Yii::app()->request->getParam('ownerPk'),
                    'hash' => $this->fileHash,
                    'extension' => $this->fileExtension,
                );
            } else {
                //MODEL_MODE
                $attributes = array(
                    'class' => $this->modelClass,
                    'data_id' => $this->primaryKey,
                    'hash' => $this->fileHash,
                    'extension' => $this->fileExtension,
                );
            }
            
            
            foreach(UserFile::getImageSizes() as $size=>$postfix) {
                $attributes[$size] = 1;
            }
            
            $file->setAttributes($attributes, false);
            
            // data saved separately, because it's not an attribute
            $file->data = $this->fileData;            
            $file->save();
            
            // id as value of updated data
            $attribute = $this->attribute;
            // swap value in model before saving
            // this solution if rather bad because
            // validations are fired earlier...
            if (!$this->fileMode) {
                $this->model->$attribute = $file->id;
            }
            //$this->value = $file->id;
        }
    }
    
    /**
     * Komunikat o błędzie.
     * @param string $msg Komunikat.
     * @throws CHttpException
     */
    public function error($msg)
    {
        if (Yii::app()->request->isAjaxRequest) {
            throw new CHttpException($this->errorHttpCode, $msg);
            /*throw new CHttpException(
                    $this->errorHttpCode, 
                    Yii::t('editable', 'Cannot update data.')
                    );*/
        } else {
            Yii::app()->user->setFlash('error', $msg);
            Yii::app()->controller->redirect(Yii::app()->user->returnUrl);
        }
    }
    
}
