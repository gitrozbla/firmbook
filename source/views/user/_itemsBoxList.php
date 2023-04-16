
<?php /*$link =  $this->createUrl('/categories/show/', array(
        'context' => Search::getContextOption($action, $type),
        'username' => $user->username,
));*/ ?>
<?php switch($type) {
    case 'product':
        $controller = 'products';
        $title = 'Products';
        $icon = 'shopping-cart';
        $noItemsMessage = 'No active products.';
		$type = 'p';
        break;
    case 'service':
        $controller = 'services';
        $title = 'Services';
        $icon = 'truck';
        $noItemsMessage = 'No active services.';
		$type = 's';
        break;
    case 'company':
        $controller = 'companies';
        $title = 'Companies';
        $icon = 'building-o';
        $noItemsMessage = 'No active companies.';
		$type = 'c';
        break;
} ?>

<?php $link =  $this->createUrl('/user/items/', array(
        'type' => $controller,
        'username' => $user->username,
)); ?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => Html::link(Yii::t($controller, $title), $link) 
			. ' <span class="badge badge-info">' . Item::model()->count("cache_type=:type AND user_id=:user_id", 
				array(':type'=>$type, ':user_id'=>$user->id)) . '</span>',
        'headerIcon' => 'fa fa-'.$icon,
        'htmlOptions' => array('class' => 'transparent'),
    	'htmlHeaderOptions' => array('class' => $user->getPackageItemClass()),
    )
);?>
    <ul class="small-list">
        <?php $data = Item::model()->userItemsDataProvider($user->id, $type, 3)->getData(); ?>
        <?php if (empty($data)) : ?>
            <?php echo Yii::t($controller, $noItemsMessage); ?>
        <?php endif; ?>
        <?php foreach($data as $item) : ?>
        <?php /*<li<?php if ($item->package) : ?> class="package-item-<?php echo $item->package->css_name; ?>"<?php endif; ?>>*/?>
        <li>
            <?php echo Html::link(
                    ($item->thumbnail
                        ?  Html::image($item->thumbnail->generateUrl('small'), $item->name).' '
                        : '')
                        .$item->name, 
                    $this->createUrl($controller.'/show', array(
                        'name' => $item->alias))
                    ); ?>
        </li>
        <?php endforeach; ?>
        <li>
            <?php echo Html::link('...', $link); ?>
        </li>
    </ul>

<?php $this->endWidget(); ?>