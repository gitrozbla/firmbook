<?php 
/**
 * Strona produktu.
 *
 * @category views
 * @package product
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php 
    $item = $product->item; 
    $editor = Yii::app()->session['editor'];
    $updateUrl = $this->createUrl('products/update');
    $itemUpdateUrl = $this->createUrl('products/update', array('model'=>'Item'));
    $productUpdateUrl = $this->createUrl('products/update', array('model'=>'Product'));
    $user = $item->user;
    $company = $product->company;
?>

<div class="row">
    <div class="span9">
        <?php $this->renderPartial('/site/_editorButton'); ?>
        
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
                'emptytext' => Yii::t('product', $editor ? 'Type product name' : 'No name'),
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
            <?php echo Yii::t('product', 'Product ID'); ?>: <?php echo $item->id; ?>
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
            <p class="muted">
                <?php echo Yii::t('product', 'Offering company'); ?>: 
                <?php $this->widget('EditableSelect3', array(
                    'model'     => $product,
                    'attribute' => 'company_id',
                    'emptytext' => Yii::t('companies', 'not selected'),
                    'text'      => $product->company 
                        ? $product->company->item->name 
                        : null,
                    'source'    => $this->createUrl(
                            '/companies/json_companies_list', 
                            array('user_id'=>$item->user_id)),
                    'url'       => $productUpdateUrl,
                )); ?>
            </p>
        <?php endif; ?>

        <ul>
            <?php if ($product->code) : ?>
                <li><?php echo Yii::t('product', 'Product code').': '.$product->code; ?></li>
            <?php endif; ?>
        </ul>

        <hr class="clearfix" />


        <?php if ($item->description || $editor) : ?>
            <h2><?php echo Yii::t('item', 'Description'); ?></h2>
            <?php /*echo $item->description;*/ ?>
            <?php $this->widget('EditableEditor', array(
                    'model'     => $item,
                    'attribute' => 'description',
                    'url'       => $itemUpdateUrl,
                    'emptytext' => Yii::t('product', 'Describe product here'),
                    'apply'     => $editor,
                )); ?>
        <?php endif; ?>
            
        <?php if (Yii::app()->user->checkAccess('Products.remove', array('record'=>$product))) : ?>
            <hr />
            <div class="text-right">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'label' => Yii::t('products', 'Remove this product'),
                    'url' => $this->createUrl(
                            '/products/remove',
                            array('name'=>$item->alias, 'return'=>0)
                            ),
                    'type' => 'danger',
                    'icon' => 'fa fa-times',
                    'htmlOptions' => array(
                        'class' => 'confirm-box',
                        'data-message' => Yii::t(
                                'products', 
                                '{remove-product?}', 
                                array(
                                    '{remove-product?}' => 
                                    'Are you sure you want to remove this product? '
                                    . 'You can also temporarily hide this offer '
                                    . 'by clicking \'inactive\' on product page.'
                                    )),
                    ))
                ); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="span3">
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>
        
        <?php $this->renderPartial('/user/_userBox', compact('user')); ?>
    </div>
</div>
