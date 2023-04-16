<?php
/**
 * Model konfiguracji generowania strony.
 * 
 * @category models
 * @package user
 */
class CreatorsWebsite extends ActiveRecord
{
    public $faviconDelete = false;
    public $logoDelete = false;
    public $header_bgDelete = false;
    
    public $header_heightAuto;
    
    protected $oldFiles = array();
    
	public static function getAllSocialNetworks()
	{
		return array(
			'facebook' => 'Facebook',
			'googlePlus' => 'Google+',
			'twitter' => 'Twitter',
			'pinterest' => 'Pinterest',
			'linkedin' => 'Linkedin',
			'email' => 'Email'
		);
	}
    
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
        return '{{creators_website}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'company_id';    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array
     */
    public function relations()
    {
        return array(
            'company' => array(self::BELONGS_TO, 'Company', 'company_id', 'together'=>true),
            'pages' => array(self::HAS_MANY, 'CreatorsPage', 'website_id', 'order'=>'position ASC', 'together'=>true),
            'homepage' => array(self::BELONGS_TO, 'CreatorsPage', 'home_page_id', 'together'=>true),
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
            'meta_title' => Yii::t('CreatorsModule.website', 'Title'),
            'meta_description' => Yii::t('CreatorsModule.website', 'Description'),
            'meta_keywords' => Yii::t('CreatorsModule.website', 'Keywords'),
            
            'layout' => Yii::t('CreatorsModule.website', 'Layout'),
            'theme' => Yii::t('CreatorsModule.website', 'Theme'),
            'favicon' => Yii::t('CreatorsModule.website', 'Thumbnail'),
            'faviconDelete' => Yii::t('CreatorsModule.website', 'Remove'),
            
            'name' => Yii::t('CreatorsModule.website', 'Name'),
            'name_color' => Yii::t('CreatorsModule.website', 'Name text color'),
            'logo' => Yii::t('CreatorsModule.website', 'Logo'),
            'logoDelete' => Yii::t('CreatorsModule.website', 'Remove'),
            'slogan' => Yii::t('CreatorsModule.website', 'Slogan'),
            'slogan_color' => Yii::t('CreatorsModule.website', 'Slogan text color'),
            'header_text_align' => Yii::t('CreatorsModule.website', 'Header text align'),
            'header_bg' => Yii::t('CreatorsModule.website', 'Header background'),
            'header_bgDelete' => Yii::t('CreatorsModule.website', 'Remove'),
            'extended_header_bg' => Yii::t('CreatorsModule.website', 'Extended header background'),
            'header_bg_brightness' => Yii::t('CreatorsModule.website', 'Header background brightness'),
            'header_height' => Yii::t('CreatorsModule.website', 'Header height'),
            'header_heightAuto' => Yii::t('CreatorsModule.website', 'Auto'),
            'header_social_icons' => Yii::t('CreatorsModule.website', 'Social icons in header'),

            'footer_text' => Yii::t('CreatorsModule.website', 'Footer text'),
			'footer_social_icons' => Yii::t('CreatorsModule.website', 'Social icons in footer'),

			'social_icons_title' => Yii::t('CreatorsModule.website', 'Button text'),
			'social_icons_networks' => Yii::t('CreatorsModule.website', 'Networks'),
        );   
    }
    
    /**
     * Lista reguł walidacji.
     * @return array
     */
    public function rules()
    {
    	return array(
            array('meta_title', 'length', 'max'=>70),
            array('meta_description', 'length', 'max'=>150),
            array('meta_keywords', 'length', 'max'=>150),
            
            array('layout', 'in', 'range'=>array_keys(self::getLayouts()), 'allowEmpty'=>false),
            array('theme', 'in', 'range'=>array_keys(self::getThemes()), 'allowEmpty'=>false),
            array('favicon', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png, ico', 'maxSize'=>5000000,
                'safe'=>true, 'allowEmpty'=>true),
            array('faviconDelete', 'safe'),
            
            array('name', 'length', 'max'=>70),
            array('name_color', 'match', 'pattern'=>'/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/'),
            array('logo', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>5000000,
                'safe'=>true, 'allowEmpty'=>true),
            array('logoDelete', 'safe'),
            array('slogan', 'length', 'max'=>150),
            array('slogan_color', 'match', 'pattern'=>'/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/'),
            array('header_text_align', 'in', 'range'=>array_keys(self::getTextAlign()), 'allowEmpty'=>false),
            array('header_bg', 'userFileValidator', 'types'=>'jpg, jpeg, gif, png', 'maxSize'=>5000000,
                'safe'=>true, 'allowEmpty'=>true),
            array('header_bgDelete', 'safe'),
            array('extended_header_bg', 'boolean'),
            array('header_bg_brightness', 'numerical', 'min'=>-1.0, 'max'=>1.0),
            array('header_height', 'numerical', 'integerOnly'=>true, 'min'=>10, 'max'=>600),
            array('header_heightAuto', 'safe'),
			array('header_social_icons', 'boolean'),
            
            array('home_page_id', 'numerical', 'integerOnly'=>true),
            
            array('footer_text', 'length', 'max'=>1500),
            array('footer_text', 'filter', 'filter'=>array($obj=new CHtmlPurifier(),'purify')),
			array('footer_social_icons', 'boolean'),

			array('social_icons_title', 'length', 'max'=>30),
			array('social_icons_networks', 'safe'),
            
            //defaults
            array('meta_title', 'default', 'value'=>$this->company->item->name, 'on'=>'create'),
            
            array('layout', 'default', 'value'=>'Layout_1', 'on'=>'create'),
            array('theme', 'default', 'value'=>'flatly', 'on'=>'create'),
            
            array('name', 'default', 'value'=>$this->company->item->name, 'on'=>'create'),
            
            array('footer_text', 'default', 'value'=>'Copyright &copy; '.date("Y").' '.$this->company->item->name, 'on'=>'create')
        );
    }
    
    public function afterFind()
    {
        $this->oldFiles = array(
            'favicon' => $this->favicon,
            'logo' => $this->logo,
            'header_bg' => $this->header_bg,
        );
        
        $this->header_heightAuto = ($this->header_height === null);
		
		if (!empty($this->social_icons_networks)) {
			$this->social_icons_networks = explode(',', $this->social_icons_networks);
		} else if ($this->social_icons_networks === null) {	// init
			$this->social_icons_networks = array('facebook', 'googlePlus', 'email');
		}
        
        return parent::afterFind();
    }
    
    public function beforeSave()
    {
        // delete/save files
        foreach($this->oldFiles as $attribute=>$oldValue) {
            $uploadedFileObject = $this->$attribute;
            $fileUploaded = !is_string($uploadedFileObject) && (
                    get_class($uploadedFileObject) == 'CUploadedFile' 
                    || is_subclass_of($uploadedFileObject, 'CUploadedFile')
                    ) && $uploadedFileObject->getName() != null; 
            
            $fileDeleteAttribute = $attribute.'Delete';
            // remove old file
            if ($this->$fileDeleteAttribute==true || $fileUploaded==true) {
                if ($oldValue) {
                    $fileObject = Yii::app()->file->set('CreatorsWebsite/'.$this->company_id.'/'.$oldValue);
                    if ($fileObject->exists) {
                        $removeEmptyDirs = !$fileUploaded;
                        $fileObject->delete(true, $removeEmptyDirs);
                    }
                }
            } else {
                $this->$attribute = $oldValue;
            }
            // new file
            if ($fileUploaded) {
                $filename = Func::randomString(16).'_'.$attribute.'.'.$uploadedFileObject->getExtensionName();
                Yii::app()->file->createDir(0755, 'CreatorsWebsite/'.$this->company_id);
                $uploadedFileObject->saveAs('CreatorsWebsite/'.$this->company_id.'/'.$filename);
                $this->$attribute = $filename;
            }
        }
        
        if ($this->header_heightAuto) {
            $this->header_height = null;
        }
		
		if (is_array($this->social_icons_networks)) {
			$this->social_icons_networks = implode(',', $this->social_icons_networks);
		}
        
        return parent::beforeSave();
    }
    
    public function afterValidate()
    {
        foreach($this->oldFiles as $attribute=>$oldValue) {
            if ($this->getError($attribute)) {
                $this->$attribute = $oldValue;  // revert image on error
            }
        }
        
        return parent::afterValidate();
    }
    
    public function beforeDelete()
    {
        // remove generated websites
        $files = CreatorsFile::model()->findAllByAttributes(array(
            'company_id' => $this->company_id,
        ));
        foreach($files as $file) {
            $file->delete();
        }
        
        // remove files
        $files = UserFile::model()->findAllByAttributes(array(
            'class' => 'CreatorsWebsite',
            'data_id' => $this->company_id,
        ));
        foreach($files as $file) {
            $file->delete();
        }
        
        return parent::beforeDelete();
    }
    
    public function generatePages()
    {
        $attributes = array(
            array(
                'position' => 1,
                'type' => 'news',
                'title' => Yii::t('CreatorsModule.page', 'Home'),
                'alias' => Yii::t('CreatorsModule.page', 'home'),
                'content' => Yii::t('CreatorsModule.page', 'Welcome to our website!')
            ),
            array(
                'position' => 2,
                'type' => 'about',
                'title' => Yii::t('CreatorsModule.page', 'About company'),
                'alias' => Yii::t('CreatorsModule.page', 'about-company')
            ),
            array(
                'position' => 3,
                'type' => 'products',
                'title' => Yii::t('CreatorsModule.page', 'Our products'),
                'alias' => Yii::t('CreatorsModule.page', 'our-products')
            ),
            array(
                'position' => 4,
                'type' => 'services',
                'title' => Yii::t('CreatorsModule.page', 'Services we offer'),
                'alias' => Yii::t('CreatorsModule.page', 'services-we-offer')
            ),
            array(
                'position' => 5,
                'type' => 'contact',
                'title' => Yii::t('CreatorsModule.page', 'Contact'),
                'alias' => Yii::t('CreatorsModule.page', 'contact')
            )
        );
        
        $pages = array();
        foreach($attributes as $pageAttributes) {
            $page = new CreatorsPage();
            $page->scenario = 'create';
            $page->website_id = $this->company_id;
            $page->setAttributes($pageAttributes, false);
            $pages []= $page;
        }
        $this->pages = $pages;
    }
    
    public function pushEmptyPage()
    {
        $newPage = new CreatorsPage();
        $newPage->website_id = $this->company_id;
        $newPage->scenario = 'empty';
        $newPage->position = count($this->pages) + 1;
        $this->pages = array_merge($this->pages, array($newPage));
    }
    
    public function setPagesAttributes($values, $safeOnly=true) 
    {
        // index
        $pagesIndex = array();
        foreach($this->pages as $key=>$page) {
            $pagesIndex[$page->id] = $key;
        }
        // set attributes
        foreach($values as $pageValues) {
            if (isset($pageValues['id']) && isset($pagesIndex[$pageValues['id']])) {
                $this->pages[$pagesIndex[$pageValues['id']]]->setAttributes($pageValues, $safeOnly);
            }
        }
    }
    
    public function validateWithPages()
    {
        if ($result = $this->validate() == false) {
            return $result;
        }
        
        foreach($this->pages as $page) {
            if ($result = $page->validate() == false) {
                return $result;
            }
        }
        
        return true;
    }
    
    public function saveWithPages()
    {
        $result = true;
        
        $result = $this->save();
        
        // index and sort
        $pagesIndex = array();
        foreach($this->pages as $key=>$page) {
            $pagesIndex[$page->position.'-'.$page->id] = $key;
        }
        ksort($pagesIndex);
        
        // save with fixing positions
        $position = 1;
        foreach($pagesIndex as $key=>$index) {
            $page = $this->pages[$index];
            if ($page->website_id == $this->company_id) {
                switch($page->scenario) {
                    case 'remove':
                        if ($page->isNewRecord == false) {
                            $page->delete();
                        }
                        break;
                    case 'empty':
                        break;
                    default:
                        $page->position = $position;
                        $result = $page->save() && $result;
                        $position++;
                }
            }
        }
        
        //$result = $this->save() && $result;
        
        return $result;
    }
    
    
    public function incompleteMeta()
    {
        return empty($this->meta_title) 
            || empty($this->meta_description) 
            || empty($this->meta_keywords);
    }
    
    public static function getLayouts($translated=false)
    {
        if ($translated) {
            $text = Yii::t('CreatorsModule.website', 'Layout').' ';
        } else {
            $text = 'Layout ';
        }
        return array(
            'Layout_1' => $text.'1',
            'Layout_2' => $text.'2'
        );
        
        return $result;
    }
    
    public static function getThemes($translated=false)
    {
        $result =  array(
            'amelia' => 'Amelia',
            'bootstrap' => 'Bootstrap',
            'cerulean' => 'Cerulean',
            'cosmo' => 'Cosmo',
            'cyborg' => 'Cyborg',
            'flatly' => 'Flatly',
            'readable' => 'Readable',
            'simplex' => 'Simplex',
            'slate' => 'Slate',
            'spacelab' => 'Spacelab',
            'superhero' => 'Superhero',
            'united' => 'United',
            'yournal' => 'Yournal',
        );
        return $result;
    }
    
    public static function getTextAlign($translated=false)
    {
        if ($translated) {
            return array(
                'left' => Yii::t('CreatorsModule.website', 'Left'),
                'right' => Yii::t('CreatorsModule.website', 'Right'),
                'center' => Yii::t('CreatorsModule.website', 'Center'),
            );
        } else {
            return array(
                'left' => 'Left',
                'right' => 'Right',
                'center' => 'Center',
            );
        }
    }
    
    public function getPagesForMenu($current=null, $allowEmptyTitle=false)
    {
        $result = array();
        $controller = Yii::app()->controller;
        
        foreach($this->pages as $page) {
            if ($page->scenario == 'empty') {
                $title = '';
                $alias = '~empty';
            } else {
                $title = $page->title
                        ? Html::encode($page->title) 
                        : '<i class="faded">['.Yii::t('CreatorsModule.page', 'no name').']</i>';
                $alias = $page->alias 
                        ? $page->alias
                        : '~alias_'.$page->id;
            }
            $result []= array(
                'label' => $title,
                'url' => $controller->mapPage($alias),
                'active' => $current == $page->id,
                'itemOptions' => array(
                    'class' => 'page-'.$page->id,
                    'style' => $title ? null : 'display:none;'
                )
            );
        }
        return $result;
    }
    
    
    public function render($url='/', $layout=null, $return=false)
    {
        if ($layout == null) {
            $layout = $this->layout;
        }
        $controller = Yii::app()->controller;
        $controller->layout = '/generator/layouts/'.$layout;
        
        // parse url
        if (!empty($url) && $url[0]=='/') {
            $url = substr($url, 1);
        }
        if ($url[0] == '~') {
            // special page alias
            $pagePart = $url;
            $params = array();
        } else {
            $parts = explode('~', $url);
            if (count($parts) > 1) {
                $tilde = $parts[1];
            } else {
                $tilde = null;
            }
            $parts = explode('/', $parts[0]);
            $pagePart = $parts[0];
            array_shift($parts);
            $params = $parts;
        }
        if (empty($pagePart)) {  // homepage
            if ($this->home_page_id) {
                $page = $this->homepage;
            } else {
                $page = $this->pages[0];
            }
        } else if ($pagePart == '~empty') {
            $page = new CreatorsPage();
            $page->scenario = 'empty';
        } else {
            $page = CreatorsPage::model()->findByAttributes(array(
                'alias' => $pagePart,
                'website_id' => $this->company_id
            ));
        }
        $controller->website = $this;
        $controller->page = $page;
        $controller->path = dirname($url);
        if (!empty($params)) {
            $controller->noCustomContent = true;
        } else {
            $controller->noCustomContent = false;
        }
        
        return $controller->render('/generator/page', compact('page', 'params', 'tilde'), $return);
    }
    
    public function build()
    {
        $pages = array(
            '/' => 'index.html',
        );
        
        // prepare temp dir
        $ds = DIRECTORY_SEPARATOR;
        $dirname = 'generator_temp_'.substr((md5(Yii::app()->params['key']['systemSalt'].$this->company_id)), 16);
        $generatorPath = 'CreatorsWebsite'.$ds.$this->company_id.$ds.$dirname;
        $file = Yii::app()->file->set($generatorPath);
        //var_dump($path);exit();
        $file->delete(); // with purge
        $file->createDir();
        
        // render pages
        $controller = Yii::app()->controller;
        $controller->layout = '/generator/layouts/'.$this->layout;
        $controller->previewMode = false;
        // index page
        $result = $this->render('/', null, true);
        $file = Yii::app()->file->set($generatorPath.'/index.html');
        if(!$file->create()){
            return false;
        }
        $file->setContents($result);
        // other pages
        for ($i = 0; $i < count($controller->mappedPages); $i++) {
            // liczba stron usupelnia sie przy każdej iteracji
            // dlatego nie uzywamy foreach
        //foreach($pages as $page=>&$filename) {
            $alias = $controller->mappedPages[$i];
            $result = $this->render($alias, array(), true);
            $file = Yii::app()->file->set($generatorPath.'/'.$alias.'.html');
            if(!$file->create()){
                return false;
            }
            $file->setContents($result);
        }
        
        // compress
        $file = Yii::app()->file->set('CreatorsFile'.$ds.$this->company_id);
        $file->createDir();
        
        $zip = new ZipArchive();
        $archiveFilename = $this->company->item->alias.'_'.Func::randomString(16);
        $basePath = (Yii::app()->basePath).$ds.'..'.$ds;
        $archivePath = $basePath.(Yii::app()->file->filesPath)
                .$ds.'CreatorsFile'.$ds.$this->company_id.$ds
                .$archiveFilename.'.zip';
        if ($zip->open($archivePath, ZipArchive::CREATE)!==TRUE) {
            return false;
        }
        
        // add html
        $pagesPath = $basePath
                .(Yii::app()->file->filesPath).$ds
                .$generatorPath.$ds;
        $zip->addFile($pagesPath.'index.html', 'index.html');
        foreach($controller->mappedPages as $alias) {
            $filename = $alias.'.html';
            $zip->addFile($pagesPath.$filename, $filename);
        }
        // add assets
        $files = $controller->mappedFiles;
        //$zip->addEmptyDir('files');
        foreach($files as $source=>$dest) {
            $zip->addFile($basePath.$source, 'files~'.$ds.$dest);
        }
        
        $zip->close();
        
        // add package to list
        $archive = new CreatorsFile();
        $archive->scenario = 'create';
        $archive->company_id = $this->company_id;
        $archive->filename = $archiveFilename;
        $archive->generated = date("Y-m-d H:i:s");
        if (!$archive->save()) {
            return false;
        }
        
        // cleanup
        //$file = Yii::app()->file->set($generatorPath);
        //$file->delete(); // with purge
        
        return true;
    }
    
}
