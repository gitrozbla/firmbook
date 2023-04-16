<?php
/**
 * Kontroler akcji dla firmy.
 * 
 * @category controllers
 * @package company
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class CompaniesController extends Controller
{
    /**
     * Domyślna akcja.
     * @var string
     */
    public $defaultAction = 'list';
    
    /**
     * Wyświetla listę firm.
     * @throws CHttpException
     */
   	public function actionList()
    {
		//survey - sonda
		$surveyForm = new SurveyForm();
		if (isset($_POST['SurveyForm'])) {
			$surveyForm->attributes = $_POST['SurveyForm'];
			if($surveyForm->validate())	{
				$this->layout = 'mail';
				$user = Yii::app()->user->getModel();
				Yii::app()->mailer->ClearAttachments();
				Yii::app()->mailer->systemMail(
						//'wojciech.alaszewski@gmail.com',	// test mail
						Yii::app()->params['admin']['email'],
						Yii::t('survey', 'Survey form'),
						$this->render('surveyMail', compact('surveyForm', 'user'), true),
						array(
								'email' => $user->email,
								'name' => $user->forename.' '.$user->surname
						)
				);

				Yii::app()->user->setFlash('success', Yii::t('surveyForm', 'Message sent. Thank you!'));
				$this->redirect('list');
			}
		}

		
        $search = Search::model();
        $search->username = Yii::app()->user->name;
        $search->type = 'c';
        $search->action = null;
        
        // ajax support
        if (Yii::app()->request->getParam('ajax') == 'grid-view') {
            $this->renderPartial('_list', compact('search'));
            exit();
        }

        $this->render('list', compact('search', 'surveyForm'));
    }
    
    public function actionShow($name)
    {
        $name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');

        // get company
        $company = Company::model()->with(
                array('item'=>array(
                        'alias'=>'i'
                    ),
                    'item.category'
                ))->findByAttributes(array(), array(
                    'condition'=>'i.alias=:alias and i.user_id=:user_id',
                    'params'=>array(
                        ':alias'=>$name,
                        ':user_id'=>Yii::app()->user->id,
                )));
        
        if (!$company) {
            throw new CHttpException(404, Yii::t('CreatorsModule.companies', 'Company does not exist.'));
        }
        
        $this->setPageTitle(Yii::app()->name.' - '.$company->item->name);
        
        // auto generate configuration
        $website = CreatorsWebsite::model()->findByPk($company->item_id);
        if (!$website) {
            $website = new CreatorsWebsite('create');
            $website->company_id = $company->item_id;
            $website->generatePages();
            $website->validateWithPages();
            $website->saveWithPages();
        }
        
        // ajax support
        if (Yii::app()->request->getParam('ajax') == 'grid-view') {
            $this->renderPartial('_fileList', array('company' => $company->item_id));
            exit();
        }
        
        $this->render('show', compact('company'));
    }
    
    
    public function renderThumbnail($data)
    {
        $file = UserFile::model()->findByPk($data->thumbnail_file_id);
        if ($file) {
            return Html::image($file->generateUrl("small"), "");
        }
    }
    
    public function renderBackButton()
    {
        return Html::link(
                Yii::t('CreatorsModule.companies', 'Go back to list'),
                $this->createUrl('companies/list')
        );
    }
    
    public function actionDelete($id)
    {
        $file = CreatorsFile::model()->with('company.item.user')->findByPk($id);
    	if (!$file) {
            throw new CHttpException(404);
    	} else if ($file->company->item->user->id != Yii::app()->user->id) {
            throw new CHttpException(403);
        }
    	
        if ($file->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('CreatorsModule.file', 'File removed.'));
            
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }
}
