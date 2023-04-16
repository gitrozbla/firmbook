<?php
/**
 * Model pliku użytkownika.
 * 
 * @category models
 * @package user
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class UserFile extends ActiveRecord
{
    /**
     * Plik (wysyłany, format $_FILE, base64).
     * Używany przez EditableField do wysyłania pliku.
     * @var type null|string|Object
     */
    public $data;
    
    // original needs to be first to proper folder creation
    // @see UserFile::beforeSave()
    static protected $imageSizes = array(
        'original' => '',
        'small' => '_s',
        'medium' => '_m',
        'large' => '_l',
    );
    
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
        return '{{file}}';
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
     * @return array
     */
    public function relations()
    {
        return array(
            'thumbnail_owner' => array(self::HAS_ONE, 'Item', 'thumbnail_file_id'),
        );
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
        return array(
            // update
            array('data', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>500000,
                'safe'=>true, 'allowEmpty'=>false, 'on'=>'update'),
            array('data', 'safe', 'on'=>'blank'),
        );
    }
    
    /**
     * Generuje i zwraca url do pliku.
     * @param string $size Rozmiar w przypadku miniatury zdjęcia (xs, s, m l).
     * @return string Url do pliku.
     */
    public function generateUrl($size='original')
    {
        $sizes = UserFile::getImageSizes();
        if (!empty($this->hash)) {
            return (Yii::app()->file->filesPath).'/'.
                $this->class.'/'.
                $this->data_id.'/'.
                $this->hash.
                $sizes[$size].
                '.'.$this->extension;
        } else {
            return null;
        }
    }
    
    public function beforeSave()
    {
        if (!$this->isNewRecord) {
            // any existing file cannot be apdated
            // to update file, you need to remove 
            // old and insert new record
            // this is for proper refreshing in browsers
            return false;
        } else {
            if ($this->data == null) {
                return false;   // wymagane dane do pliku
            } else if (is_string($this->data)) {
                // original image
                $originalImageObject = imagecreatefromstring($this->data);
            } else {
                //$tempPath = Yii::app()->file->filesPath.'/'.Func::randomString(16);
                //move_uploaded_file($this->data['tmp_name'], $tempPath);
                $tempPath = reset($this->data['tmp_name']);
                // extension
                if (isset($this->data['type'])) {
                    $type = $this->data['type'];
                    if (is_array($type)) $type = reset($type);
                } else {
                    $path = $_FILES['image']['name'];
                    if (is_array($path)) $path = reset($path);
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                }
                // original image
                switch($type) {
                    case 'jpg':
                    case 'jpeg':
                    case 'image/jpg':
                    case 'image/jpeg':
                        $originalImageObject = imagecreatefromjpeg($tempPath);
                        break;
                    case 'png':
                    case 'image/png':
                        $originalImageObject = imagecreatefrompng($tempPath);
                        break;
                    case 'gif':
                    case 'image/gif':
                        $originalImageObject = imagecreatefromgif($tempPath);
                        break;
                }
            }
            
            // get dimensions from setting
            $allDimensions = Yii::app()->db->createCommand()
                    ->select('image_sizes')
                    ->from('tbl_settings')
                    ->queryScalar();
            $allDimensions = unserialize($allDimensions);
            
            // get target sizes
            $sizes = $this->getFilePaths();
                    
            // original dimensions and ratio
            $originalImageWidth = imagesx($originalImageObject);
            $originalImageHeight = imagesy($originalImageObject);
            $originalImageRatio = $originalImageWidth / $originalImageHeight;
            // create dirs
            Yii::app()->file->set(dirname(reset($sizes)))->createDir();
            // generate all sizes
            foreach ($sizes as $size=>$filepath) {
                if ($size == 'original'){
                    $resizedImageObject = $originalImageObject;
                } else {
                    // resized image
                    $dimensions = $allDimensions[$size];
                    $reizedImageRatio = $dimensions['w'] / $dimensions['h'];
                    if ($reizedImageRatio < $originalImageRatio) {
                        $outputWidth = $dimensions['w'];
                        $outputHeight = intval($dimensions['w'] / $originalImageRatio);
                    } else {
                        $outputWidth = intval($dimensions['h'] * $originalImageRatio);
                        $outputHeight = $dimensions['h'];
                    }

                    $resizedImageObject = imagecreatetruecolor($outputWidth, $outputHeight);
                    imagecopyresampled(
                            $resizedImageObject, $originalImageObject,
                            0, 0, 0, 0, 
                            $outputWidth, $outputHeight, 
                            $originalImageWidth, $originalImageHeight
                            );
                }
                
                // save!
                switch($this->extension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($resizedImageObject, $filepath, 85);
                        break;
                    case 'png':
                        imagepng($resizedImageObject, $filepath);
                        break;
                    case 'gif':
                        imagegif($resizedImageObject, $filepath);
                        break;
                }

                if ($size != 'original'){
                    imagedestroy($resizedImageObject);
                }
            }
            imagedestroy($originalImageObject);
            unset($this->data);
            
            /*if ($tempPath) {
                unlink($tempPath);
            }*/
            
            return true;
        }
        
    }
    
    public function beforeDelete()
    {
        $file = Yii::app()->file;
        foreach ($this->getFilePaths() as $filepath) {
            $fileObject = $file->set($filepath);
            if ($fileObject->exists) {
                $fileObject->delete();
            }
        }
        
        return true;
    }
    
    public function getFilePaths()
    {
        // remove file and all thumbnails
        $path = $this->class.'/'.
                $this->data_id.'/'.
                $this->hash;
        $extension = '.'.$this->extension;
        
        // sizes
        $sizes = array();
        foreach(UserFile::$imageSizes as $size=>$postfix) {
            $sizes[$size] = array('postfix'=>$postfix, 'exist'=>$this->$size);
        }
        
        $files = array();
        foreach($sizes as $key=>$size) {
            if ($size['exist']) {
                $files[$key] = $path.$size['postfix'].$extension;
            }
        }

        return $files;
    }
    
    public static function getImageSizes()
    {
        return UserFile::$imageSizes;
    }
    
    public static function generateRandomHash() {
        $text = '';
        $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $possibleLength = strlen($possible);

        for($i=0; $i<16; $i++) {
            $text .= $possible[rand(0, $possibleLength-1)];
        }

        return $text;
    }
    
}
