<?php
    /*$string = 'aleje jerozolimskie';
    $lastSpacePos = strrpos($string, ' ');
    $multipleWords = $lastSpacePos !== false;
    if ($multipleWords) {
        $lastWord = substr($string, $lastSpacePos+1);
    }
    var_dump($lastWord);
    $haystack = 'test1 jerozolimskie test2';
    $occurrence = strpos(' '.$haystack.' ', ' '.$lastWord.' ') !== false;
    var_dump($occurrence);*/

	$user = User::model()->with('packageCreators')->findByPk(Yii::app()->user->id);	
?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => Yii::t('CreatorsModule.packages', 'Package').' '.$user->packageCreators->name,
    	'htmlHeaderOptions' => array('style'=>'color:white; background-color: '.$user->packageCreators->color)
        //'htmlHeaderOptions' => array('style'=>'color:white; background:gold')
    )
);?>
<?php if($user->creators_package_id != Yii::app()->params['packages']['defaultPackageCreators']) : ?>
<p><strong><?php echo Yii::t('CreatorsModule.packages', 'Your package expires'); ?> <?php echo $user->creators_package_expire ?></strong></p>
<?php endif; ?>
<p class="text-center">
    <?php $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'label' => Yii::t('CreatorsModule.packages', 'Upgrade your account here'),
            'type' => 'primary',
            'url' => $this->createUrl('/packages')
        )
    ); ?>
</p>
<?php $this->endWidget(); ?>



<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
			'title' => Yii::t('surveyForm', 'Survey'),
			'htmlOptions' => array('class' => 'survey-form')
		)
);
	$form = $this->beginWidget(
			'ActiveForm',
			array(
					'id' => 'survey-form'
			)
	);
			echo '<p><strong>'.Yii::t('surveyForm', 'Do you have any suggestion or need a help?').'</strong></p>';
			echo $form->hiddenField($surveyForm, 'email',
							array('value' => Yii::app()->user->getModel()->email)
					);
			echo $form->textAreaRow($surveyForm, 'message', array(), array('label' => false));

			//echo $form->errorSummary($surveyForm, '');
			echo '<p>';
				 $this->widget(
						'bootstrap.widgets.TbButton',
						array(
								'label' => Yii::t('surveyForm', 'Leave message'),
								'url' => array('site/survey'),
								'buttonType' => 'submit',
								'type' => 'primary'
						)
				);
			echo '</p>';
	$this->endWidget();
$this->endWidget(); ?>


<?php /*$box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array('title' => false)
);?>
    <p class="text-center">
        <?php echo Html::link(
                Yii::t('CreatorsModule.companies', 
                        'Login to your Firmbook account to edit companies and personal data.'),
                $this->createFirmbookUrl('/user/profile'),
                array('target'=>'_blank')); ?>
    </p>
<?php $this->endWidget();*/ ?>