<?php
/**
 * Kontroler akcji dla załączników.
 * 
 * @category controllers
 * @package attachments
 * @author
 * @copyright (C) 2015
 */
class AttachmentsController extends Controller
{
    /**
     * Domyślna akcja.
     * @var string
     */
   // public $defaultAction = 'show';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        //return 'show';
    }    
		
	public function actionAdd($name=null)
	{
		$item = Item::model()->findByAttributes(array(), array(
				'condition'=>'t.alias=:alias',
				'params'=>array(
						':alias'=>$name,
				)));			
		
		if (!$item || !Yii::app()->user->checkAccess('Attachments.add', array('record'=>$item))) {
			throw new CHttpException(404);
			//throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
		}		
		
		switch($item->cache_type) {
			case 'p':				
				$type = 'product';
				$class = 'Product';
				$controller = 'products';
				break;
			case 's':
				$type = 'service';
				$class = 'Service';
				$controller = 'services';
				break;
			case 'c':
				$type = 'company';
				$class = 'Company';
				$controller = 'companies';
				
		}
		
		$this->setPageTitle(Yii::app()->name.' - '.Yii::t('product', 'product').' '.$item->name);		
		//$name = Yii::t('inv.item-'.$this->id, $name, null, 'dbMessages');
		
		// get company/product/service		
		$itemCPS = $class::model()->findByPk($item->id);			
		
		$attachment = new Attachment('create');

		if (isset($_POST['Attachment'])) {
			$attachment->attributes = $_POST['Attachment'];			
			$attachment->file = CUploadedFile::getInstance($attachment, 'file');
			
			if ($attachment->validate()) {
				$attachment->item_id = $item->id;
				$attachment->save();
				
				Yii::app()->user->setFlash('success', Yii::t('attachment', 'Attachment added.'));
				$this->redirect($this->createGlobalRouteUrl($controller.'/show', array('name' => $item->alias)));
			} else {
				Yii::app()->user->setFlash('error',
						Yii::t('attachment', 'Attachment not added!'));
			}			
		}
				
		// change context
		$search = Search::model()->getFromSession();
		$search->type = $type;
		$search->action = $item->sell ? 'sell' : 'buy';
		
		if ($item->category) {
			$this->breadcrumbs = $item->category->generateBreadcrumbs();
		} else {
			$this->breadcrumbs = array();
		}
		
		$this->breadcrumbs [$item->name] = $this->createGlobalRouteUrl($controller.'/show', array('name'=>$item->alias));
		$this->breadcrumbs [] = Yii::t('attachment', 'Add attachment file');	
		
		$this->render('addAttachment', compact('item', 'itemCPS', 'attachment', 'controller'));
	}
	
	public function actionUpdate($id)
	{
		
		$attachment = Attachment::model()
			->with(	array('item', 'item.category'))->findByPk($id);
		
		if (!$attachment || !Yii::app()->user->checkAccess('Attachments.update', array('record'=>$attachment->item))) {
			throw new CHttpException(404);
			//throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
		}
				
		$item = $attachment->item;
		
		switch($item->cache_type) {
			case 'p':		
				$type = 'product';
				$class = 'Product';
				$controller = 'products';				
				break;
			case 's':
				$type = 'service';
				$class = 'Service';
				$controller = 'services';				
				break;
			case 'c':
				$type = 'company';
				$class = 'Company';
				$controller = 'companies';
		}
		
		$itemCPS = $class::model()->findByPk($item->id);	
			
		$this->setPageTitle(Yii::app()->name.' - '.Yii::t('product', 'product').' '.$item->name);

		if (isset($_POST['Attachment'])) {
			$attachment->attributes = $_POST['Attachment'];
						
			if ($attachment->validate()) {
				$attachment->save();
	
				Yii::app()->user->setFlash('success', Yii::t('attachment', 'Attachment updated.'));
				$this->redirect($this->createGlobalRouteUrl($controller.'/show', array('name' => $item->alias)));
			} else {
				Yii::app()->user->setFlash('error',
				Yii::t('attachment', 'Attachment not updated!'));
			}
		}	
	
		// change context
		$search = Search::model()->getFromSession();
		$search->type = $type;
		$search->action = $item->sell ? 'sell' : 'buy';
	
		if ($item->category) {
			$this->breadcrumbs = $item->category->generateBreadcrumbs();
		} else {
			$this->breadcrumbs = array();
		}
	
		$this->breadcrumbs [$item->name] = $this->createGlobalRouteUrl($controller.'/show', array('name'=>$item->alias));		
		$this->breadcrumbs [] = Yii::t('attachment', 'Add attachment file');
	
		$this->render('addAttachment', compact('item', 'itemCPS', 'attachment', 'controller'));
	}	
	
	//AJAX - usunięcie okresu
	public function actionRemove($id)
	{
		if (!Yii::app()->request->isAjaxRequest)
			exit;		
	
		$file = Attachment::model()->findByPk($id);
		
		if (!$file || !Yii::app()->user->checkAccess('Movies.remove', array('record'=>$file->item))) {
			throw new CHttpException(404);
		}
		
		$file->delete();
	}
	
	public static function checkAccess($bizruleName, $params=array())
	{	
		switch ($bizruleName) {
			case 'add':
				if (isset($params['record'])) {
					$item = $params['record'];
					return $item->user_id == Yii::app()->user->id;
				} else
					return Yii::app()->user->isGuest ? false : true;
	
			case 'remove':
			case 'update':				
				$item = $params['record'];				
				return $item->user_id == Yii::app()->user->id;
	
			default:
				return false;
		}
	}
	
}