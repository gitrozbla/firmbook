                    
<?php $likesCount = Elist::model()->count(
                array(
                        'condition' => 'type=:type and item_id=:item_id and item_type=:item_type',
                        'params' => array(':type'=>Elist::TYPE_FAVORITE, ':item_type'=>$itemType, ':item_id'=>$item->id)
)); ?>
<div>
    <?php //var_dump($item)?>
    <a href="<?php echo $this->createUrl('elists/inverselist', array('type' => Elist::TYPE_FAVORITE, 'itype'=>$itemType, 'id'=>$item->id)) ?>">                        
            <span id="inverse-btn-favorite"><i class="fa fa-heart fa-1x"></i>&nbsp; <?php echo $likesCount ?></span>
            <?php echo Yii::t('elists', 'people like this'); ?>
        </a>    
</div>
<?php /*$onElistCount = Elist::model()->count(
                            array(
                                    'condition' => 'type=:type and item_id=:item_id and item_type=:item_type',
                                    'params' => array(':type'=>Elist::TYPE_ELIST, ':item_type'=>$itemType, ':item_id'=>$item->id)
            ));*/ ?>
<?php /*<div>
    <a href="<?php echo $this->createUrl('elists/inverselist', array('type' => Elist::TYPE_ELIST, 'itype'=>$itemType, 'id'=>$item->id)) ?>">                        
            <span id="inverse-btn-elist"><i class="fa fa-list fa-1x"></i>&nbsp; <?php echo $onElistCount ?></span>
            <?php echo Yii::t('elists', 'people added to the elist'); ?>
        </a>
</div>*/ ?>
<?php $followersCount = Follow::model()->count(
                            array(
                                    'condition' => 'item_id=:item_id and item_type=:item_type',
                                    'params' => array(':item_type'=>$itemType, ':item_id'=>$item->id)
            )); ?>
<div>
    <?php /*$this->beginWidget('bootstrap.widgets.TbButton',array(
            'buttonType' => 'link',
            'url' => '',
            'encodeLabel' => false,
            'label' => 'a<br>b' 				
    ));*/ ?>
    <?php /*<a href="<?php echo $this->createUrl('follow/inverselist', array('itype'=>$itemType, 'id'=>$item->id)) ?>">                        
            <span id="inverse-btn-follow"><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $followersCount ?></span>
            <?php echo Yii::t('elists', 'people are watching this'); ?>
        </a>*/ ?>
    <span id="inverse-btn-follow"><i class="fa fa-eye fa-1x"></i>&nbsp; <?php echo $followersCount ?></span>
            <?php echo Yii::t('elists', 'people are watching this'); ?>
    <?php //$this->endWidget(); ?>
</div>
