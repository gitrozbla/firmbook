<iframe src="<?php echo Yii::app()->params['calendarUrl']; ?>"
style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>

<p><?php echo Yii::t('admin', 'If calendar is not visible, please log in to your Google account.'); ?></p>
<?php $this->widget('ext.eauth.EAuthWidget', array('action' => '/site/remote_login', 'predefinedServices'=> array('google_oauth'))); ?>
