<?php
/**
 * Kontroler akcji dla firmy.
 * 
 * @category controllers
 * @package company
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class GeneratorController extends Controller
{
    public $previewMode = true;
    public $website = null;
    public $page = null;
    public $path = null;
    public $noCustomContent = false;
    public $previewLayout = null;
    public $sideContent = false;
    
    /**
     * Domyślna akcja.
     * @var string
     */
    public $defaultAction = 'editor';
    
    public function actionEditor($id)
    {
    	echo '<br>GeneratorController->actionEditor';
        $website = $this->getWebsite($id);
        $website->pushEmptyPage();
        
        // collect user input data
        if (isset($_POST['CreatorsWebsite'])) {
            $website->attributes = $_POST['CreatorsWebsite'];
            $website->pagesAttributes = $_POST['CreatorsPage'];
            $website->favicon = CUploadedFile::getInstance($website, 'favicon');
            $website->logo = CUploadedFile::getInstance($website, 'logo');
            $website->header_bg = CUploadedFile::getInstance($website, 'header_bg');
            
            // if it is ajax validation request
            if (isset($_POST['ajax'])) {
                echo ActiveForm::validate($website);
                Yii::app()->end();
            }
            
            if ($website->validateWithPages() && $website->saveWithPages()) {
                Yii::app()->user->setFlash('success', 
                    Yii::t('CreatorsModule.editor', 'Configuration saved.'));
                $this->redirect($this->createUrl('generator/editor', array(
                    'id' => $website->company_id
                )));
            } else {
                Yii::app()->user->setFlash('error', 
                    Yii::t('CreatorsModule.editor', 'Configuration not saved!'));
            }
        }
        
        $errors = array();
        if ($website->hasErrors()) {
            $errors []= Html::errorSummary($website);
        }
        foreach($website->pages as $page) {
            if ($page->hasErrors()) {
                $errors []= Yii::t('CreatorsModule.page', 'Page').' '.$page->position.': '
                        .Html::errorSummary($page);
            }
        }
        if (!empty($errors)) {
            Yii::app()->user->setFlash('error', implode('<br />', $errors));
        }
       
        if ($website->incompleteMeta()) {
            Yii::app()->user->setFlash('warning', 
                    Yii::t('CreatorsModule.editor', 'Some metadata fields are empty!'));
        }
        
        $this->noContainer = true;
        $this->noPartials = true;
        
        $this->pageTitle = $website->meta_title;
        /*if ($website->favicon) {  // unsafe feature
            $this->customFavicon = 'files/CreatorsWebsite/'.$website->company_id.'/'.$website->favicon;
        } else {
            $this->customFavicon = null;
        }*/
        
        $this->render('editor', compact('website'));
    }
    
    
    public function actionPreview($id, $creators_page='/', $layout=null)
    {
    	echo '<br>GeneratorController->actionPreview';
        $this->ignoreRequest = true;
        $this->previewMode = true;
        $this->previewLayout = $layout;
        
        $website = $this->getWebsite($id);
        $website->pushEmptyPage();
        
        $creators_page = str_replace(':', '/', $creators_page);
        
        $website->render($creators_page, $layout);
    }
    
    public function actionBuild($id) 
    {
        $website = $this->getWebsite($id);
        
        if ($website->build($website)) {
            Yii::app()->user->setFlash('success', Yii::t('CreatorsModule.generator', 'Website has been generated without errors.'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('CreatorsModule.generator', 'An error has occurred. Please try again.'));
        }
        
        $this->redirect($this->createUrl('companies/show', array(
            'name' => $website->company->item->alias
        )));
    }
    
    
    public function getWebsite($id)
    {
        $website = CreatorsWebsite::model()->with('company.item')->findByPk($id);
        if (!$website) {
            throw new CHttpException(404, Yii::t('companies', 'Company does not exist.'));
        }
        $this->website = $website;
        if ($website->company->item->user_id != Yii::app()->user->id) {
            throw new CHttpException(403);
        }
        return $website;
    }
    
    
    // generator
    
    //public $mapped
    
    public $mappedPages = array();
    protected $mappedPagesIndex = array();
    
    public function mapPage($url) 
    {
        if (!empty($url) && $url[0]=='/') {
            $url = substr($url, 1);
        }
        
        if ($this->previewMode) {
            $url = str_replace('/', ':', $url);
            if ($this->previewLayout) {
                return $this->createUrl('generator/preview', array(
                    'id' => $this->website->company_id,
                    'layout' => $this->previewLayout,
                    'creators_page' => $url
                ));
            } else {
                return $this->createUrl('generator/preview', array(
                    'id' => $this->website->company_id,
                    'creators_page' => $url
                ));
            }
        } else {
            if (!isset($this->mappedPagesIndex[$url])) {
                $this->mappedPages[] = $url;
                $this->mappedPagesIndex[$url] = true;
                
            }
            
            $path = '';
            if (!empty($this->path) && $this->path != '.') {
                $count = substr_count($this->path, '/');
                for($i=0; $i<=$count; $i++) {
                    $path .= '../';
                }
            }
            return $path.$url.'.html';
        }
    }
    
    public $mappedFiles = array();
    protected $mappedFilesIndex = array();
    
    public function mapFile($url, $path=null) 
    {
        if ($this->previewMode) {
            return $url;
        } else {
            if (!isset($this->mappedFiles[$url])) {
                // filename only
                $parts = pathinfo($url);
                $filename = $parts['basename'];
                
                // find conflicts 
                if (!isset($this->mappedFilesIndex[$filename])) {
                    $this->mappedFilesIndex[$filename] = 1;
                    $this->mappedFiles[$url] = ($path ? $path.'/' : '').$filename;
                } else {
                    $this->mappedFilesIndex[$filename] ++;
                    $this->mappedFiles[$url] = $this->mappedFilesIndex[$filename].'_'.$filename;
                }
                
            }
            
            $path = '';
            if (!empty($this->path) && $this->path != '.') {
                $count = substr_count($this->path, '/');
                for($i=0; $i<=$count; $i++) {
                    $path .= '../';
                }
            }
            return $path.'files~/'.$this->mappedFiles[$url];
        }
    }
}
