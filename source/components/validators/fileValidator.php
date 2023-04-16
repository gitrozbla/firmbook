<?php
/**
 * Walidator wysyłanego pliku.
 * 
 * Dodane wsparcie base64, używane przez EditableSaver
 * @see EditableSaver
 *
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class fileValidator extends CFileValidator
{
    /**
     * Weryfikuje plik po stronie serwera.
     * W przypadku nie przejścia testu dodaje błąd.
     * @see CValidator::addError()
     * @param object $object Instancja zawierająca atrybut.
     * @param string $attribute Atrybut pliku.
     */
    protected function validateAttribute($object, $attribute)
    {
        $base64ParamName = get_class($object).'['.$attribute.'.base64]';
        $data = Yii::app()->request->getParam($base64ParamName);
        if ($data != null) {
            // base 64 file
            $basePart = substr($data, 0, 64);
            if (substr($basePart, 0, 5) != 'data:' or 
                    substr($basePart, strpos($basePart, ';')+1, 7) != 'base64,') {
                $message = Yii::t('validators', 'File data wasn\'t sent correctly.');
                $this->addError($object,$attribute,$message);
                return;
            }
            
            // no 'is empty' check'. If base64 data does not exists, 
            // there might be data in $_FILES.
            
            // maxFiles not supported
            
            // mime
            if ($this->mimeTypes!==null) {
                if (is_string($this->mimeTypes)) {
                    $this->mimeTypes=preg_split('/[\s,]+/',strtolower($this->mimeTypes),-1,PREG_SPLIT_NO_EMPTY);
                }
                $mimeBegin = 5;
                $mime = substr($basePart, $mimeBegin, strpos($basePart, '/') - $mimeBegin);
                if (in_array($mime, $this->mimeTypes) == false) {
                    $message = $this->$wrongMimeType!==null ? $this->$wrongMimeType : 
                            Yii::t('validators','The file cannot be uploaded. Only files of these MIME-types are allowed: {mimeTypes}.');
                    $this->addError($object,$attribute,$message,array('{mimeTypes}'=>implode(', ',$this->mimeTypes)));
                }
            }
            
            // extension
            if ($this->types!==null) {
                if (is_string($this->types)) {
                    $this->types=preg_split('/[\s,]+/',strtolower($this->types),-1,PREG_SPLIT_NO_EMPTY);
                }
                $extensionBegin = strpos($basePart, '/')+1;
                $extension = substr($basePart, $extensionBegin, strpos($basePart, ';') - $extensionBegin);
                if (in_array($extension, $this->types) == false) {
                    $message = $this->$wrongType!==null ? $this->$wrongType : 
                            Yii::t('validators','The file cannot be uploaded. Only files with these extensions are allowed: {extensions}.');
                    $this->addError($object,$attribute,$message,array('{extensions}'=>implode(', ',$this->types)));
                }
            }
            
            // size
            if ($this->minSize != null or $this->maxSize != null) {
                $base64Size = strlen($data) - strpos($basePart, ',');
                $realSize = $base64Size / 4 * 3;
                if ($realSize < $this->minSize) {
                    $message = $this->tooSmall!==null ? $this->tooSmall : 
                            Yii::t('validators','The file is too small. Its size cannot be smaller than {limit} bytes.');
                    $this->addError($object,$attribute,$message,array('{limit}'=>$this->minSize));
                }
                if ($realSize > $this->maxSize) {
                    $message = $this->tooLarge!==null ? $this->tooLarge : 
                            Yii::t('validators','The file is too large. Its size cannot exceed {limit} bytes.');
                    $this->addError($object,$attribute,$message,array('{limit}'=>$this->maxSize));
                }
            }
            
            return;

        } else {
            return parent::validateAttribute($object, $attribute);
        }
    }
}
