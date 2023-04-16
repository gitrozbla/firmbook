<?php
	$creators = Yii::app()->params['websiteMode'] == 'creators';

$box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => '<div class="pull-right">'.$user->badge(false, $creators).'</div>'
								. Html::link(
                $user->username,
                $this->createUrl('user/profile', array(
                    'username' => $user->username))
        ),
        'headerIcon' => 'fa fa-user',
    		'htmlOptions' => array('class' => 'fixed-width-icon'),
        //'htmlOptions' => array('class' => 'transparent fixed-width-icon'),
        'htmlHeaderOptions' => array('class' => $user->getPackageItemClass($creators)),
    )
);?>
    <?php if ($user->show_email) : ?>
        <i class="fa fa-envelope"></i>
        <?php $this->widget(
                'application.components.widgets.TextOverflowScroll',
                array(
                    'text' => '<a href="mailto:'.$user->email.'">'.$user->email.'</a>',
                )
        ); ?><br />
    <?php endif; ?>

		<?php if (!empty($user->skype)) : ?>
        <i class="fa fa-skype"></i>
        <?php echo Html::skypeWidget($user->skype); ?>

    <?php endif; ?>
<?php $this->endWidget(); ?>
