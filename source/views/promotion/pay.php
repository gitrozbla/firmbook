<?php 
/**
 * Podstrona przenosząca do systemu dotpay.
 *
 * @category views
 * @package promotion
 * @author
 * @copyright (C) 2014
 */ 

$selectedId = Yii::app()->request->getParam('id');

//dane do TbDetailView
$tdvAttributes = array();
$tdvAttributes[] = array(
	'value'=> $order->box->name,
	'label'=>Yii::t('ad', 'Product:'),
	'type' => 'raw'
);
$tdvAttributes[] = array(
	'value'=> $order->box->label,
	'label'=>Yii::t('ad', 'Label').':',
	'type' => 'raw'
);
$tdvAttributes[] = array(
	'value'=> $order->price.' PLN',
	'label'=>Yii::t('ad', 'Price').':',
	'type' => 'raw'
);
$tdvAttributes[] = array(
	'value'=> $order->period.' '.Yii::t('ad', 'weeks'),
	'label'=>Yii::t('ad', 'For the period:'),
	'type' => 'raw'
);
?>

<div class="row">
    <div class="span6"> 
    <h1><?php echo Yii::t('ad', 'Advertise on'); ?> <?php echo Yii::app()->name; ?></h1>            
            <?php echo Yii::t('ad', 'Details of the order'); ?>            
            <hr />       
        <?php $this->widget(
			    'bootstrap.widgets.TbDetailView',
			    array(
			    	'id' => 'detail-view',	
				    'data' => array(),	    	
			    	'htmlOptions' => array(
			    		//'style' => 'width: 800px',
			    	),
			    	'attributes' => $tdvAttributes	    	
			    )
		    );
		?>
		<?php 
		Yii::app()->request->enableCsrfValidation = false;
		$dotpayForm = CHtml::beginForm(Yii::app()->params['packages']['dotpayPaymentUrl'], 'POST',
				array('style'=>'display:inline', 'id'=>'paymentForm')
		);
		//CHtml::clientChange('submit',)
		$dotpayForm .= CHtml::hiddenField('api_version', 'dev');
		$dotpayForm .= CHtml::hiddenField('id', Yii::app()->params['packages']['dotpayId']);
		$dotpayForm .= CHtml::hiddenField('amount', $order['price']);
		$dotpayForm .= CHtml::hiddenField('description', Yii::t('ad', 'Advertising fee - {box_label}.', array('{box_label}'=>$order->box->label)));
		$dotpayForm .= CHtml::hiddenField('URL', $this->createAbsoluteUrl('promotion/paymentconfirm/id/'.$order->id));
		$dotpayForm .= CHtml::hiddenField('URLC', $this->createAbsoluteUrl('promotion/transactionconfirm/id/'.$order->id));
		$dotpayForm .= CHtml::hiddenField('type', 0);
		$dotpayForm .= CHtml::hiddenField('control', $order->id);
		$dotpayForm .= CHtml::hiddenField('p_info', Yii::app()->name);
		//$dotpayForm .= CHtml::hiddenField('p_email', Yii::app()->email);
		
		$dotpayFormEnd = CHtml::endForm();
		Yii::app()->request->enableCsrfValidation = true;
		
		?>         
    </div>    
    <div class="span6"> 
    	<?php $this->beginWidget(
    		'bootstrap.widgets.TbHeroUnit',
    		array(
    			'heading' => Yii::t('ad', 'Pay order'),
    			//'heading' => '<img src="images/branding/email-logo.png" alt="'.Yii::app()->params['branding']['title'].'"/>',
    			'encodeHeading' => false,
    			//'heading' => 'Witaj na Firmbook-u!',
    			'htmlOptions' => array(
    				'class' => 'text-center',	
    				'style' => 'background-color: rgba(0,165,0,0.1); cursor: pointer',
    				//'style' => 'background-color: rgba(92,170,229,0.1); cursor: pointer',
    				//'onclick' => 'document.location.href="http://onet.pl"'
    				'onclick' => 'document.location.href="'.$this->createAbsoluteUrl('account/register').'"'	
    							
    			)			
    		)			
    	); ?>    	
    		<p class="lead primary"><?php echo Yii::t('ad', 'To pay for the service use the button below.'); ?></p>
    		<?php echo $dotpayForm.'<input type="submit" class="btn btn-primary" value="'.Yii::t('packages', 'Pay').'"/>'.$dotpayFormEnd; ?>
    		<?php //<p class="lead primary">Firmbook - pierwsza platforma business community. Nie zwlekaj - utwórz darmowe konto i zacznij korzystać z narzędzi, możliwości i środków udostępnianych na firmbooku.</p>?>
    		<?php /*$this->widget(
                    'bootstrap.widgets.TbButton',
                    array(
                        'label' => Yii::t('ad', 'Pay'),
                        //'url' => array('account/register'),
                        'type' => 'primary',
                    	'size' => 'large',
                    	'url'=>'#',
                    	//'htmlOptions'=>array('onclick' => '$("#formID").submit()'),
                        'htmlOptions' => array(
                        	'onclick' => '$("#paymentForm").submit()',
                            'class' => 'top-10',
                        ),
                    )
                );*/ ?>
                
    	<?php $this->endWidget(); ?>
    	<?php //$this->renderPartial('_adboxList') ?>    	    
		<?php /*   
        <br />
        <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>
        <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
        */ ?>
    </div>
</div>


