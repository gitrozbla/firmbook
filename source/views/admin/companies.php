<?php 
/**
 * Admin - lista pakietów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */
 
?>
<h1><i class="fa fa-building-o"></i> Firmy</h1>
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	//'id'=>'project-grid-'.uniqid(),
    	'id'=>'project-grid',
    	//'rowCssClassExpression'=>'"items[]_{$data->item_id}"',
    	'selectableRows'=>1,    
    	//'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    //'type' => 'striped',
	    'dataProvider' => $company->adminSearch(),
    	'filter' => $company,
	    'responsiveTable' => true,
	    //'template' => "{items}",
    	'enableSorting' => true,
    	'rowCssClassExpression' => '$data->item->active ? "" : "fade-medium";',
	    'columns' => array(	            
    			array(
    				'name'=>'item_id',    				
    				'htmlOptions'=> array('style'=>'width:60px;')	            	
    			),
    			array(
    				'name'=>'item_name',
    				'value' => '$data->item->name',    				
    				//'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),
    			array(
    				'name'=>'item_date',
    				'value' => '$data->item->date',    				
    				'htmlOptions'=> array('style'=>'width:95px;')	            	
    			), 
    			array(
    				'name'=>'phone',    				
    				'htmlOptions'=> array('style'=>'width:150px;')	            	
    			),
    			array(
    				'name'=>'email',    				
    				'htmlOptions'=> array('style'=>'width:150px;')	            	
    			), 
    			array(
    				'name'=>'user_username',
    				'value' => '$data->user->username',    				
    				'htmlOptions'=> array('style'=>'width:120px;')	            	
    			),
	    		array(
                    'template' => '{view}&nbsp;&nbsp;&nbsp;{login}',
                    'buttons' => array(
                            'view' => array(
                                    'url' => 'Yii::app()->controller->createUrl('
                                    . '"/user/profile",array("username"=>$data->user->username))',
                                    'label' => Yii::t('user', 'Show profile'),
                                    'icon' => 'fa fa-eye',
                            ),
                            'login' => array(
                                    'url' => 'Yii::app()->controller->createUrl('
                                    . '"/account/login_as",array("username"=>$data->user->username))',
                                    'icon' => 'fa fa-custom-user-swap',
                                    'label' => Yii::t('user', 'Login as user'),
                                    'visible' => 'Yii::app()->user->id != $data->user->id && !User::checkRole($data->user->id)'.
                                        ' && !Yii::app()->user->hasState("realUsername")'
                            ),
                    ),
                    'class' => 'bootstrap.widgets.TbButtonColumn',
	    		),
    			array(
    				'name'=>'package_id',
    				'value' => 'Package::badge($data->package->name, $data->package->css_name)',
    				'type'=>'raw',    				
    				'htmlOptions'=> array('style'=>'width:110px;')	            	
    			),
                array(
    				'name'=>'business_reliable',
    				'value' => '$data->business_reliable ? "TAK" : "NIE"' ,
    				'type'=>'raw',    				
    				'htmlOptions'=> array('style'=>'width:110px;')	            	
    			),
    			array(
    				'name'=>'package_expire',
    				'value' => '$data->user->package_expire',    				    				
    				'htmlOptions'=> array('style'=>'width:95px;')	            	
    			),	
    			//'item.name',	
	            //'description',    			  	    		
				/*array(
    				'name'=>'id',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),*/
	    )
    ));
    
?>          
