<?php 
    $packages = Package::model()->findAllAsArray();
    
    // index packages
    $indices = array();
    foreach($packages as $index=>$package) {
        $indices[$package['id']] = $index;
    }

    // generate data and colors
    $stats = Stats::model()->findLast();
    $data = array();
    $colors = array();
    foreach($stats->package_owners as $packageId=>$count) {
        $data []= array(
            'label' => Yii::t(
                    'package.name', 
                    $packages[$indices[$packageId]]['name'], 
                    null, 
                    'dbMessages'),
            'value' => $count
            );
        $colors []= $packages[$indices[$packageId]]['stats_color'];
    }
    $data []= array(
        'label' => Yii::t('stats', 'None'),
        'value' => $stats->users - array_sum($stats->package_owners)
        );
    $colors []= 'lightgray';
?>
<div class="row-fluid">
    <div class="span6">
        <?php $this->widget('Chart', array(
            'options' => array(
                'chartType' => 'Donut',
                'data' => $data,
                'colors' => $colors,
            ),
            //'select' => 0,
            'htmlOptions' => array('style'=>'height:250px;')
        )); ?>
    </div>

    <div class="span6">
        <ul>
            <?php foreach($data as $index=>$data): ?>
            <li>
                <?php echo '<b style="color:'.$colors[$index].'">'
                            .$data['label']
                        .'</b>:&nbsp;'.$data['value']
                        .'&nbsp;('.number_format($data['value']/($stats->users)*100.0).'%)'; ?>
            </li>
            <?php endforeach; ?>
            <li>
                <?php echo Yii::t('stats', 'Total').':&nbsp;'.$stats->users; ?>
            </li>
        </ul>
    </div>
</div>