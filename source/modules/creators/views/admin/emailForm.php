<h1 class="text-center"><i class="fa fa-envelope"></i> <?php echo Yii::t('common', 'Send message'); ?></h1>

<div class="span3"></div>
<?php $form = $this->beginWidget(
  'ActiveForm',
  array(
    'id' => 'email-form',
    //'enableAjaxValidation' => true,
		'enableClientValidation' => true,
    'htmlOptions' => array(
    	'class' => 'span6'
		)
	)
);?>

  <?php echo $form->textAreaRow(
      $email,
      'recipientAddresses',
      array('class' => 'span6')
  ); ?>
	<?php echo $form->checkBoxRow(
      $email,
      'recipientAll'
  ); ?>
	<p class="quiet">(<?php echo Yii::T('contact', ':count fully activated accounts in total', array(':count' => $allCount)); ?>)</p>
	<div class="alert alert-block">
		<?php echo Yii::t('contact', 'For safety this message will be sent to every recipients separately. This may take a while.'); ?>
	</div>
  <?php echo $form->textFieldRow(
      $email,
      'subject',
      array('class' => 'span6')
  ); ?>
  <?php echo $form->textAreaRow(
      $email,
      'message',
      array('class' => 'span6', 'rows' => 10)
  ); ?>
    <!-- </fieldset> -->

  <div class="form-actions">
      <?php $this->widget(
          'bootstrap.widgets.TbButton',
          array(
              'buttonType' => 'submit',
              'type' => 'primary',
              'label' => Yii::t('contact', 'Submit')
          )
      ); ?>
  </div>

<?php $this->endWidget(); ?>

<?php Yii::app()->clientScript->registerScript('email-form-script', "
	jQuery(function($) {
		var all = $('#EmailForm_recipientAll');
		var emailAddresses = $('#EmailForm_recipientAddresses');

		var checkboxChangeHandler = function() {
			if (all.prop('checked')) {
				emailAddresses.attr('disabled', 'disabled');
			} else {
				emailAddresses.removeAttr('disabled', 'disabled');
			}
		}

		all.on('change', checkboxChangeHandler);
		checkboxChangeHandler();
	});
");
