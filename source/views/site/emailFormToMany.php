<?php 
/**
 * Formularz wiadomości email.
 *
 * @category views
 * @package main
 * @author
 * @copyright (C) 2015
 */ 
?>
<div class="modal-header"><a class="close" data-dismiss="modal">&times;</a>
	<h4><i class="fa fa-envelope"></i> <?php echo Yii::t('common', 'Send message'); ?></h4>
	</div>
	<div class="modal-body">
	
<!-- <div class="row">
    <div class="span9">
        <h1><?php echo $this->t2('Contact'); ?></h1> -->

        <?php $form = $this->beginWidget(
            'ActiveForm',
            array(
                'id' => 'emailForm',
                //'type' => 'horizontal',
                'enableAjaxValidation'=>true,
                'htmlOptions' => array(
                	'class' => 'span5',
            		//'style' => 'margin-bottom: 100px;'
            		//'onsubmit'=>"alert('onsubmit'); return false;",/* Disable normal form submit */
                	//'onclick'=>"alert('onclick'); return false;",/* Disable normal form submit */
            	),
            	/*'clientOptions'=>array(
			        'validateOnSubmit'=>true, // Required to perform AJAX validation on form submit
                	'afterValidate'=>'js:alert("jest")', // Your JS function to submit form
			        //'afterValidate'=>'js:mySubmitFormFunction', // Your JS function to submit form
			    ),*/
            )
        ); ?>
        	<?php echo $form->hiddenField($email, 'recipientId');?>
        	<?php echo $form->hiddenField($email, 'recipientType');?>
        	<?php echo $form->hiddenField($email, 'recipientName');?>
        	<?php //echo $form->hiddenField($email, 'recipientEmail');?>
        	<?php if ($email->recipientItemId) : ?>
        	<?php echo $form->hiddenField($email, 'recipientItemId');?>
        	<?php echo $form->hiddenField($email, 'recipientItemType');?>
        	<?php echo $form->hiddenField($email, 'recipientItemName');?>
        	<?php endif; ?>            
            <!-- <fieldset>
                <legend><?php echo Yii::t('contact', 'Message'); ?></legend> -->
                <?php echo $form->textFieldRow(
                    $email,
                    'recipientName',
                    array('class' => 'span5', 'disabled'=>'disabled')
                ); ?>
                <?php if ($email->recipientItemId) : ?>
                <?php echo $form->textFieldRow(
                    $email,
                    'recipientItemName',
                    array('class' => 'span5', 'disabled'=>'disabled')
                ); ?>
                <?php endif; ?>
                <?php echo $form->textFieldRow(
                    $email,
                    'subject',
                    array('class' => 'span5')
                ); ?>
                <?php echo $form->textAreaRow(
                    $email,
                    'message',
                    array('class' => 'span5', 'rows' => 4)                    
                ); ?>
            <!-- </fieldset> -->

            <div class="form-actions">
                <?php $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => Yii::t('contact', 'Submit'),
                    	'htmlOptions' => array(		                	
                    		'onclick'=>"sendEmail('emailModal'); return false;",/* Disable normal form submit */		                	
		            	),
                    )
                ); ?>
            </div>

        <?php $this->endWidget(); ?>
<!--     </div>   
</div> -->

</div>
<div class="modal-footer"></div>

<?php
echo '
<script>	
	function sendEmail(id){			
			var data=$("#emailForm").serialize();
			$.ajax({
				method: "POST",
				url: "'.$this->createUrl('site/send_email_to_many').'",
				data: data,
				//data: {type: 1, '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				dataType: "html",
			}).done(function(html) {		        
		        if(html=="close") {
					$("#" + id).modal("hide");					
				} else	
					$("#" + id).html(html);	
			})/*.success(function(html) {})*/;				
	}
</script>
'; 

?>