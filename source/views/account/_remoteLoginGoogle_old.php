

            
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            <div class="g-signin2" data-onsuccess="onSignIn"></div>
            
            <script>
	            function onSignIn(googleUser) {
				  var profile = googleUser.getBasicProfile();
				  console.log('ID: ' + profile.getId());
				  console.log('Name: ' + profile.getName());
				  console.log('Image URL: ' + profile.getImageUrl());
				  console.log('Email: ' + profile.getEmail());
				  /*$.ajax({
			      		method: "POST",
					    url: "<?php echo $this->createUrl('account/remote_login') ?>",
					    data: { <?php echo Yii::app()->request->csrfTokenName ?> : '<?php echo Yii::app()->request->csrfToken ?>', 
					    	name: profile.getName(), 
					    	email: profile.getEmail(),					    	
					    	id: profile.getId() },
					    context: document.body,
					    success: function(){
					      //console.log('Wysłano ajaxem');
					      document.location.href="<?php echo Yii::app()->user->returnUrl ?>"
					    }
					});*/
				}
            </script>
