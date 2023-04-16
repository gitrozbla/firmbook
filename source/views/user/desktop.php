<h1><i class="fa fa-desktop"></i> <?php echo Yii::t('desktop', 'Desktop'); ?></h1>
<hr />
<div class="row">
    <div class="span3">
        <?php
            $search = Search::model()->getFromSession();
            $user = Yii::app()->user->getModel();
            $action = $search->action;
            if (PackagePurchase::model()->exists('user_id=:user_id and status=:status', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])))
				$packagePending = true;
			else	
				$packagePending = false;
        ?>
        <?php $this->widget(
            'bootstrap.widgets.TbBox',
            array(
                'title' => Yii::t('desktop', 'Quick access'),
                'htmlOptions' => array(
                    'class' => 'widget-box',
                ),
                'content' => $this->widget(
                    'bootstrap.widgets.TbMenu',//'application.components.widgets.EditableMenu', //'bootstrap.widgets.TbMenu',
                    array(
                        'encodeLabel' => false,
                        'items' => array(
                        	array(
                        		'itemOptions' => array('class' => 'active'),
                        		'label' => '<i class="fa fa-plus"></i> '
                        				.Yii::t('desktop', 'Add new company'),
                        		'url' => $this->createUrl('/companies/add'),
                        	),
                            array(
                            	'itemOptions' => array('class' => 'active'),
                                'label' => '<i class="fa fa-plus"></i> '
                                        .Yii::t('desktop', 'Add new product'),
                                'url' => $this->createUrl('/products/add'),
                            ),
                            array(
                                'label' => '<i class="fa fa-plus"></i> '
                                        .Yii::t('desktop', 'Add new service'),
                                'url' => $this->createUrl('/services/add'),
                            ),
                        	/*array(
                            	'itemOptions' => array('class' => 'active'),
                                'label' => '<i class="fa fa-plus"></i> '
                                        .Yii::t('desktop', 'Add new product'),
                                'url' => $this->createUrl('/categories/show/', array(
                                    'context' => Search::getContextOption($search->action, 'product'),
                                    'username' => Yii::app()->user->name,
                                    )),
                            ),
                            array(
                                'label' => '<i class="fa fa-plus"></i> '
                                        .Yii::t('desktop', 'Add new service'),
                                'url' => $this->createUrl('/categories/show/', array(
                                    'context' => Search::getContextOption($search->action, 'service'),
                                    'username' => Yii::app()->user->name,
                                    )),
                            ),*/	
                            /*array(
                                'label' => '<i class="fa fa-wrench"></i> '
                                        .Yii::t('desktop', 'Edit company profile'),
                                'url' => $this->createUrl('/categories/show/', array(
                                    'context' => Search::getContextOption($search->action, 'company'),
                                    'username' => Yii::app()->user->name,
                                    )),
                            ),*/
                            array(
                                'label' => '<i class="fa fa-wrench"></i> '
                                        .Yii::t('desktop', 'Edit account'),
                                'url' => $this->createUrl('user/profile'),
                            ),
                            array(
                                'label' => '<i class="fa fa-wrench"></i> '
                                        .Yii::t('desktop', 'Change password'),
                                'url' => $this->createUrl('user/profile'),
                            ),
                            array(
                                'label' => '<i class="fa fa-wrench"></i> '
                                        .Yii::t('desktop', 'Payments'),
                                'url' => $this->createUrl('packages/history'),
                            ),
                        ),
                    ),
                    true
                ),
            )
        ); ?>
        <?php  ?>
    </div>
    
    <div class="span6">
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
    </div>
    
    <div class="span3">
        <div class="well box bg-transparent">
            <?php if ($user->package_id): ?>
            <p>
                <?php echo Yii::t('packages', 'Your package:');?>
                <?php echo $user->badge(true); ?>
            </p>
            <p>
                <?php echo Yii::t('packages', 'Package will expire on');?><br />
                <?php echo $user->package_expire; ?>
            </p>
            	<?php if(!$packagePending) :?>
            <p class="text-center">
                <?php $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'label' => Yii::t('packages', 'Change your package'),
                        'type' => 'primary',
                        'url' => $this->createUrl('/packages/comparison'),
                    )
                ); ?>
            </p>
            	<?php endif; ?>
            <?php else: ?>
            <p>
                <?php echo Yii::t('packages', 'You currently have no package.');?>
            </p>
            <p class="text-center">
                <?php $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'label' => Yii::t('packages', 'Upgrade your account'),
                        'type' => 'primary',
                        'url' => $this->createUrl('/packages/comparison', array(
                            'name'=>Yii::t('article', 'packages'))),
                    )
                ); ?>
            </p>
            <?php endif; ?>
        </div>
        <?php /*<div class="paypal-logo">
            <div>
                <?php echo Yii::t('packages', 'SECURE CREDIT CARD PAYMENT<br />with'); ?>
            </div>
            <img src="images/paypal_logo.jpg" alt="PAYPAL" />
        </div>*/ ?>
    </div>
</div>