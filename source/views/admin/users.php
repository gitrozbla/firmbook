<h1><i class="fa fa-users"></i> <?php echo Yii::t('user', 'Users'); ?></h1>

<?php $this->widget(
    'GridView',
    array(
        //'type' => 'striped bordered',
        'dataProvider' => $user->adminSearch(),
        'filter' => $user,
        //'ajaxUrl' => $this->createUrl('/admin/users'),
        'rowCssClassExpression' => '$data->active ? "" : "fade-medium";',
        'columns' => array(
            array(
    			'name'=>'id',    				
    			'headerHtmlOptions'=> array('style'=>'width:60px;')	            	
    		),
       		array(
   				'name' => 'register_source',   	
  				'headerHtmlOptions'=> array('style'=>'width:120px;'),	
        		'filter' => CHtml::dropDownList( 'User[register_source]', $user->register_source,
        			CHtml::listData( array( array( 'source' => User::REGISTER_SOURCE_FIRMBOOK, 'name' => 'Firmbook'), array( 'source' => User::REGISTER_SOURCE_CREATORS, 'name' => 'Creators') ), 'source', 'name' ),
        			//CHtml::listData( ProductGroups::model()->findAll( array( 'order' => 'group_title' ) ), 'group_title', 'group_title' ),
        			array( 'empty' => '-' )
       			),
  			),
            'username',
            'email',
            'active' => array(
                'name' => 'active',
                'value' => '$data->active '
                    . '? "'.Yii::t('user', 'yes').'"'
                    . ': "'.Yii::t('user', 'no').'"',
            	'headerHtmlOptions'=> array('style'=>'width:60px;')
            ),
            array(
    			'name'=>'package_id',
    			'value' => 'Package::badge($data->package->name, $data->package->css_name)',
    			'type'=>'raw',    				
    			'htmlOptions'=> array('style'=>'text-align: center;')
    		),
        	
    		array(
    			'name'=>'package_expire',
    			'value' => '$data->package_expire',   					  
    			'class' => 'bootstrap.widgets.TbEditableColumn',
				'headerHtmlOptions' => array('style' => 'width:110px'),
				'editable' => array(
					'type' => 'text',
					'url' => '/example/editable',
					'apply' => false	
				)          	
    		),
        	array(
        		'name'=>'creators_package_id',
        		'value' => '$data->packageCreators->name',
        		//'type'=>'raw',
        		'htmlOptions'=> array('style'=>'width:120px;')
        	),
        	array(
        		'name'=>'creators_package_expire',
        		'value' => '$data->creators_package_expire',
        		//'class' => 'bootstrap.widgets.TbEditableColumn',
        		'headerHtmlOptions' => array('style' => 'width:110px'),
        		/*'editable' => array(
        			'type' => 'text',
        			'url' => '/example/editable',
        			'apply' => false
        		)*/
        	),
			array(
				'name' => 'referrer',
				'value' => '$data->refererLink()',
				'type'=>'raw',
			),
    		/*array(
    				'name'=>'package_expire',
    				'value' => '$data->package_expire',    				    				
    				'htmlOptions'=> array('style'=>'width:120px;')	            	
    			),*/
        	array(
        		'name'=>'registered',
        		'value' => 'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->registered)',
        		'headerHtmlOptions'=> array('style'=>'width:95px;')
        	),
            array(
                'template' => '{view}&nbsp;&nbsp;&nbsp;{login}',
                'buttons' => array(
                    'view' => array(
                        'url' => 'Yii::app()->controller->createUrl('
                            . '"/user/profile",array("username"=>$data->username))',
                        'label' => Yii::t('user', 'Show profile'),
                        'icon' => 'fa fa-eye',
                    ),
                    'login' => array(
                        'url' => 'Yii::app()->controller->createUrl('
                            . '"/account/login_as",array("username"=>$data->username))',
                        'icon' => 'fa fa-custom-user-swap',
                        'label' => Yii::t('user', 'Login as user'),
                        'visible' => 'Yii::app()->user->id != $data->id && !User::checkRole($data->id)'.
                    		' && !Yii::app()->user->hasState("realUsername")'
                    ),
                ),
		'class' => 'bootstrap.widgets.TbButtonColumn',
                ),
        ),
    )
); ?>