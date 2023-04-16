<div>
    <?php
        $stats = Stats::model()->findLastMonthAsArray();
        $packages = Package::model()->findAllAsArray();

        // generate data
        $data = array();
        foreach($stats as $statsRow) {
            $dataRow = array();
            $dataRow['date'] = $statsRow['date'];//date('Y-m-d', $statsRow['date']);
            foreach($statsRow['package_owners'] as $packageName=>$count) {
                $dataRow[$packageName] = $count;
            }
            $dataRow['none'] = $statsRow['users'] 
                    - array_sum($statsRow['package_owners']);

            $data []= $dataRow;
        }
        
        // generate ykeys, labels and colors
        $ykeys = array();
        $labels = array();
        $colors = array();
        foreach($packages as $package) {
            $ykeys []= $package['id'];
            $labels []= Yii::t('package.name', $package['name'], null, 'dbMessages');
            $colors []= $package['stats_color'];
        }
        $ykeys []= 'none';
        $labels []= Yii::t('stats', 'None');
        $colors []= 'lightgray';
    ?>
    <?php $this->widget('Chart', array(
        'options' => array(
            'chartType' => 'Line',
            'data' =>$data,
            'xkey' => 'date',
            'ykeys' => $ykeys,
            'labels' => $labels,
            'lineColors' => $colors,
        ),
        'htmlOptions' => array('style'=>'height:250px;')
    )); ?>
</div>
