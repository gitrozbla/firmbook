<?php
/**
 * Kontroler z akcjami newslettera.
 * 
 * @category controllers
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class NewsletterController extends Controller
{
    /**
     * Domyślna akcja.
     * @var string
     */
    public $defaultAction = 'posts';
    /**
     * Layout dla wszystkich akcji.
     * @var string
     */
    public $layout = '//newsletter/layout';
    
    /**
     * Lista wiadomości newsletter.
     */
    public function actionPosts()
    {
        $post = new NewsletterPost('update');
        
        if (isset($_GET['ajax']) && $_GET['ajax'] == 'post-list') {
            $this->renderPartial('posts', compact('post'));
            exit();
        }
        
        $this->render('posts', compact('post'));
    }
    
    /**
     * Tworzy pustą wiadomość newsletter do uzupełnienia.
     */
    public function actionWrite_post()
    {
        $post = new NewsletterPost('createEmpty');
        $post->datetime = date('Y-m-d H:i:s');
        $post->save();
        
        $this->listViewFirstPage('post-list');
        
        $this->endCoolAjax();
    }
    
    /**
     * Usuwa wiadomość newsletter z historii (również te niewysłane).
     * @param string $id ID wiadomości newsletter.
     */
    public function actionRemove_post($id)
    {
        NewsletterPost::model()->findByPk($id)->delete();
        
        $this->listViewRefresh('post-list');
        
        $this->endCoolAjax();
    }
    
    /**
     * Modyfikuje wiadomość newsletter.
     * ID podane jest w $_GET/$_POST.
     */
    public function actionUpdate_post()
    {
        Yii::import('EditableSaver', true);
        $es = new TbEditableSaver('NewsletterPost');
        $es->scenario = 'update';
        $es->update();
    }
    
    /**
     * Rozsyła wiadomość z newslettera.
     * @param  string $id ID wiadomości newslettera.
     * @throws CHttpException
     */
    public function actionSend($id)
    {
        $post = NewsletterPost::model()->findByPk($id);
        if (!$post) {
            throw new CHttpException(404);
        }
        $post->setScenario('send');
        if ($post->validate()) {
            
            set_time_limit(600); // 10m
            
            // get readers
            $readers = Yii::app()->db->createCommand()
                    ->select('email')
                    ->from('tbl_newsletter_reader')
                    ->where("email<>''")
                    ->queryColumn();
            
            // send emails
            $this->layout = 'mail';
            Yii::app()->mailer->ClearAttachments();
            Yii::app()->mailer->systemMail(
                    Yii::app()->params['admin']['email'],
                    $post->subject,
                    $this->render('postEmail', compact('post'), true),
                    null,
                    true,
                    $readers
            );
            
            // update post info
            $post->sent = 1;
            $post->datetime = date('Y-m-d  H:i:s');
            $post->save();
            
            Yii::app()->user->setFlash('success', Yii::t('newsletter', 'Post has been sent.'));
        } else {
            $errors = $post->getErrors();
            $error = reset($errors);
            /*$attribute = key($errors);
            $attributeLabels = $post->attributeLabels();*/
            Yii::app()->user->setFlash('error', /*$attributeLabels[$attribute].': '.*/reset($error));
        }
        
        
        $this->redirect($this->createUrl('newsletter/posts'));
    }
    
    
    
    /**
     * Lista czytelników.
     */
    public function actionReaders()
    {
        $reader = new NewsletterReader('search');
        
        if (isset($_GET['ajax']) && $_GET['ajax'] == 'reader-list') {
            $this->renderPartial('readers', compact('reader'));
            exit();
        }
        
        $this->render('readers', compact('reader'));
    }
    
    /**
     * Edycja czytelnika (adres email).
     * Stary email podany w $_GET/$_POST.
     */
    public function actionUpdate_reader()
    {
        Yii::import('EditableSaver', true);
        $es = new TbEditableSaver('NewsletterReader');
        $es->scenario = 'update';
        $es->update();
    }
    
    /**
     * Dodaje czytelnika w postaci pustego pola do wypełnienia.
     */
    public function actionAdd_reader()
    {
        $reader = NewsletterReader::model()->findByPk('');
        if ($reader) {
            Yii::app()->user->setFlash('info', Yii::t('newsletter', 'Please fill undefined e-mail below.'));
            $this->endCoolAjax();
        }
        
        $reader = new NewsletterReader('createEmpty');
        $reader->email = '';
        $reader->save();
        
        $this->gridViewFirstPage('reader-list');
        
        $this->endCoolAjax();
    }
    
    /**
     * Usuwanie czytelnika.
     * @param string $email Email użytkownika.
     */
    public function actionRemove_reader($id)
    {
        NewsletterReader::model()->findByPk($id)->delete();
        
        $this->gridViewRefresh('reader-list');
        
        $this->endCoolAjax();
    }
}
