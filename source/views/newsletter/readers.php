<?php 
/**
 * Lista czytelników newslettera.
 *
 * @category views
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<h1><?php echo Yii::t('newsletter', 'Readers'); ?> </h1>
<hr />
<?php echo Html::coolAjaxLink(
        '<i class="fa fa-plus"></i>&nbsp;'.Yii::t('newsletter', 'Add reader'),
        $this->createUrl('add_reader')
        ); ?>
<hr />
<?php
    $widget = $this->widget('EditableGridView', array(
        'id' => 'reader-list',
        'dataProvider' => $reader->search(),
        'editableUrl' => $this->createUrl('newsletter/update_reader'),
        'columns' => array(
            array(
                'name' => 'email',
                'editableType' => 'email',
                'editable' => array(
                    'emptytext' => Yii::t('newsletter', 'Type e-mail'),
                    ),
                ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{delete}',
                'buttons' => array(
                    'delete' => array(
                        'url' => 'Yii::app()->createUrl("newsletter/remove_reader", array("id"=>$data->id))',
                    ),
                ),
            ),
        ),
    ));
?>