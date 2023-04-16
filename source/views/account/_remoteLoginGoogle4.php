<?php $this->widget(
				    'Button',
				    array(
				    	'id' =>'signin-button',
				    	'buttonType' => 'button',					    
				    	'icon' => 'fa fa-google',
				    	'label' => Yii::t('account','Login by Google'),				    	
					    'htmlOptions' => array(						    
				    		'onclick' => 'initClient()',
				    		'style'=>'margin-left: 10px;',
				    		'title' => Yii::t('account','Login by Google'), 				    			    	
				    	),				    	
				    )
			    ); ?>
<?php //echo Yii::app()->params['google']['clientId'] ?>
<script src="https://apis.google.com/js/api:client.js"></script>
<script>

/**
 * The Sign-In client object.
 */
var auth2;

/**
 * Initializes the Sign-In client.
 */
var initClient = function() {
	
    gapi.load('auth2', function(){
    	
        /**
         * Retrieve the singleton for the GoogleAuth library and set up the
         * client.
         */
        auth2 = gapi.auth2.init({
            client_id: '<?php echo Yii::app()->params['google']['clientId'] ?>'
        });
        
        // Attach the click handler to the sign-in button
        auth2.attachClickHandler('signin-button', {}, onSuccess, onFailure);
        
    });
};

/**
 * Handle successful sign-ins.
 */
var onSuccess = function(user) {	
    console.log('Signed in as ' + user.getBasicProfile().getName());
    var profile = user.getBasicProfile();
    $.ajax({
  		method: "POST",
	    url: "<?php echo $this->createUrl('account/remote_login_ajax') ?>",
	    data: { <?php echo Yii::app()->request->csrfTokenName ?> : '<?php echo Yii::app()->request->csrfToken ?>', 
	    	name: profile.getName(), 
	    	email: profile.getEmail(),					    	
	    	id: profile.getId(),
	    	source: <?php echo User::REGISTER_SOURCE_GOOGLE; ?> },
	    context: document.body,
	    success: function(){
	      //console.log('Wysłano ajaxem');
	      document.location.href="<?php echo Yii::app()->user->returnUrl ?>"
	    }
	});
 };

/**
 * Handle sign-in failures.
 */
var onFailure = function(error) {	
    console.log(error);
};

</script>
<script>initClient();</script>  