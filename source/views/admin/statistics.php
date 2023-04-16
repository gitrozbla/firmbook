<h1><i class="fa fa-bar-chart-o"></i> <?php echo Yii::t('admin', 'Statistics'); ?></h1>
<hr />

<h2><i class="fa fa-star-o"></i> <?php echo Yii::t('Stats', 'Users and packages'); ?></h2>
<div class="row">
    <div class="span6">
        <?php require 'currentPackagesChart.php'; ?>
    </div>
    
    <div class="span6">
        <?php require 'lastMonthPackagesChart.php'; ?>
    </div>
</div>