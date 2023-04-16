<?php 
/**
 * Wewnętrzny layout z menu newslettera.
 *
 * @category views
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php  $this->beginContent('//layouts/main'); /* layout globalny */ ?>

<div class="row">
    <div class="span3">
        <?php $this->widget('bootstrap.widgets.TbMenu',
            array(
                'type' => 'list',
                'items' => array(
                    array(
                        'label' => Yii::t('newsletter', 'Newsletter'),
                    ),
                    array(
                        'label' => '<i class="fa fa-pencil"></i>&nbsp;'
                            .Yii::t('newsletter', 'Posts'),
                        'url' => $this->createUrl('newsletter/posts'),
                    ),
                    array(
                        'label' => '<i class="fa fa-envelope-o"></i>&nbsp;'
                            .Yii::t('newsletter', 'Readers'),
                        'url' => $this->createUrl('newsletter/readers'),
                    ),
                ),
                'encodeLabel' => false,
                'htmlOptions' => array(
                    'class' => 'well bg-transparent box',
                ),
            )
        ); ?>
    </div>
    
    <div class="span9">
        <?php  echo $content;?>
    </div>
</div>


<?php $this->endContent(); ?>