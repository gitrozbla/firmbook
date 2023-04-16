<?php

class StatsCollector extends CComponent
{
    
    public function init()
    {
        $this->collectData();
    }
    
    public function collectData()
    {
        //$currentTime = time();
        
        $currentDate = date('Y-m-d');
        
        // check last collected data
        $db = Yii::app()->db;
        $date = $db->createCommand()
                ->select('date')
                ->from('tbl_stats')
                ->where('date=:current_date', array(
                    ':current_date' => $currentDate,
                ))
                ->limit(1)
                ->queryScalar();
        if (!$date) {
            $date = 0;
        }
        //var_dump($date);exit();
        
        if (empty($date)) {
            // no stats today
            
            $statsLimitDate = date('Y-m-d', strtotime('-5 years'));
            $db->createCommand()->delete('tbl_stats', 
                    'date<:limit_date',
                    array(':limit_date' => $statsLimitDate));

            $packageOwnersData = $db->createCommand()
                    ->select('package_id, count(*) as count')
                    ->from('tbl_user')
                    ->where('active=1 AND package_id IS NOT NULL')
                    ->group('package_id')
                    ->order('package_id ASC')
                    ->queryAll();
            $packageOwners = array();
            foreach($packageOwnersData as $packageOwner) {
                $packageOwners[$packageOwner['package_id']] 
                        = $packageOwner['count'];
            }
                
            $db->createCommand()
                    ->insert('tbl_stats', array(
                'users' => User::model()->count("active=1"),
                'package_owners' => CJSON::encode($packageOwners),
                'date' => $currentDate,
            ));
        }
        
        // old version
        // round to day + hour (to avoid time shift problems)
        /*$day = 86400;
        $hour = 3600;
        $dateRound = strtotime('today', $date) + $hour;
        $currentDateRound = strtotime('today', $currentTime) + $hour;*/
        /*var_dump(date('d.m.y H:i:s', time()));
        var_dump(date('d.m.y H:i:s', $dateRound));
        var_dump(date('d.m.y H:i:s', $currentDateRound));*/
        
        // old version
        /*while ($date <= $currentDate) {
            
            // nextDate
            $dateRound += $day;
            
            // first iteration
            if (!isset($firstIteration)) {
                
                // first date: today - 30 days
                if ($dateRound < $currentDateRound - $day * 30) {
                    $dateRound = $currentDateRound - $day * 30;
                }
                
                // additionally, remove old stats
                $statsLimitDate = $currentDateRound - $day * 365;
                $db->createCommand()
                        ->delete('tbl_stats', 
                                'date<:date',
                                array('date' => $statsLimitDate));
                
                $packageOwnersData = $db->createCommand()
                        ->select('package_id, count(*)')
                        ->from('tbl_user')
                        ->where('active=1 AND package_id IS NOT NULL')
                        ->group('package_id')
                        ->order('package_id ASC')
                        ->queryAll();
                $packageOwners = array();
                foreach($packageOwnersData as $packageOwner) {
                    $packageOwners[$packageOwner['package_id']] 
                            = $packageOwner['count(*)'];
                }
                
                $rows = array(
                    'users' => User::model()->count("active=1"),
                    'package_owners' => CJSON::encode($packageOwners),
                );
                
                $firstIteration = true;
            }
            //echo '<br /> '.date('d.m.y H:i:s', $dateRound);
            
            // save Data
            $rows['date'] = $dateRound;
            $db->createCommand()
                    ->insert('tbl_stats', $rows);
        }*/
    }
    
}
