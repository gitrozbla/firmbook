<?php
    $user = Yii::app()->user;
    $isGuest = $user->isGuest;
    $isAdmin = $user->isAdmin;
	$search = Search::model()->getFromSession();
    $type = $search->type;
	
    $favoriteCount = Elist::model()->count(
        array(
            'condition'=>'user_id=:user_id and type=:type',
            'params'=>array(':user_id'=>Yii::app()->user->id, ':type'=>Elist::TYPE_FAVORITE)
        ));
    $followedCount = Follow::model()->count(
        array(
            'condition'=>'user_id=:user_id',
            'params'=>array(':user_id'=>Yii::app()->user->id)
        ));
//    $alertsCount = Alert::model()->count(
//        array(
//            'condition'=>'user_id=:user_id and date>:date',
//            'params'=>array(
//                ':user_id'=>Yii::app()->user->id,
//                ':date'=> date('Y-m-d', strtotime('-'.Alert::EXPIRE_AFTER.' days'))			
//            )
//        ));

    $alertsNewCount = Alert::model()->count(
        array(
            'condition'=>'user_id=:user_id and date>:date and displayed=0',
            'params'=>array(
                ':user_id'=>Yii::app()->user->id,
                ':date'=> date('Y-m-d', strtotime('-'.Alert::EXPIRE_AFTER.' days'))			
            )
        ));

    // Konfiguracja przycisków w zależności od użytkownika.
    // Wyciągnięte w celu optymalizacji (wersja bez użycia visible).
    $buttons = array(
        array(
            'label' => '<img class="lang-img" src="'
                . 'images/flag_icons/'.Yii::app()->language.'.gif" alt="">&nbsp;'
                . Yii::app()->params->languages[Yii::app()->language].'</span>'
								. '<span class="small-desc">('.Yii::t('site', 'interface&nbsp;language').')</span>',
            'items' => array_map('Html::languageMap',
                    array_keys(Yii::app()->params->languages),
                    Yii::app()->params->languages
                    ),
        ),
    );
    
    $buttons = array_merge(    		
    		array(
//    			array(
//    				'label' => 'Creators',
//    				'url' => Yii::app()->params['creatorsUrl'],
//    				//'url' => $this->createCreatorsUrl('/'),
//    				'linkOptions' => array(
//    					'target' => '_blank',
//    					'rel' => 'nofollow'
//    				),
//					'itemOptions' => array(
//						'class' => 'creators'
//					)
//    			),
                
    			array(
    				'label' => '<i class="fa fa-plus"></i> ' . Yii::t('site', 'Add product/service/company'),
    				'url' => $this->createUrl(Search::getTypeController($type).'/add'),
    				'itemOptions' => array(
    					'class' => 'hidden-tablet hidden-phone'    				
    				),
    				'linkOptions' => array(    					
    					'rel' => 'nofollow'
    				)
    			),
    			array(
    				'label' => '<div class="relative">
								<div id="google_translate_element" style="margin-top:8px; margin-right:10px;"></div>
								<span class="small-desc">('.Yii::t('site', 'content&nbsp;language').')</span>
								</div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: \'pl\', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false}, \'google_translate_element\');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        ',
    				//'url' => $this->createUrl('/account/login'),
    			),
				
    		),
    		$buttons
    );
    
    if ($isGuest) {
        
        $buttons = array_merge(
                $buttons,
                array(
                    array(
                        'label' => Yii::t('account', 'Login'),
                        'url' => $this->createUrl('/account/login'),
                    	'linkOptions' => array(
                    		'rel' => 'nofollow'
                    	)
                    ),
                    array(
                        'label' => Yii::t('account', 'Register'),
                        'url' => $this->createUrl('/account/register'),
                    	'linkOptions' => array(
                    		'rel' => 'nofollow'
                    	)
                    ),
                )
                );
    } else {
        
        $buttons = array_merge(
            $buttons,     
            array(    
                array(
                    'label' => $this->widget(
                        'Button',
                        array(
                            'id' =>'btn-favorite',
                            'buttonType' => 'button',
                            'label' => '('.$favoriteCount.')',
                            //'label' => Yii::t('elists','Favorite').' ('.$favoriteCount.')',
                            'type' => $favoriteCount ? 'success' : '',
                            'icon' => 'fa fa-heart',
                            'htmlOptions' => array(
                                'data-toggle' => 'modal',
                                'data-target' => '#favoriteModal',
                                'onclick' => 'loadElist(\'favoriteModal\', 1)',	
                                'title' => Yii::t('elists', 'Favorite'),
                                'style' => 'margin-right: 3px; margin-left: 10px;'
                            )				    	
                        ), true
                    ),
                ),
                array(
                    'label' => $this->widget(
                        'Button',
                        array(
                            'id' =>'btn-follow',
                            'buttonType' => 'button',
                            'label' => '('.$followedCount.')',
                            //'label' => Yii::t('follow','Followed').' ('.$favoriteCount.')',
                            'type' => $followedCount ? 'success' : '',
                            'icon' => 'fa fa-eye',
                            'htmlOptions' => array(
                                'data-toggle' => 'modal',
                                'data-target' => '#followModal',
                                'onclick' => 'loadFollow()',
                                'title' => Yii::t('follow', 'Observed'),
                                'style' => 'margin-right: 3px;'
                            )				    	
                        ), true                    
                    ),
                ),
                array(
                    'label' => $this->widget(
                        'Button',
                        array(
                            'id' =>'btn-alerts',
                            'buttonType' => 'button',
                            'label' => '('.$alertsNewCount.')',
                            //'label' => Yii::t('follow','Followed').' ('.$favoriteCount.')',
                            'type' => $alertsNewCount ? 'danger' : '',
                            'icon' => 'fa fa-bell',
                            'htmlOptions' => array(
                                'data-toggle' => 'modal',
                                'data-target' => '#alertModal',
                                'onclick' => 'loadAlert()',		
                                'title' => Yii::t('alerts', 'Notifications'),	
                                'style' => 'margin-right: 3px;'
                            )				    	

                        ), true                    
                    ),
                )
            )
            
        );
//        $buttons = array_merge(
//            $buttons,
//            array(
//                array(
//                    'label' => $isGuest ? '' : 
//                        '<div class="text-center" style="width: 200px;">'
//                            .$this->widget('application.components.widgets.TextOverflowScroll', array(
//                                'text' => $user->name,
//                                //'text' => $user->name.' '.$user->getState('package_id'),
//                                //'text' => $user->name.' '.$user->package_id,                                    
//                            ), true)
//                        .'</div>',
//                    'url' => $this->createUrl('/user/profile'),
//                    'active' => $this->id == 'user' && $this->action->id == 'profile',
//                    'itemOptions' => array(
//                        'class' => 'username-highlight, '.$user->getModel()->getPackageItemClass(),
//                    )
//                ),
//                //forum link
//                /*array(
//                    'label' => '<i class="fa fa-comments-o"></i> '.Yii::t('account', 'Forum'),
//                    'url' => $this->createUrl('/forum'),
//                    'linkOptions' => array(
//                        'target' => '_blank',
//                    ),
//                ),*/
//            )
//        );
    }
    if (false && $isAdmin 
            && isset($this->editorEnabled) && $this->editorEnabled) {
        $buttons = array_merge(
            $buttons,
            array(
                array(
                    'label' => '<i class="fa fa-wrench"></i> '.Yii::t('editor', 'Editor').
                        ($editor ? '<br /><span class="page-editor-button-info">('.
                            Yii::t('editor', 'click to turn off').')</span>' : ''),
                    'url' => $this->createUrl('/site/editor', array(
                        'return' => urlencode(Yii::app()->request->requestUri),
                    )),
                    'active' => $editor,
                    'itemOptions' => array(
                        'class' => 'navbar-editor-button '.($editor ? '' : 'blink'),
                    ),
                ),
            )
        );
    }
//    if ($isGuest == false) {
//        if (($realUsername = $user->getState('realUsername')) != null) {
//            $buttons = array_merge(
//                    $buttons,
//                    array(
//                        array(
//                            'label' => Yii::t('account', 'Back to').' '.$realUsername,
//                            'url' => $this->createUrl('/account/login_back', array(
//                                'username' => $realUsername,
//                            )),
//                            'linkOptions' => array('class'=>'login-as-button'),
//                        ),
//                    )
//                    );
//        } else {
//            $buttons = array_merge(
//                    $buttons,
//                    array(
//                        array(
//                            'label' => Yii::t('account', 'Logout'),
//                            'url' => $this->createUrl('/account/logout'),
//                        ),
//                    )
//                    );
//        }
//    }
    if ($isGuest == false) {
        $dropdwonButtons = array(
            array(
                'label' => Yii::t('user', 'Profile'),
                'url' => $this->createUrl('/user/profile'),
                'active' => $this->id == 'user' && $this->action->id == 'profile',
//                'itemOptions' => array(
//                    'class' => 'username-highlight, '.$user->getModel()->getPackageItemClass(),
//                )
            ),
//            array(
//                'label' => Yii::t('account', 'Logout'),
//                'url' => $this->createUrl('/account/logout'),
//            )
        );
        
        if (($realUsername = $user->getState('realUsername')) != null) {
            $dropdwonButtons = array_merge(
                $dropdwonButtons,
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
        }
        $dropdwonButtons = array_merge(
            $dropdwonButtons,
            array(
                array(
                    'label' => Yii::t('account', 'Logout'),
                    'url' => $this->createUrl('/account/logout'),
                ),
            )
        );                    
        
        $buttons = array_merge(
            $buttons,
            array(
                array(                
    //                'label' => 'Dropdown',
    //                'url' => $this->createUrl('/account/logout'),
                    'label' => $isGuest ? '' : 
                        '<div class="text-center" style="width: 200px; display:inline-block">'
    //                        .$user->name
                            .$this->widget('application.components.widgets.TextOverflowScroll', array(
                                'text' => $user->name,                            
                            ), true)
                        .'</div>',
                    'url' => $this->createUrl('/user/profile'),
                    'active' => $this->id == 'user' && $this->action->id == 'profile',
                    'itemOptions' => array(
                        'class' => 'username-highlight, '.$user->getModel()->getPackageItemClass(),
                    ),
                    'items' => $dropdwonButtons
                ),
            )
        );
    }
?>
<?php
//  $socialHtml = '<span class="firmbook-social">
//      <a href="https://www.facebook.com/sharer.php?u=' . $absoluteBaseUrl . '"
//            target="_blank" class="social-facebook" rel="nofollow">
//          <i class="fa fa-facebook" aria-hidden="true"></i>
//      </a>
//      <a href="https://twitter.com/share?url=' . $absoluteBaseUrl . '"
//            target="_blank" class="social-twitter" rel="nofollow">
//          <i class="fa fa-twitter" aria-hidden="true"></i>
//      </a>
//      <a href="https://plus.google.com/share?url=' . $absoluteBaseUrl . '"
//            target="_blank" class="social-google-plus" rel="nofollow">
//          <i class="fa fa-google-plus" aria-hidden="true"></i>
//      </a>
//      <a href="https://pinterest.com/pin/create/button/?url=' . $absoluteBaseUrl . '"
//            target="_blank" class="social-pinterest" rel="nofollow">
//          <i class="fa fa-pinterest" aria-hidden="true"></i>
//      </a>
//  </span>';
  $socialHtml = '<span class="firmbook-social">
      <a href="https://www.facebook.com/sharer.php?u=' . $absoluteBaseUrl . '"
            target="_blank" class="social-facebook" rel="nofollow">
          <i class="fa fa-facebook" aria-hidden="true"></i>
      </a>
      <a href="https://twitter.com/share?url=' . $absoluteBaseUrl . '"
            target="_blank" class="social-twitter" rel="nofollow">
          <i class="fa fa-twitter" aria-hidden="true"></i>
      </a>      
  </span>';
?>
<?php 
    $this->widget(
        'bootstrap.widgets.TbNavbar',
        array(
            'brand' => '<a href="'. $absoluteHomeUrl . '">
                    <img src="images/branding/top-logo.png"
                        alt="'.Yii::app()->params['branding']['title'].'" />
                </a>' 
//                . $socialHtml
            ,
            'brandUrl' => false,//$absoluteHomeUrl,
            'type' => 'inverse',
            'fixed' => false,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'encodeLabel' => false,
                    'items' => $buttons,
                ),
            ),
            'htmlOptions' => array('class'=>'container'),
        )
    );
?>
<?php require 'modals.php'; ?>