<?php
/**
 * Kontroler z akcjami użytkownika.
 * 
 * @category controllers
 * @package user
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class UserController extends Controller 
{
    public $defaultAction = 'profile';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return strings
     */
    public function allowedActions()
    {
        return 'profile, items';//, partialupdate
    }
    
    /**
     * Profil użytkownika.
     */
    public function actionProfile($username=null)
    {    	
    	
        if (!empty($username)) {
            $user = User::model()->findByAttributes(array(
                'username' => $username
            ));
            if (!$user) {
                throw new CHttpException(404, Yii::t('user', 'User does not exists.'));
            }
        } else {
            if (Yii::app()->user->isGuest) {
                $this->requestCreatorsLogin();
            }
            
            $user = Yii::app()->user->getModel();
        }
        
        if (Yii::app()->user->checkAccess('Creators.User.profile_update', array('record'=>$user))) {        	
            $this->editorEnabled = true;
        }
        
        $this->breadcrumbs = array();
        $this->breadcrumbs []= Yii::t('user', 'User');
        //$this->breadcrumbs []= Yii::t('user', 'Profile');
        //$this->breadcrumbs []= Yii::t('user', $user->username);
        //$this->breadcrumbs [$user->username]= Yii::app()->createUrl('user/items', array('type'=>$type, 'username'=>$user->username));
        //$this->breadcrumbs []= Yii::t('user', ucfirst($type));
        
        $this->render('profile', compact('user'));
    }
    
    public static function checkAccess($bizruleName, $params=array())
    {    	
        $params += array(
            'record' => null,
            'attribute' => null,
        );
        $class = get_class($params['record']);
        
        switch ($bizruleName) {
            case 'profile':
                switch($class.'.'.$params['attribute']) {
                    case 'User.secureData':
                        return Yii::app()->user->id == $params['record']->id;
                        
                    case 'User.':
                    case '.':
                        // view profile action is allowed
                        return true;
                        
                    default:
                        // unknown data
                        return false;
                }                
                
            case 'profile_update':
                if ($params['record'] == null) {
                    $c = Yii::app()->controller;
                    switch($c->id.'.'.$c->action->id) {
                        case 'user.profile_update':
                            $request = Yii::app()->request;
                            $pk = $request->getParam('pk');
                            $class = 'User';
                            // not necessary
                            //$params['record'] = User::model()->findByPk($pk);
                            $params['attribute'] = $request->getParam('name');
                            break;

                        case 'user.profile':
                            // record required
                        default:
                            // unknown action
                            return false;
                    }
                } else {
                    $pk = $params['record']->id;
                }
                
                switch($params['attribute']) {
                	case 'username':
                    case 'email':
					case 'skype':
                    case 'password':
                    case 'forename':
                    case 'surname':
                    case 'show_email':
                    //case 'package_id': tylko admin może zmienić pakiet
                        return $pk == Yii::app()->user->id;
                        
                    case null:
                        // for editor button
                    	//return $params['record']->username == Yii::app()->user->username;
                        return $params['record']->username == Yii::app()->user->name;
                        
                    default:
                        return false;
                        
                }
                
            default:
                // unknown action
                return false;
        }
    }
    
    
    public function actionDesktop()
    {
        $this->render('desktop');
    }
    
    public function actionProfile_update()
    {
        $es = new EditableSaver('User');
        $es->update();
    }
    
    /**
     * Modyfikuje informacje o produkcie.
     * ID podane jest w $_GET/$_POST.
     * @param type $model Klasa modelu.
     */
    public function actionPartialupdate($model='User')
    {
    	$es = new EditableSaver($model);
    	$es->scenario = 'update';
    	$es->update();
    }
    
    public function actionRemove($username)
    {
        User::model()->findByAttributes(array(
            'username' => $username
        ))->delete();
        
        if ($username == Yii::app()->user->name) {
            $this->redirect($this->createUrl('account/logout'));
        } else {
            $this->redirect($this->createUrl('admin/users'));
        }
    }
    
    public function actionItems($username=null, $type)
    {
    	if($type!=='companies' && $type!=='products' && $type!=='services')
    		throw new CHttpException(404);
    	
    	if (!empty($username)) {
    		$user = User::model()->findByAttributes(array(
    				'username' => $username
    		));
    		if (!$user) {
    			throw new CHttpException(404, Yii::t('user', 'User does not exists.'));
    		}
    	} else {
    		if (Yii::app()->user->isGuest) {
    			$this->requestLogin();
    		}
    	
    		$user = Yii::app()->user->getModel();
    	}    	
    	
    	// editor
    	/*if (Yii::app()->user->checkAccess('Companies.add', array('record'=>$company))) {
    		$this->editorEnabled = true;
    	}*/
    	
    	$this->breadcrumbs = array();
    	//$this->breadcrumbs [Yii::t('user', 'User')]= Yii::app()->createUrl('user/items', array('type'=>$type, 'username'=>$user->username));
    	//$this->breadcrumbs [$user->username]= Yii::app()->createUrl('user/items', array('type'=>$type, 'username'=>$user->username));
    	
    	if($type=='companies')
    		$this->breadcrumbs [] = Yii::t('navigation', 'My companies');
    	elseif($type=='products')
    		$this->breadcrumbs [] = Yii::t('navigation', 'My products');    			
    	elseif($type=='services')
    		$this->breadcrumbs [] = Yii::t('navigation', 'My services');
    			
    	//$this->breadcrumbs []= Yii::t('user', ucfirst($type));
    	
    	$this->render('items', compact('user', 'type'));
    }
}