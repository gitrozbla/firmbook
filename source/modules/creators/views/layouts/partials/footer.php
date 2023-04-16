<hr />
        
<div class="container footer text-center">
    <div class="row">
        <div class="span8">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => array(
                    array(
                        'label' => Yii::t('site', 'Home'),
                        'url' => $absoluteHomeUrl,
                    ),
                    array(
                        'label' => Yii::t('CreatorsModule.article', 'Terms of Use'),
                        'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('CreatorsModule.article', 'terms-of-use'))),
                    ),
                    array(
                        'label' => Yii::t('article', 'Packages'),
                        'url' => $this->createUrl('/packages/comparison'),
                    ),
                    array(
                        'label' => Yii::t('CreatorsModule.site', 'My companies'),
                        'url' => $this->createUrl('/companies/list'),
                    ),
                    array(
                        'label' => Yii::t('CreatorsModule.article', 'Help'),
                        'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('CreatorsModule.article', 'help'))),
                    ),
                    array(
                        'label' => Yii::t('contact', 'Contact'),
                        'url' => $this->createUrl('/site/contact'),
                    ),
                    array(
                        'label' => 'Firmbook',
                        'url' => Yii::app()->params['firmbookUrl'],
                        'linkOptions' => array(
                            'target' => '_blank',
                        ),
                    ),
                ),
                'htmlOptions' => array(
                    'class' => 'nav-pills-center'
                ),
            )); ?>
        </div>
        <div class="span4">
            <div class="muted copyright"><small><?php echo Yii::app()->params['branding']['copyrightInfo']; ?></small></div>
        </div>
    </div>
    <?php if (Yii::app()->params['branding']['bottomImage']) : ?>
        <img src="images/branding-creators/program_regionalny.png" alt="<?php echo Yii::app()->params['branding']['bottomImage']; ?>" />
    <?php endif; ?>
        
    <a href="#" id="scroll-to-top" class="scroll-to-top-hidden" style="position:absolute;">
        <i class="fa fa-step-backward fa-rotate-90"></i>
    </a>
</div>