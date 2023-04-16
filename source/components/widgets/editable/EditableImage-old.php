<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableImage extends EditableField 
{   
    public $imageAlt = '';
    
    public $imageSize = 'medium';
    
    /**
     * Maksymalna szerokość wysyłanego obrazu.
     * @var int
     */
    public $scaleMaxWidth = null;
    /**
     * Maksymalna wysokość wysyłanego obrazu.
     * @var int
     */
    public $scaleMaxHeight = null;
    /**
     * Format, do którego ma zostać przekonwertowany obraz.
     * @var string
     */
    public $convertTo = null;
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'image';
    
    protected $image = null;
            
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->type = 'image';
        
        // get UserFile
        $attribute = $this->attribute;
        if ($attribute) {
            $this->image = UserFile::model()->findByPk($this->model->$attribute);
            if ($this->image) {
                $this->image = $this->image->generateUrl($this->imageSize);
                $this->text = $this->image;
            }
        }
        
        $sizes = UserFile::getImageSizes();
        
        if ($this->apply) {
            $this->options = array_merge($this->options, array(
                // image
                'filesPath' => Yii::app()->file->filesPath, 
                'class' => get_class($this->model),
                'postfix' => $sizes[$this->imageSize],
                'imageAlt' => $this->imageAlt,
                // conversion
                'scaleMaxWidth' => $this->scaleMaxWidth,
                'scaleMaxHeight' =>  $this->scaleMaxHeight,
                'convertTo' => $this->convertTo,
                // messages
                'noImageText' => Yii::t('editable', 'upload image'),
                'notImageText' => Yii::t('editable', 'This file is not an image!'),
                'removeText' => Yii::t('editable', 'Remove?'),
                'reloadAlertText' => Yii::t('editable', 'A file is still being uploaded!'),
                //internal
                'value' => $this->image ,
                'csrfToken' => Yii::app()->request->csrfToken,
                'savenochange' => true, // important
                'onblur' => 'cancel',
                'ajaxOptions' => array('type'=>'POST'),
                'url' => $this->url,
                'attribute' => $this->attribute,
                'pk' => $this->model->primaryKey,
            ));
        }
        /*echo '<br/>EditableImage value<br/>';
        print_r($this->image);
        echo '<br/>EditableImage filesPath<br/>';
        print_r(Yii::app()->file->filesPath);*/
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        if (!empty($this->image)) {
            return Html::image($this->image, $this->imageAlt);
        } else {
            return '<span class="editable-image-empty"><i class="fa fa-picture-o"></i> '.Yii::t('editable', 'no image').'</span>';
        }  
    }
    
}