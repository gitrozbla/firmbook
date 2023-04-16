<?php

class PackagesController extends Controller
{
    public $defaultAction = 'comparison';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'comparison';
    }

    /**
     * Akcja domyślna.
     * Wyświetla listę pakietów.
     * @throws CHttpException
     */
    public function actionComparison()
    {
        /*$articleDescription = Article::model()->find(
                'alias="creators-description"'
        );*/
        
        $this->render('comparison');
    }
    
}