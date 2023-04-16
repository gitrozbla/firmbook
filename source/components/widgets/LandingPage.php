<?php

/**
 * Bloki landing page
 *
 * @author devasta
 * @category components
 * @package components/widgets
 */
class LandingPage extends CWidget {
    
    public $articlesGroup = '';
    
    public function run() {
        
        // dane
        $articles = Article::model()->findAll('alias LIKE "'.$this->articlesGroup.'%" AND visible=1');
        
        if (!empty($articles)) {
            echo '<div class="home-landing-page">';			
            for ($i=0; $i<count($articles);$i++) {
                $article = $articles[$i];
                $this->renderLandingPage($article);
            }
            echo '</div>';
        }
    }
    
    public function renderLandingPage($article) {
        switch($article->alias) {
            case 'landing-page-1a':
            case 'landing-page-1b':
                $this->renderLandingPage1($article);
                break;
            case 'landing-page-2a':
            case 'landing-page-2b':
            case 'landing-page-2c':
            case 'landing-page-2d':
                $this->renderLandingPage2($article);
                break;   
            case 'landing-page-3a':
            case 'landing-page-3b':
                $this->renderLandingPage3($article);
                break;
        }
    }
    
    public function renderLandingPage1($article) {
        if($article->alias == 'landing-page-1a') {            
            echo '<div class="'.$article->alias.'">';
            echo '  <div class="row-fluid">';
            echo '      <div class="span12">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages'); 
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        } elseif($article->alias == 'landing-page-1b') {
            echo '<div class="'.$article->alias.'">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages');
            echo '<a href="'.Yii::app()->getUrlManager()->createUrl('account/register').'" class="btn btn-primary btn-large">Zarejestruj</a>';
            echo '<a href="'.Yii::app()->getUrlManager()->createUrl('account/login').'" class="btn btn-primary btn-large">Zaloguj</a>';
            echo '</div>';            
        }
    }
    
    public function renderLandingPage2($article) {
        if($article->alias == 'landing-page-2a') {
            echo '<div class="'.$article->alias.'">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages');            
            echo '</div>';
        } elseif($article->alias == 'landing-page-2b') {
            echo '<div class="'.$article->alias.'">';
            echo '  <div class="row-fluid">';
            echo '      <div class="span6 landing-image">';            
            echo '      </div>';
            echo '      <div class="span6">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages'); 
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        } elseif($article->alias == 'landing-page-2c') {
            echo '<div class="'.$article->alias.'">';
            echo '  <div class="row-fluid">';
            echo '      <div class="span6">';       
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages');
            echo '      </div>';
            echo '      <div class="span6 landing-image">';             
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        } elseif($article->alias == 'landing-page-2d') {
            echo '<div class="'.$article->alias.'">';
            echo '  <div class="row-fluid">';
            echo '      <div class="span6 landing-image">';            
            echo '      </div>';
            echo '      <div class="span6">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages'); 
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }
    }
    
    public function renderLandingPage3($article) {
        if($article->alias == 'landing-page-3a') {
            echo '<div class="'.$article->alias.'">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages');            
            echo '  <div class="row-fluid">';
            echo '  </div>';
            echo '  <img src="/images/slider/1-5.jpg">';
            echo '  <div class="clearfix"></div>';
            echo '  <a href="'.Yii::app()->getUrlManager()->createUrl('account/register').'" class="btn btn-primary btn-large">Zarejestruj</a>';
            echo '  <a href="'.Yii::app()->getUrlManager()->createUrl('account/login').'" class="btn btn-primary btn-large">Zaloguj</a>';
            echo '</div>';
        } elseif($article->alias == 'landing-page-3b') {
            echo '<div class="'.$article->alias.'">';
            echo Yii::t('article.content', '{'.$article->alias.'}', array('{'.$article->alias.'}'=>$article->content), 'dbMessages');            
            echo '</div>';
        }
    }
}
