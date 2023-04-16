<?php
/**
 * Parametry mechanizmu pakietów.
 *
 * @category config
 * @package 
 * @author 
 * @copyright (C) 2015
 */
 
// parametry dostępne w aplikacji i przez GUI
return array(
    // 
    'dotpayId'=>'****',
    'dotpayPin'=>'****',
	'dotpayStatus'=>array(
		'completed'=>1,
		'rejected'=>2
	),
	'dotpayStatusCompleted'=>'completed',
	'dotpayStatusRejected'=>'rejected',
	//'dotpayStatusCompletedInt'=>1,
	//'dotpayStatusRejectedInt'=>2,
	//informacja o wygaśnięciu pakietu x dni przed wygaśnięciem
	'expireAfter'=>array('3','7','14','30','60'),
	
    
	
);
