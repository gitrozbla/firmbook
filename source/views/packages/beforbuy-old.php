<?php 
/**
 * Formularz rejestracyjny.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row">
    <div class="span6">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',
        	'action' => $this->createUrl('packages/payment'),
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        
            <h1><?php echo Yii::t('packages', 'Upgrade your account'); ?> <?php echo Yii::app()->name; ?></h1>            
            <?php echo Yii::t('register', 'Please fill this simple form, it takes less than minute.'); ?>
            
            <hr />
 <input type="hidden" name="package_form" value="1" />           
<label>Pakiet</label>            
<!-- <select name="package_id"> -->
<select name="Purchase[id]">
<?php foreach($packages as $package) : ?>
<?php if($package['id'] != 1) :?>
  <option value="<?php echo $package['id']; ?>" <?php if(Yii::app()->request->getParam('package_id') == $package['id']) echo 'selected';?>><?php echo $package['name']; ?></option>
  <?php endif;?>  
<?php endforeach;?>  
</select>  
<br />
<br />
<label>Okes</label>            
<select name="Purchase[period]">
  <option value="3">3 miesiące</option> 
  <option value="6">6 miesięcy</option> 
  <option value="12">12 miesięcy</option>
</select>
<?php /*echo CHtml::radioButtonList('gender_code','',array('Male'=>'Male','Female'=>'Female'),array('separator'=>'')); */?>
            <?php /*$this->widget('bootstrap.widgets.TbSelect',array(
       'name' => 'package_id',
       //'data' => Country::listData(),
       'data' => array('1'=>'Złoty'),
       'htmlOptions' => array(
           //'multiple' => true,
       ),
))*/ ?>

            <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Buy now'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?>
<!-- <button type="button" class="btn btn-primary">Primary</button> -->
        <?php $this->endWidget(); ?>
            
        <?php /* Yii::app()->clientScript->registerScript(
                'username-autofill',
                "   var emailPart, username;
                    function emailCrop(email) {
                        var atPos = email.indexOf('@');
                        if (atPos != -1) {
                            return email.substring(0, atPos);
                        } else {
                            return email;
                        }
                    }
                    $('#User_email, #User_username').on('focus', function(){
                        emailPart = emailCrop($('#User_email').val());
                        username = $('#User_username').val();
                    });
                    $('#User_email').on('keyup change', function(){
                        
                        if (username == '' || username == emailPart) {
                            $('#User_username').val(emailCrop($(this).val()));
                        }
                    });"
                );*/ ?>

    </div>
    
    <div class="span6">
        
        <br />
        <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>
        
        <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
        
        
    </div>
</div>
