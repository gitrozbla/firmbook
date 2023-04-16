<?php 
/**
 * Profil użytkownika.
 *
 * @category views
 * @package user
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php 
    $editor = Yii::app()->session['editor'];
    $userUpdateUrl = $this->createUrl('user/profile_update');
    $isAdmin = Yii::app()->user->isAdmin;
    $search = Search::model()->getFromSession();
    $action = $search->action;
    $webUser = Yii::app()->user;
    
    $itemUpdateUrl = $this->createUrl('user/partialupdate', array('model'=>'User'));
    //$fileUpdateUrl = $this->createUrl('user/partialupdate', array('model'=>'UserFile'));
	$secureAccess = $webUser->checkAccess('User.profile', array(
		'record' => $user,
		'attribute' => 'secureData',
	)) || Yii::app()->user->isAdmin;
    
?>

<div class="row">
    <div class="span9">
    	
    	<?php $this->renderPartial('/site/_editorButton'); ?>       
        
        <div class="pull-right margin-10"><?php echo $user->badgeCreators(); ?></div>
        
        <h1 style="background-color: <?php echo $user->packageCreators->color ?>">
            
            <i class="fa fa-user"></i> <?php echo $user->username; ?>
            
            <?php if (!($isAdmin && $user->active && !$editor) 
                    && $webUser->checkAccess('Creators.User.profile', array(
                        'record' => $user, 
                        'attribute' => 'active',
                        ))): ?>
                <span class="nowrap">
                    (<?php $this->widget('EditableToggle', array(
                        'model'     => $user,
                        'attribute' => 'active',
                        'url'       => $userUpdateUrl,
                        'apply'     => $isAdmin && $editor,
                        'red'       => 0,
                        'toggleSize'=> 'normal',
                        /*'fade'      => 0,
                        'fadeSelector' => '.content',*/
                        'source'    => array(
                            '0' => Yii::t('item', 'Inactive'), 
                            '1' => Yii::t('item', 'Active'),
                        )
                    )); ?>)
                </span>
            <?php endif; ?>
            
            <?php if ($isAdmin && $user->id != Yii::app()->user->id) : ?>
                <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'type' => 'warning',
                            'icon' => 'fa fa-custom-user-swap',
                            'label' => Yii::t('user', 'Login as user'),
                            'url' => Yii::app()->controller->createUrl(
                                    '/account/login_as',
                                    array("username"=>$user->username)
                                    ),
                        )
                ); ?>
            <?php endif; ?>
            
        </h1>
         <?php /*
        <div class="thumbnail pull-left right-30 bottom-10">
            
                <?php $this->widget('EditableImage', array(
                    'model'     => $user,
                    'attribute' => 'thumbnail_file_id',
                    'imageSize' => 'medium',
                    'imageAlt'  => $user->username,
                	'url'       => $itemUpdateUrl,                    
                    'apply'     => $editor,
                )); ?>
                
            </div>
        */?>
        
        <hr />

        <?php
			$attributes = array();
			if ($editor || $user->show_email || $user->id === Yii::app()->user->id) {
				$attributes []= array(
					'name' => 'email',
					'editableType' => 'email',  // to show as link
				);
			}
			if ($editor || $secureAccess || !empty($user->skype)) {
				$attributes []= array(
					'name' => 'skype',
					'editableType' => 'skype',  // to show as link
					'editableApply' => $editor,
				);
			}

			if (!empty($attributes)) {
				$this->widget('EditableDetailView', array(
					'data'       => $user,
					'editableUrl' => $userUpdateUrl,
					'editableAutoCheckAccess' => 'User.profile_update',
					//'editableApply' => $editor,
					'attributes' => $attributes
				));
			}
		?>


        <?php if ($webUser->checkAccess('Creators.User.profile', array(
                    'record' => $user, 
                    'attribute' => 'secureData',
                    ))
                || Yii::app()->user->isAdmin) : ?>
            <h2><?php echo Yii::t('user', 'Account'); ?></h2>
            <?php
                $packages = array(null=>Yii::t('packages', 'None'))
                        + Package::model()->findAllAsArray(true);
                foreach($packages as $key=>$value) {
                    if ($key != 0) {
                        $packages[$key] = Yii::t('packages', $value['name']);
                                /*'<span class="package-badge-'.$packages[$key]['css_name'].'">'
                                	.Yii::t('packages', $value['name'])
                                    //. Yii::t('package.name', $value['name'], null, 'dbMessages')
                                . '</span>';*/
                    }
                }
            ?>
            <?php $itemsCount = Item::model()->countUserItems($user->id); ?>
            <?php $this->widget('EditableDetailView', array(
                //'id' => 'user-detail',
                'data'       => $user,
                'editableUrl' => $userUpdateUrl,
                'editableAutoCheckAccess' => 'Creators.User.profile_update',
                'editableApply'=>$editor,
                'attributes' => array(
                	array(
                		'name' => 'username',                	
                	),
                    array(
                        'name' => 'password',
                        'editableType' => 'password',
                    ),
                    array(
                        'name' => 'creators_package_id',
                        'editableType' => 'select3',
                        'editableSource' => $packages,
                        'editable' => array(
                            'select3Escape' => false,
                            'emptytext' => Yii::t('packages', 'None'),
                            /*'validate' => $itemsCount > 200 
                                    ? 'js: function(value) {'
                                        . 'if (confirm("'
                                            .Yii::t('user', 
                                                    'This user have {itemsCount} objects. '
                                                    . 'This operation may take a while. Continue?',
                                                    array('{itemsCount}'=>$itemsCount))
                                                .'") == false) return "'
                                                    .Yii::t('user', 'Cancelled.')
                                                .'";'
                                    . '}'
                                    : null,*/
                            //'success' => 'js: function() {reload();}',
                        ),
                        //'editableApply'=>false,
                    ),
                    array(
                    	'name' => ' ',
                    	'value' => '<a class="btn btn-primary" href="'.$this->createUrl('/packages/show').'">'.Yii::t('user', 'Change package').'</a>',
                    	'type' => 'raw',
                    	'editableApply'=>false,
                    ),
                    array(
                        'name' => 'creators_package_expire',
                        'editableType' => 'date',
                        'editable' => array(
                            'emptytext' => Yii::t('user', 'no limit'),
                        ),
                        'editableApply'=>$editor && Yii::app()->user->isAdmin,
                    ),
                    array(
                        'name' => 'verified',
                        'editableType' => 'checkbox',
                    )
                ),
            )); ?>
            <?php if ($webUser->checkAccess('Creators.User.profile_update', array(
                    'record' => $user, 
                    'attribute' => 'package_id',
                    )) && $editor): ?>
                <span class="muted"><?php echo Yii::t('packages', 'Packages disables automaticaly during night hours.'); ?></span>
            <?php endif; ?>

            <h2><?php echo Yii::t('user', 'Personal details'); ?></h2>
            <?php $this->widget('EditableDetailView', array(
                //'id' => 'user-detail',
                'data'       => $user,
                'editableUrl' => $userUpdateUrl,
                'editableAutoCheckAccess' => 'Creators.User.profile_update',
                'editableApply'=>$editor,
                'attributes' => array(
                    array(
                        'name' => 'forename',
                    ),
                    array(
                        'name' => 'surname',
                    ),
                    array(
                        'name' => 'show_email',
                        'editableType' => 'toggle',
                    ),
                ),
            )); ?>
        <?php endif; ?>

        <?php /*if ($webUser->checkAccess('User.remove')) : ?>
            <?php $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'icon' => 'fa fa-times',
                        'label' => Yii::t('user', 'Remove user'),
                        'url' => $this->createUrl('remove', array('username'=>$user->username)),
                        'type' => 'danger',
                        'htmlOptions' => array(
                            'class' => 'pull-right confirm-box',
                            'data-message' => Yii::t('user', 
                                    'Are you sure you want to delete this user? '
                                    . 'All products, services and companies attached to this user will be removed too. '
                                    . 'You can also deactivate user to save his data.')
                        ),
                    )
        ); ?>
        <?php endif; */?>
        <?php /*if (!($webUser->checkAccess('User.profile', array(
                    'record' => $user, 
                    'attribute' => 'secureData',
                    ))
                || Yii::app()->user->isAdmin)) : ?>
        
        <hr />
        <div class="span3" style="margin-left: 0;">
        <?php 
            $type = 'company';
            $this->renderPartial('_itemsBoxList', compact(
                    'user', 'action', 'type'));
        ?>
        </div>
        <div class="span3">
        <?php 
            $type = 'product';
            $this->renderPartial('_itemsBoxList', compact(
                    'user', 'action', 'type')); 
        ?> 
        </div>       
        <div class="span3">
        <?php 
            $type = 'service';
            $this->renderPartial('_itemsBoxList', compact(
                    'user', 'action', 'type'));
        ?>
        </div>
        <?php endif; */?>
    </div>
    
    <div class="span3">
    	<?php /*$this->renderPartial('_itemsMenu', compact('user')); ?>
    	<?php if ($webUser->checkAccess('User.profile', array(
                    'record' => $user, 
                    'attribute' => 'secureData',
                    ))
                || Yii::app()->user->isAdmin) : ?>
        <?php 
            $type = 'company';
            $this->renderPartial('_itemsBoxList', compact(
                    'user', 'action', 'type'));
        ?>
        <?php 
            $type = 'product';
            $this->renderPartial('_itemsBoxList', compact(
                    'user', 'action', 'type')); 
        ?>
        
        <?php 
            $type = 'service';
            $this->renderPartial('_itemsBoxList', compact(
                    'user', 'action', 'type'));
        ?>
        <?php endif; */?>
    </div>
    
        
</div>