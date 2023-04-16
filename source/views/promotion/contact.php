<?php 
/**
 * Formularz kontaktowy.
 *
 * @category views
 * @package promotion
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row">
    <div class="span9">
    	<h1><?php echo Yii::t('ad', 'Advertise on'); ?> <?php echo Yii::app()->name; ?></h1>        

        <?php $form = $this->beginWidget(
            'ActiveForm',
            array(
                'id' => 'contact-form',
                'type' => 'horizontal',
            )
        ); ?>
            <fieldset>
                <legend><?php echo Yii::t('user', 'Contact information'); ?></Legend>
                <?php echo $form->textFieldRow(
                    $model,
                    'forename'
                ); ?>
                <?php echo $form->textFieldRow(
                    $model,
                    'surname'
                ); ?>
                <?php echo $form->textFieldRow(
                    $model,
                    'phone'
                ); ?>
                <div class="contact-form-or">
                    <?php echo Yii::t('ad', 'or'); ?>
                </div>
                <?php echo $form->textFieldRow(
                    $model,
                    'email'
                ); ?>
            </fieldset>
            <fieldset>
                <legend><?php echo Yii::t('contact', 'Message'); ?></legend>
                <?php echo $form->textFieldRow(
                    $model,
                    'subject'
                ); ?>
                <?php echo $form->textAreaRow(
                    $model,
                    'message',
                    array('class' => 'span6', 'rows' => 8)
                ); ?>
            </fieldset>

            <div class="form-actions">
                <?php $this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => Yii::t('contact', 'Submit'),
                    )
                ); ?>
                <?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Cancel'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('promotion/offer'),
			                	
			                )
			            ); ?>
            </div>

        <?php $this->endWidget(); ?>
    </div>
    
    <div class="span3">
        <?php $this->beginWidget(
            'bootstrap.widgets.TbBox',
            array(
                'title' => false,
                'htmlOptions' => array(
                    'class' => 'widget-box',
                ),
            )
        ); ?>
            <?php $brand = Yii::app()->params['branding']; ?>
            <?php if (isset($brand['skype'])) : ?>
                <h3><?php echo Yii::t('contact', 'Free video presentations of system'); ?>:</h3>
                <p>
                    <?php echo Yii::t('contact', 'Skype contact'); ?><br />
                    <?php /* 
                    <!-- Skype 'My status' button http://www.skype.com/go/skypebuttons -->
                    <script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script>
                    <a href="skype:<?php echo $brand['skype']; ?>?call">
                        <img src="http://mystatus.skype.com/bigclassic/<?php echo $brand['skype']; ?>" style="border: none;" width="182" height="44" alt="Skype" />
                    </a>
                    */ ?>
                    <?php ///* ?>
                    <script type="text/javascript" src="http://www.skypeassets.com/i/scom/js/skype-uri.js"></script>
					<div id="SkypeButton_Call_firmbook_1">
					  <script type="text/javascript">
					    Skype.ui({
					      "name": "call",
					      "element": "SkypeButton_Call_firmbook_1",
					      "participants": ["firmbook.eu"],
					      "imageSize": 32					      
					    });
					  </script>
					</div>
					<?php //*/ ?>
                </p>
                <hr />
            <?php endif; ?>
                
            <?php if (isset($brand['phone'])) : ?>
                <h3><?php echo Yii::t('contact', 'Phone contact'); ?>:</h3>
                <p>
                    <a href="tel:<?php echo str_replace(
                            array(' ', '-', '+', '(', ')'), 
                            '', 
                            $brand['phone']
                            ); ?>">
                        <?php echo $brand['phone']; ?>
                    </a>
                </p>
                <hr />
            <?php endif; ?>
            
            <?php if (isset($brand['email'])) : ?>
                <h3><?php echo Yii::t('contact', 'Email contact'); ?>:</h3>
                <p>
                    <?php echo Yii::t('contact', 'IT and programming'); ?>:<br />
                    <a href="mailto:<?php echo Yii::app()->params['admin']['email']; ?>">
                        <?php echo Yii::app()->params['admin']['email']; ?>
                    </a><br />
                    <?php echo Yii::t('contact', 'CEO'); ?>:<br />
                    <a href="mailto:<?php echo $brand['email']; ?>">
                        <?php echo $brand['email']; ?>
                    </a>
                </p>
                <hr />
            <?php endif; ?>
            
            <p>
                <?php echo Yii::t('contact', 'The owner of servise is'); ?>: <br />
                <b><?php echo $brand['company']; ?></b> 
                <small>
                    <a class="contact-verify" href="https://prod.ceidg.gov.pl/CEIDG/CEIDG.Public.UI/Search.aspx" target="_blank">
                        <?php echo Yii::t('contact', 'Check registers'); ?>
                    </a>
                </small><br />
                NIP: <?php echo $brand['NIP']; ?> 
                <small>
                    <a class="contact-verify" href="https://prod.ceidg.gov.pl/CEIDG/CEIDG.Public.UI/Search.aspx" target="_blank">
                        <?php echo Yii::t('contact', 'Check registers'); ?>
                    </a>
                </small><br />
                REGON: <?php echo $brand['REGON']; ?> 
                <small>
                    <a class="contact-verify" href="https://prod.ceidg.gov.pl/CEIDG/CEIDG.Public.UI/Search.aspx" target="_blank">
                        <?php echo Yii::t('contact', 'Check registers'); ?>
                    </a>
                </small><br />
                <?php echo Yii::t('contact', 'Address'); ?>: <br />
                <?php echo $brand['street']; ?> <?php echo $brand['home']; ?> / <?php echo $brand['flat']; ?><br />
                <?php echo $brand['postalCode']; ?> <?php echo $brand['city']; ?>
                <hr />
            </p>
            
            <p>
                <?php $this->widget('ext.qrcode.QRCodeGenerator',array(
                        'data' => Yii::app()->createAbsoluteUrl('site/contact'),
                        'filename' => 'contact_url.png',
                    )) ?>
            </p>
                
        <?php $this->endWidget(); ?>
    </div>
</div>