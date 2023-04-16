<?php 
//<script src="https://apis.google.com/js/platform.js" async defer></script>
//<script src="https://apis.google.com/js/api:client.js"></script>
?>
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
            client_id: '620805939068-8lasopi0k0kllod9kinkadh444hmcmv3.apps.googleusercontent.com'
        });
        alert('dziala 3');
        // Attach the click handler to the sign-in button
        auth2.attachClickHandler('signin-button', {}, onSuccess, onFailure);
        alert('dziala 4');
    });
};

/**
 * Handle successful sign-ins.
 */
var onSuccess = function(user) {
	alert('sukces5');
    console.log('Signed in as ' + user.getBasicProfile().getName());
 };

/**
 * Handle sign-in failures.
 */
var onFailure = function(error) {
	alert('blad');
    console.log(error);
};

</script>            
<div class="g-signin2" id="signin-button"></div>
           
<?php
//<script>initClient();</script>  
//<div class="g-signin2" id="signin-button"></div>
//<div class="g-signin2" data-onsuccess="onSignIn"></div>
?>
            
            