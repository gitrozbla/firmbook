<?php

Yii::import('bootstrap.widgets.TbEditableField');

/**
 * Edytowalne pole.
 * 
 * Na bazie YIi-booster <- X-Editable for YII <- X-Editable
 * @see http://yiibooster.clevertech.biz/
 * @see http://x-editable.demopage.ru/
 * @see http://vitalets.github.io/x-editable/
 * EditableField współpracuje ściśle ze skryptem my_editable.js
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableField extends TbEditableField 
{   
    /**
     * Opcja zaznaczona na czerwono.
     * @var string
     */
    public $red = null;
    /**
     * Opcja zaznaczona na niebiesko.
     * @var string
     */
    public $blue = null;
    /**
     * Jeśli != null to przy wybranej wartości fragment 
     * strony staje się półprzeźroczysty.
     * @var string|null
     */
    public $fade = null;
    /**
     * Selektor fragmentu strony, który ma być półprzeźroczysty.
     * @var string
     */
    public $fadeSelector = null;
    /**
     * Selektor najbliższego fragmentu strony (rodzica), 
     * który ma być półprzeźroczysty.
     * @var string
     */
    public $closestFadeSelector = null;
    /**
     * Tekst, który jest wyświetlany, gdy pole jest puste.
     * @var string
     */
    public $emptytext = null;
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
     * Opcje edytora, zgodnie api X-Editable
     * @see http://vitalets.github.io/x-editable/
     * @var array
     */
    public $editorOptions = array();
    /**
     * Format daty.
     * @var string mysql_datetime/unix_timestamp
     */
    public $dateSourceFormat = 'mysql_datetime';// 'unix_timestamp';
    
    public $imageSize = 'l';
    
    public $toggleSize = 'mini';
    
    public $secondModel = null;
    
    public $treeMode = 'adjacencyList';
    
    /**
     * wartość pola.
     * @var string
     */
    protected $_value = null;
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() {
        
        // empty text
        if ($this->emptytext === null) {
            $this->emptytext = Yii::t('editable', 'no information');
        }
        
        if ($this->value) {
            $this->_value = $this->value;
        } else if ($this->text) {
            $this->_value = $this->text;
        } else if ($this->model) {
            if (get_class($this->model) == 'UserFile'
                    || is_subclass_of($this->model, 'UserFile')) {
                // this is managed file (UserFile)
                $this->attribute = 'data';
                $this->_value = $this->model;
            } else {
                $attribute = $this->attribute;
                $this->_value = $this->model->$attribute;
            }
        }
        
        if ($this->apply == false or 
                (!empty($this->model) and $this->model->isAttributeSafe($this->attribute) == false)) {
            // not safe or disabled
            $this->text = $this->renderField();
            $this->encode = false;
            $this->type = 'text';
        } else {
            
            if (!isset($this->options['params'])) {
                $this->options['params'] = array();
            }
            
            // Csrf token
            $this->options['params']['YII_CSRF_TOKEN'] = Yii::app()->request->csrfToken;
            
            // custom types
            switch($this->type) {
                case 'toggle':
                    Yii::app()->clientScript->registerCssFile('js/vendor/bootstrap-switch/stylesheets/bootstrap-switch.css');
                    Yii::app()->clientScript->registerScriptFile('js/vendor/bootstrap-switch/js/bootstrap-switch.min.js');
                    // no break
                case 'checkbox':
                    if (empty($this->source)) {
                        $this->source = array(
                            '0' => Yii::t('editable', 'No'), 
                            '1' => Yii::t('editable', 'Yes')
                        );
                    }
                    $attribute = $this->attribute;
                    $this->options = array_merge($this->options, array(
                        'red' => $this->red,
                        'blue' => $this->blue,
                        'fade' => $this->fade, 
                        'fadeClassPostfix' => $this->id,
                        // pass options direct to toggle input
                        'url' => $this->url,
                        'attribute' => $this->attribute,
                        'value' => $this->_value,
                        'pk' => $this->model->primaryKey,
                        'csrfToken' => Yii::app()->request->csrfToken,
                        'size' => $this->toggleSize,
                    ));
                    if (!empty($this->fadeSelector)) {
                        $this->options['fadeSelector'] = $this->fadeSelector;
                    }
                    if (!empty($this->closestFadeSelector)) {
                        $this->options['closestFadeSelector'] = $this->closestFadeSelector;
                    }
                    break;
                    
                case 'password':
                    $this->text = '********'; // for safety
                    break;
                
                case 'image':
                    $attribute = $this->attribute;
                    if (!is_string($this->_value)) {
                        $this->text = $this->_value->generateUrl($this->imageSize);
                    }
                    $this->options = array_merge($this->options, array(
                        'noImageText' => Yii::t('editable', 'upload image'),
                        'notImageText' => Yii::t('editable', 'This file is not an image!'),
                        'removeText' => Yii::t('editable', 'Remove?'),
                        'filesPath' => '',//Yii::app()->file->filesPath, 
                        'savenochange' => true, // important
                        'ajaxOptions' => array('type'=>'POST'),
                        // pass options direct to toggle input
                        'url' => $this->url,
                        'class' => get_class($this->model),
                        'attribute' => $this->attribute,
                        'value' => $this->text,
                        'pk' => $this->model->primaryKey,
                        'csrfToken' => Yii::app()->request->csrfToken,
                        'scaleMaxWidth' => $this->scaleMaxWidth,
                        'scaleMaxHeight' =>  $this->scaleMaxHeight,
                        'convertTo' => $this->convertTo,
                    ));
                    
                    //$this->options['showbuttons'] = false;    // will not work
                    break;
                
                case 'date':
                    Yii::app()->clientScript->registerScriptFile('js/datepicker-fix.js',
                            ClientScript::POS_HEAD);    // fix
                    break;
                
                case 'datetime':
                    Yii::app()->log('TODO: unix timestamp support + timezones');
                    $model = $this->model;
                    $attribute = $this->attribute;
                    if ($model->$attribute != '0000-00-00 00:00:00') {
                        $this->text = Yii::app()->dateFormatter->format('yyyy-MM-dd H:mm', CDateTimeParser::parse($model->$attribute, 'yyyy-M-d H:mm:ss')); // format fix
                    } else {
                        $this->text = $this->emptytext;
                    }
                    break;
                    
                case 'editor':
                    Yii::app()->bootstrap->assetsRegistry->registerPackage('ckeditor');
                    $this->options['onblur'] = 'ignore';
                    $this->options['editorOptions']['emptytext'] = '<p>abc</p>';
                    $this->options['editorOptions'] = array_merge(
                            $this->editorOptions,
                            array(
                                'language' => Yii::app()->language,
                            )
                    );
            }
            
        }
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() {
        
        $value = $this->_value;
        
        switch($this->type) {
            case 'link':
                if (empty($value)) {
                    return $this->emptytext;
                }
                if (substr($value, 0, 7) != 'http://') {
                    $url = 'http://'.$value;
                } else {
                    $url = $value;
                }
                return Html::link(
                    $value,
                    $url,
                    array('target'=>'_blank')
                );
            
            case 'email':
                return Html::link(
                    $value,
                    'mailto:'.$value
                );
             
            case 'password':
                return '********';
            
            case 'toggle':
            case 'checkbox':
                if (empty($this->source)) {
                    $this->source = array(
                        '0' => Yii::t('editable', 'No'), 
                        '1' => Yii::t('editable', 'Yes')
                    );
                }
                
                if ($value) {
                    $result = '<i class="icon-check"></i> '.$this->source[$value];
                } else {
                    $result = '<i class="icon-check-empty"></i> '.$this->source[$value];
                }
                
                if ($this->red !== null and $this->red == $value) {
                    $result = '<span class="red">'.$result.'</span>';
                } else if ($this->blue !== null and $this->blue == $value) {
                    $result = '<span class="blue">'.$result.'</span>';
                }
                
                if ($this->fade !== null and $this->fade == $value) {
                    if (!empty($this->fadeSelector) or !empty($this->closestFadeSelector)) {
                        Yii::app()->clientScript->registerScript('editable-fade-'.($this->id), 
                                (!empty($this->fadeSelector) ? 
                                    "$('".$this->fadeSelector."').addClass('fade-medium');" : "")
                                .(!empty($this->closestFadeSelector) ? 
                                    "$('#editable-".$this->id."').closest('".$this->closestFadeSelector."').addClass('fade-medium');" : "")
                                );
                        $result = '<span id="editable-'.$this->id.'">'.$result.'</span>';
                    } else {
                        $result = '<span class="fade-medium">'.$result.'</span>';
                    }
                }
                
                return $result;
                
            case 'image':
                if (!empty($value)) {
                    //$path = (Yii::app()->file->filesPath).get_class($model).'/'.$attribute.'/'.$model->getPrimaryKey().'.'.$value;
                    if (is_string($value)) {
                        $path = (Yii::app()->file->filesPath) . '/' . $value;
                    } else {
                        $path = $value->generateUrl($this->imageSize);
                    }
                    return '<img src="'.$path.'" alt="'.$this->attribute.'" />';
                } else {
                    return '<span class="editable-image-empty"><i class="icon-picture"></i> '.Yii::t('editable', 'no image').'</span>';
                }
            
            case 'select2':
                if (!empty($value) and isset($this->source[$value])) {
                    return $this->source[$value];
                } else {
                    return $this->emptytext;
                }
                
            case 'date':
                if (!empty($value) and $value != '0000-00-00') {
                    return $value;
                } else {
                    return $this->emptytext;
                }
                
            case 'datetime':
                $dateFormatter = Yii::app()->dateFormatter;
                if ($this->dateSourceFormat == 'mysql_datetime') {
                    $value = CDateTimeParser::parse($value, 'yyyy-M-d H:mm:ss');
                }
                
                // php 5.2 required here
                $timezone = new DateTimeZone(Yii::app()->timezone);
                $timeOffset = $timezone->getOffset(new DateTime('now', $timezone)) / 3600;
                $timezoneText = ' (UTC+'.$timeOffset.')';
                
                if (!empty($value) and $value != 0) {
                    return $dateFormatter->format('yyyy-MM-dd H:mm', $value).$timezoneText;// format fix
                } else {
                    return $this->emptytext;
                }
                
            case 'tree':
                if ($this->treeMode == 'adjacencyList') {
                    // not implemented
                    return '';
                } else {    // nested set
                    return $this->emptytext;
                }
                
            default:
                if (!empty($value)) {
                    return $value;
                } else {
                    return $this->emptytext;
                }
        }
        
    }
    
    /**
     * Tworzy opcje Javascript i zapisuje je do EditableField::$options.
     * Dodana obsługa języka w DatePicker.
     */
    public function buildJsOptions() {
        if (!isset($this->options['datetimepicker']['language'])) {
            $this->options['datetimepicker']['language'] = substr(Yii::app()->getLanguage(), 0, 2);
        }
        parent::buildJsOptions();
    }
    
    /**
     * Dodaje skrypt do html.
     * @return string Skrypt.
     */
    public function registerClientScript()
    {
        $script = parent::registerClientScript();
        
        // ajax support
        if (Yii::app()->request->isAjaxRequest) {
            echo Html::tag('div', array(
                'class' => 'ajax-script',
                'data-script' => $script,
                ));
        }
        
        return $script;
    }
    
    /**
     * Rejestruje zasoby wtyczki X_Editable (przenosi do folderu assets i dodaje do html).
     */
    public function registerAssets() {
        
        // screw assets! js moved to public_html/js
        /*$am = Yii::app()->getAssetManager();
        $assetsUrl = $am->publish(
                Yii::getPathOfAlias('application.components.widgets.assets'), 
                false, 
                -1, 
                YII_DEBUG
                );*/
        
        Yii::app()->clientScript->registerScriptFile(
            'js/my_editable.js',
            CClientScript::POS_END
        );
        
        Yii::app()->clientScript->registerCssCrushFile(
            Yii::app()->basePath.'/../css/editable.css', '', true
        );
        
        parent::registerAssets();
    }
    
    /**
     * Generuje selektor css na podstawie id widgetu.
     * @return string Selektor.
     */
    public function getSelector() {
        return ($this->id).'_'.parent::getSelector();
    }
    
}