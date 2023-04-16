<?php
/**
 * Kontroler wyświetlania podstron.
 * 
 * @category controllers
 * @package pages
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class PagesController extends Controller 
{
    public function init()
    {
        return parent::init();
    }
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'show';
    }
    
    /**
     * Wyświetla podstronę.
     * @param string $name Nazwa podstrony
     * @throws CHttpException
     */
    public function actionShow($name, $scroll_to=null)
    {
        // translate name
        $alias = 'creators-'.Yii::t('inv.CreatorsModule.article', $name);
        if ($alias == $name) {
            $alias = Yii::t('inv.article.alias', $name, null, 'dbMessages');
        }
        // translate param back (for multilanguage url)
        $_GET['name'] = substr($alias, 9);
        Yii::app()->urlManager->registerParamToTranslate(
                'name', array('Article', 'translate'));
        
        $article = Article::model()->find(
                'alias=:alias and visible=1', array(
            ':alias' => $alias
        ));
        
        if (!$article) {
            throw new CHttpException(404);
        }
        
        $title = Yii::t('article.title', $article['title'], array(), 'dbMessages');
        $this->pageTitle = /*Yii::app()->name.' - '.*/$title;
        $this->breadcrumbs = array($title);
        
        $this->render('//pages/display', compact('article'));
    }

}
