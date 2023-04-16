<?php 
/**
 * Strona firmy.
 *
 * @category views
 * @package company
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php 
    $item = $company->item; 
    $editor = Yii::app()->session['editor'];
    //$updateUrl = $this->createUrl('companies/update');
    $updateUrl = $this->createUrl('companies/update', array('id'=>$item->id));
    $itemUpdateUrl = $this->createUrl('companies/update', array('model'=>'Item'));
    $companyUpdateUrl = $this->createUrl('companies/update', array('model'=>'Company'));
    $user = $item->user;
?>

<div class="row">
    <div class="span9">
<?php    
    	// if controller does not inherit from Controller, 
    // there will be no $this->editorEnabled at all.
    if (isset($this->editorEnabled) 
            && $this->editorEnabled 
            && !Yii::app()->user->isGuest): ?>

        <?php $editor = Yii::app()->session['editor']; ?>

        <div class="editor-button pull-right">
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    //'type' => 'primary',
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('editor', 'Edit'),
                	'url' => $updateUrl,
                    //'url' => $this->createUrl('companies/update'),
                    //'active' => $editor,
                    'type' => 'success',
                    
                )
        ); ?>
        
    </div>
<?php endif; ?>
        <?php //$this->renderPartial('/site/_editorButton'); ?>
        
        <?php if ($editor || $item->thumbnail != null) : ?>
            <div class="thumbnail pull-left right-30 bottom-10">
                <?php $this->widget('EditableImage', array(
                    'model'     => $item,
                    'attribute' => 'thumbnail_file_id',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                    'url'       => $itemUpdateUrl,
                    'apply'     => $editor,
                )); ?>
            </div>
        <?php endif; ?>

        <div class="clearfix"></div>

        <h1 class="<?php echo $item->getPackageItemClass(); ?>">
            <?php $this->widget('EditableField', array(
                'model'     => $item,
                'attribute' => 'name',
                'url'       => $itemUpdateUrl,
                'emptytext' => Yii::t('company', $editor ? 'Type company name' : 'No name'),
                'apply'     => $editor,
            )); ?> 

            <?php if ($editor || !$item->active) : ?>
            <span class="nowrap">(<?php $this->widget('EditableToggle', array(
                    'model'     => $item,
                    'attribute' => 'active',
                    'url'       => $itemUpdateUrl,
                    'apply'     => $editor,
                    'red'       => 0,
                    'toggleSize'=> 'normal',
                    /*'fade'      => 0,
                    'fadeSelector' => '.content',*/
                    'source'    => array(
                        '0' => Yii::t('item', 'Inactive'), 
                        '1' => Yii::t('item', 'Active'),
                    )
                )); ?>)</span>
            <?php endif; ?>
        </h1>

        <?php if ($editor) : ?>
            <p class="muted">
                <?php echo Yii::t('item', 'Alias'); ?>: 
                <?php $this->widget('EditableField', array(
                    'model'     => $item,
                    'attribute' => 'alias',
                    'url'       => $itemUpdateUrl,
                    'apply'     => $editor,
                    'success' => "function(){
                        reload(
                            (location.href).substr(0, (location.href).lastIndexOf('/')+1)
                            +
                            $('.alias-field').next().find('input').val()
                        );
                    }",
                    'htmlOptions' => array(
                        'class' => 'alias-field',
                    ),
                )); ?>
            </p>
        <?php endif; ?>

        <span class="muted">
            <?php echo Yii::t('company', 'Company ID'); ?>: <?php echo $item->id; ?>
        </span>

        <?php if ($editor) : ?>
            <p class="muted">
                <?php echo Yii::t('category', 'Category'); ?>: 
                <?php $this->widget('EditableTree', array(
                    'model'     => $item,
                    'attribute' => 'category_id',
                    'url'       => $itemUpdateUrl,
                    'source'    => Yii::app()->createUrl(
                            'categories/json_get_subcategories'),
                    'secondModel' => 'Category',
                    'secondModelAttribute' => 'nameLocal',
                    'treeMode'  => 'nestedSet',
                    'emptytext' => Yii::t('item', 'Select category')
                )); ?>
            </p>
            <p class="muted">
                <?php echo Yii::t('item', 'Offer'); ?>: 
                <?php echo Yii::t('item', 'Buy'); ?> - 
                <?php $this->widget('EditableCheckbox', array(
                    'model'     => $item,
                    'attribute' => 'buy',
                    'url'       => $itemUpdateUrl,
                )); ?>
                ;
                <?php echo Yii::t('item', 'Sell'); ?> - 
                <?php $this->widget('EditableCheckbox', array(
                    'model'     => $item,
                    'attribute' => 'sell',
                    'url'       => $itemUpdateUrl,
                )); ?>
            </p>
        <?php endif; ?>

        <hr class="clearfix" />

        <?php if ($company->short_description || $editor) : ?>
            <h2><?php echo Yii::t('company', 'Short description'); ?></h2>
            <p><?php $this->widget('EditableField', array(
                    'type'      => 'textarea',
                    'model'     => $company,
                    'attribute' => 'short_description',
                    'url'       => $companyUpdateUrl,
                    'emptytext' => Yii::t('company', 'Short description here'),
                    'apply'     => $editor,
                )); ?></p>
        <?php endif; ?>
            
        <?php $this->widget('EditableDetailView', array(
            //'id' => 'user-detail',
            'data'       => $company,
            'editableUrl' => $companyUpdateUrl,
            //'editableAutoCheckAccess' => 'update',
            'editableApply'=>$editor,
            'attributes' => array(
                array(
                    'name' => 'email',
                    'editableType' => 'email',
                ),
                array(
                    'name' => 'phone',
                    'editableType' => 'phone',
                )
            ),
        )); ?>
            
        <?php if ($item->description || $editor) : ?>
            <h2><?php echo Yii::t('item', 'Description'); ?></h2>
            <?php /*echo $item->description;*/ ?>
            <?php $this->widget('EditableEditor', array(
                    'model'     => $item,
                    'attribute' => 'description',
                    'url'       => $itemUpdateUrl,
                    'emptytext' => Yii::t('company', 'Describe company here'),
                    'apply'     => $editor,
                )); ?>
        <?php endif; ?>
            
        <?php if (Yii::app()->user->checkAccess('Companies.remove', array('record'=>$company))) : ?>
            <hr />
            <div class="text-right">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'label' => Yii::t('companies', 'Remove this company'),
                    'url' => $this->createUrl(
                            '/companies/remove',
                            array('name'=>$item->alias, 'return'=>0)
                            ),
                    'type' => 'danger',
                    'icon' => 'fa fa-times',
                    'htmlOptions' => array(
                        'class' => 'confirm-box',
                        'data-message' => Yii::t(
                                'companies', 
                                '{remove-company?}', 
                                array(
                                    '{remove-company?}' => 
                                    'Are you sure you want to remove this company? '
                                    . 'You can also temporarily hide this offer '
                                    . 'by clicking \'inactive\' on company page.'
                                    )),
                    ))
                ); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="span3">
        <?php $this->renderPartial('/user/_userBox', compact('user')); ?>
    </div>
</div>
