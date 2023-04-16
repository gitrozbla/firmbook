<?php 
/**
 * Zmiana hasła.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row">
    <div class="offset4 span4">
        <h1>Przywracanie hasła</h1>

        <p>Podaj nowe hasło...</p>

        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        
            <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'width-limit')); ?><br />

            <?php echo $form->passwordFieldRow($model, 'passwordRepeat', array('class' => 'width-limit')); ?><br />
            
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Zmień hasło', 'type'=>'primary')); ?>
            
        <?php $this->endWidget(); ?>

    </div>
</div>