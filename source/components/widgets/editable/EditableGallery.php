<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableGallery extends CWidget 
{   
    public $model = null;   // owner of images
    
    public $attribute = null;   // relation - array of images
    
    public $url = null; // update url
    
    public $apply = true; // if false - render carousel
    
    public $add = true; // if true - add another image
    
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
        // get UserFile
        /*$attribute = $this->attribute;
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
        }*/
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function run() 
    {
        $model = $this->model;
        $attribute = $this->attribute;
        
        echo '<div id="'.($this->id).'">';
        
        if ($this->apply) {
            // editable
            
            foreach($model->$attribute as $file) {
                $this->widget('EditableImage', array(
                    'model'     => $file,
                    'imageAlt'  => $this->imageAlt,
                    'url'       => $this->url,
                    'galleryMode' => true,
                    'imageSize' => $this->imageSize,
                ));
            }
            
            if ($this->add) {
	            // last image will be empty (modelMode)
	            $blank = UserFile::model();
	            $blank->scenario = 'blank';
	            $blank->class = 'Item';
	            $blank->data_id = $this->model->primaryKey;
	            $this->widget('EditableImage', array(
	                'model'     => $blank,
	                'imageAlt'  => $this->imageAlt,
	                'url'       => $this->url,
	                'galleryMode' => true,
	                'imageSize' => $this->imageSize,
	            ));
            }
            
        } else if (!empty($model->$attribute)) {
            // carousel with thumbnails
            
            // carousel
            $items = array();
            $files = $model->$attribute;
            if (count($files) > 1) {
                foreach($files as $file) {
                    $items []= array(
                        'image' => $file->generateUrl($this->imageSize), 
                        'label' => $this->imageAlt
                    );
                }
                $this->widget(
                    'Carousel',
                    array(
                        'items' => $items,
                        'htmlOptions' => array(
                            'class' => 'item-gallery-carousel'
                        )
                    )
                );

                // thumbnails
                echo '<div class="item-gallery-thumbnails">';
                    foreach($files as $index=>$file) {
                        echo Html::link(
                            Html::image($file->generateUrl('small'), $this->imageAlt),
                                '#'.($this->id).' .item-gallery-carousel',
                                array(
                                   'data-slide-to' => $index
                                )
                        ); 
                    }
                echo '</div>';
            } else if (count($files) == 1) {
                $file = $files[0];
                echo Html::image($file->generateUrl($this->imageSize), $this->imageAlt);
            }
        }
        
        echo '</div>';
    }
    
}