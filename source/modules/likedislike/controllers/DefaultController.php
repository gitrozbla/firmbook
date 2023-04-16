<?php
//Yii::import('application.components.Controller');
//class DefaultController extends application\components\Controller
class DefaultController extends Controller
{
	
    public $translationSourceDirect = null;

    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    /*public function allowedActions()
    {
            return 'likedislike';
    }*/

    public function actionIndex()
    {
            $this->render('index');
    }

    public function actionLikedislike(){

        $this->ajaxMode();
        
        $post_id = Yii::app()->request->getParam('post_id');
        $post_type = Yii::app()->request->getParam('post_type');
        $user_id = yii::app()->user->GetId();

        if($post_type == 'item')
        {
            $item = Item::model()->findByAttributes(array(), array(
                'condition'=>'t.id=:id and t.active',
                'params'=>array(
                    ':id'=>$post_id,
            )));
            if (!$item)        
                return;
        }    

        $criteria=new CDbCriteria;
        $criteria->select='*';  // only select the 'title' column
        $criteria->condition='post_id=:post_id and user_id=:user_id  and post_type=:post_type';
        $criteria->params=array(':post_id'=>$post_id,':user_id'=>$user_id,':post_type'=>$post_type);
        $model = Likedislike::model()->find($criteria);
        $scenario = 0;
        if(count($model)==0){
            //Create new like entry in table
            $model = new Likedislike();
            $model->post_id = $post_id;
            $model->user_id = $user_id;
            $model->post_type=$post_type;
            $model->status = 1;
            $displaynow = Yii::t('like', 'Unlike');
            //$displaynow = 'Unlike';
            $scenario = 1;
        }
        else if($model->status==0){
            //Already a entry exist update it to liked.
            $model->status = 1;
            $displaynow = Yii::t('like', 'Unlike');
            //$displaynow = 'Unlike';
            $scenario = 1;
        }
        else{ 
            //Update to unliked
            $model->status = 0;
            $displaynow = Yii::t('like', 'Like');
            //$displaynow = 'Like';
        }

        if($model->save()){
            $data['status'] = true;
            $data['displaytext'] = $displaynow;
            /*
             * Dodany kod
             */
            if ($post_type == 'item' && $scenario && $item->user->send_emails)
            {
                $emailData = array(
                    'email' => $item->user->email,
                    'name' => $item->user->forename . ' ' . $item->user->surname
                );
                $this->layout = '//layouts/mail';
                $userOrgLanguage = Yii::app()->user->language;
                $appOrgLanguage = Yii::app()->language;
                Yii::app()->user->language = $item->user->language;
                Yii::app()->language = $item->user->language;
                Yii::app()->mailer->systemMail(
                    $item->user->email,
                    Yii::t('likedislikeModule.main', 'Liking', [], null, $item->user->language),    
                    $this->render('likeEmail', compact('item'), true, true),        
                    $emailData
                );
                Yii::app()->user->language = $userOrgLanguage;
                Yii::app()->language = $appOrgLanguage;
            }    
        }
        else{
                $data['status'] = false;
        }
        $data['count'] = Yii::app()->getModule('likedislike')->countlikes($post_id,$post_type);
        echo json_encode($data);
    }
        
    // Użytkownicy którzy dodali do elisty
    public function actionInverseList($id)
    {
//    	echo '<br>DefaultController->actionInverseList';
        $model = new Likedislike();
        $model->post_id = $id;
        $model->post_type='item';
        $resource = Item::model()->with(array('thumbnail'))->findByAttributes(array(), array(
            'condition'=>'t.id=:id and t.active',
            'params'=>array(
                ':id'=>$id,
        )));

        if (!$resource) {
            throw new CHttpException(404, Yii::t('companies', 'Company does not exist or is inactive.'));
        }
        
        $dataProvider = $model->inverseDataProvider();
        $providerData = [];

        foreach($dataProvider->getData() as $row)    
    	{
            $file = new UserFile();
            $file->setAttribute('class', $row['class']);
            $file->setAttribute('data_id', $row['data_id']);
            $file->setAttribute('hash', $row['hash']);
            $file->setAttribute('extension', $row['extension']);
            $row['url'] = $file->generateUrl('small');
            $providerData[] = $row;
    	}
        $dataProvider->setData($providerData);
        $this->breadcrumbs = null;
        $itemView = '_inverseListItem';
    	$this->render('list', compact('model', 'dataProvider', 'resource', 'itemView'));
    }    

    public function actionLikedislike_2019(){
		
		$this->ajaxMode();
		//echo 'jest'; exit;
		$post_id = Yii::app()->request->getParam('post_id');
                $post_type = Yii::app()->request->getParam('post_type');
		$user_id = yii::app()->user->GetId();
		
		$criteria=new CDbCriteria;
		$criteria->select='*';  // only select the 'title' column
		$criteria->condition='post_id=:post_id and user_id=:user_id  and post_type=:post_type';
		$criteria->params=array(':post_id'=>$post_id,':user_id'=>$user_id,':post_type'=>$post_type);
		$model = Likedislike::model()->find($criteria);
		
		if(count($model)==0){
                        //Create new like entry in table
			$model = new Likedislike();
			$model->post_id = $post_id;
			$model->user_id = $user_id;
                        $model->post_type=$post_type;
			$model->status = 1;
			$displaynow = Yii::t('like', 'Unlike');
			//$displaynow = 'Unlike';
		}
		else if($model->status==0){
                        //Already a entry exist update it to liked.
			$model->status = 1;
			$displaynow = Yii::t('like', 'Unlike');
			//$displaynow = 'Unlike';
		}
		else{ 
                        //Update to unliked
			$model->status = 0;
			$displaynow = Yii::t('like', 'Like');
			//$displaynow = 'Like';
		}
		
		if($model->save()){
			$data['status'] = true;
			$data['displaytext'] = $displaynow;
		}
		else{
			$data['status'] = false;
		}
		$data['count'] = Yii::app()->getModule('likedislike')->countlikes($post_id,$post_type);
		echo json_encode($data);
	}
}