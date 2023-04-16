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
     * Klasa modelu - właściciela (pole w tabeli tbl_file).
     * Potrzebne, gdy zdjęcie jeszcze nie istnieje.
     * @var type string
     */
    public $class = null;
    
    /**
     * Id modelu - właściciela (pole data_id w tabeli tbl_file).
     * Potrzebne, gdy zdjęcie jeszcze nie istnieje.
     * @var type int
     */
    public $dataId = null;
    
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
     * Tryb galerii.
     * Informuje JavaScript, że przy dodawaniu zdjęcia należy dodać nowe pole, 
     * a przy usuwaniu zdjęcia usunąć całe pole.
     * @var type boolean
     */
    public $galleryMode = false;
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'image';
    
    protected $image = null;
    
    
    
    // model posiada atrybut-referencję do obrazu
    // (używane przy relacji 1-1)
    const REFERENCE_MODE = 1;
    // zamiast modelu właściciela podany jest model zdjęcia
    // (używane przy bezpośredniej edycji zdjęcia bez referencji)
    const FILE_MODE = 2;
    /**
     * Mode:
     * referenceMode - 
     * @var type string
     */
    protected $editableImageMode = null;
            
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->type = 'image';
        
        // get UserFile
        $attribute = $this->attribute;
        if ($attribute) {
            // model a object with reference to UserFile
            $this->editableImageMode = self::REFERENCE_MODE;
            $this->image = UserFile::model()->findByPk($this->model->$attribute);
            if ($this->image) {
                $this->image = $this->image->generateUrl($this->imageSize);
            }
        } else if (get_class($this->model) == 'UserFile' || is_subclass_of($this->model, 'UserFile')) {
            // model is a UserFile object
            $this->editableImageMode = self::FILE_MODE;
            $this->image = $this->model->generateUrl($this->imageSize);
        }
        $this->text = $this->image;
        
        $sizes = UserFile::getImageSizes();
        
        
        if ($this->apply) {
            
            if  ($this->editableImageMode ==  self::REFERENCE_MODE) {
                $pk = $this->model->primaryKey;
                $params = array();
            } else {
                if (!empty($this->model->hash)) {
                    $pk = $this->model->hash;
                } else {
                    $pk = UserFile::generateRandomHash();
                }
                $params = array(
                    // parametry muszą być w params, aby 
                    // były wysyłane na serwer
                    'fileMode' => true, 
                    'fileHash' => $pk, 
                    'ownerClass' => $this->model->class,
                    'ownerPk' => $this->model->data_id,
                );
            }
            
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
                'noImageText' => Yii::t('editable', 'upload new image'),
                'notImageText' => Yii::t('editable', 'This file is not an image!'),
                'removeText' => Yii::t('editable', 'Remove?'),
                'reloadAlertText' => Yii::t('editable', 'A file is still being uploaded!'),
                //internal
                'value' => $this->image,
                'csrfToken' => Yii::app()->request->csrfToken,
                'ajaxOptions' => array('type'=>'POST'),
                'url' => $this->url,
                'attribute' => $this->attribute,
                'galleryMode' => $this->galleryMode,
                'pk' => $pk,
                'params' => $params,
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