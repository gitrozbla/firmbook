<?php

class LikedislikeModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'likedislike.models.*',
			'likedislike.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	public function defaultOnload($post_id,$post_type){
		$user_id = yii::app()->user->GetId();
		$criteria=new CDbCriteria;
		$criteria->select='*';  // only select the 'title' column
		$criteria->condition='post_id=:post_id and user_id=:user_id and post_type=:post_type';
		$criteria->params=array(':post_id'=>$post_id,':user_id'=>$user_id,':post_type'=>$post_type);
		$model = Likedislike::model()->find($criteria);
		
		//return '';
		if(count($model)==0){
			return false;
			//return Yii::t('like', 'Like');
		}
		elseif($model->status==0){
			return false;
			//return Yii::t('like', 'Like');
			//return 'Like';
		}
		else{
			return true;
			//return Yii::t('like', 'Unlike');
		}
	}
	
	public function countlikes($post_id,$post_type){
		$criteria=new CDbCriteria;
		$criteria->select='count(id) as count';  // only select the 'title' column
		$criteria->condition='post_id=:post_id and post_type=:post_type and status=:status';
		$criteria->params=array(':post_id'=>$post_id,':status'=>1,':post_type'=>$post_type);
		$model = Likedislike::model()->find($criteria);
		
		return $model->count;
	}
}
