<?php if($this->beginCache('css')) { ?>
    <?php foreach(Package::model()->findAll() as $package) : ?>
        .package-badge-<?php echo $package['css_name']; ?> {
            <?php echo $package['badge_css']; ?>
        }
	.package-badge2-<?php echo $package['css_name']; ?> {
            <?php echo $package['badge2_css']; ?>
        }
        .package-item-<?php echo $package['css_name']; ?> {
            <?php echo $package['item_css']; ?>
        }
    <?php endforeach; ?>
<?php $this->endCache(); } ?>
