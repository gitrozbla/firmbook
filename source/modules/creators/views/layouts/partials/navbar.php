<?php
    $user = Yii::app()->user;
    $isGuest = $user->isGuest;
    $isAdmin = $user->isAdmin;
    // Konfiguracja przycisków w zależności od użytkownika.
    // Wyciągnięte w celu optymalizacji (wersja bez użycia visible).
    $buttons = array(
        array(
            'label' => '<img class="lang-img" src="'
                . 'images/flag_icons/'.Yii::app()->language.'.gif" alt="">&nbsp;'
                . Yii::app()->params->languages[Yii::app()->language].'</span>',
            'items' => array_map('Html::languageMap', 
                    array_keys(Yii::app()->params->languages), 
                    Yii::app()->params->languages
                    ),
        ),
    );
    if (!$isGuest) {
        $buttons = array_merge(
                $buttons,
                array(
                    array(
                        'label' => Yii::t('CreatorsModule.site', 'My companies'),
                        'url' => $this->createUrl('/companies/list'),
                        'active' => $this->id == 'companies' && $this->action->id == 'list',
                    ),
                    array(
                        'label' =>  
                            '<div class="text-center" style="width: 200px;">'
                                .$this->widget('application.components.widgets.TextOverflowScroll', array(
                                    'text' => $user->name,//.' '.$user->creators_package_id
                                ), true)
                            .'</div>',
                    	'url' => $this->createUrl('/user/profile'),
                        //'url' => $this->createUrl('/companies/list'),
                        'active' => $this->id == 'user' && $this->action->id == 'profile',
                        'itemOptions' => array(
                            'class' => 'username-highlight',
                            )
                    ),
                
        		array(
        				'label' => Yii::t('CreatorsModule.site', 'Package'),
        				'icon' => 'fa fa-credit-card',
        				//'url' => $this->createUrl('/packages/comparison'),
        				'active' => $this->id == 'packages' && ($this->action->id == 'comparison' || $this->action->id == 'history'),
        				'items' => array(
        						array(
        								'label' => Yii::t('CreatorsModule.site', 'Package change'),
        								'url' => $this->createUrl('/packages/show'),
        						),
        						array(
        								'label' => Yii::t('CreatorsModule.site', 'Payments'),
        								'url' => $this->createUrl('/packages/history'),
        						)
        				)
        		)
                		)
        );
    }
    if ($isGuest) {
    	$buttons = array_merge(
    			$buttons,
    			array(
    					array(
    							'label' => Yii::t('account', 'Login'),
    							'url' => $absoluteHomeUrl,
    					),
    					array(
    							'label' => Yii::t('account', 'Register'),
    							'url' => $this->createUrl('/account/register'),
    					),
    			)
    	);
    } else {
    //if ($isGuest == false) {
        if (($realUsername = $user->getState('realUsername')) != null) {
            // przycisk logowania przeniesiony na środek
        	$buttons = array_merge(
        			$buttons,
        			array(
        					array(
        							'label' => Yii::t('account', 'Back to').' '.$realUsername,
        							'url' => $this->createUrl('/account/login_back', array(
        									'username' => $realUsername,
        							)),
        							'linkOptions' => array('class'=>'login-as-button'),
        					),
        			)
        	);
        	
        } else {
            $buttons = array_merge(
                    $buttons,
                    array(
                        array(
                            'label' => Yii::t('account', 'Logout'),
                            'url' => $this->createUrl('/site/logout'),
                        ),
                    )
                    );
        }
    }
?>
<?php 
    $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => Yii::app()->params['branding']['title'],//'<img src="images/branding/top-logo.png" alt="'.Yii::app()->params['branding']['title'].'" />',
            'brandUrl' => $absoluteHomeUrl,
            'type' => 'inverse',
            //'fixed' => false,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'encodeLabel' => false,
                    'items' => $buttons,
                ),
            ),
        )
    );
?>