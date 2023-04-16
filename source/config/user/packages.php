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
	//produkcja
	'dotpayId'=>'402352',
	'dotpayPin'=>'vGBEfkaLaeCoC6WHJKNbhvsgxsMScJXY',
    //'dotpayId'=>'729944', //test
    //'dotpayPin'=>'m2z36S2O9SPke3dsHuRShJnlNxz9GxUK', //test
	'dotpayStatus'=>array(
		'completed'=>1,
		'rejected'=>2
	),
	'dotpayStatusCompleted'=>'completed',
	'dotpayStatusRejected'=>'rejected',
	//'dotpayStatusCompletedInt'=>1,
	//'dotpayStatusRejectedInt'=>2,
	'dotpayPaymentUrl' => 'https://ssl.dotpay.pl/',
	//'dotpayPaymentUrl' => 'https://ssl.dotpay.pl/payment/index.php',
	//'dotpayPaymentUrl' => 'https://ssl.dotpay.pl/test_payment/', //test
	//'dotpayPaymentTitle' => 'Opłata za pakiet',
		
	//informacja o wygaśnięciu pakietu x dni przed wygaśnięciem
	//progi muszą być wprowadzone malejąco
	'expireAfter'=>array('60','30','14','7','3'),   
		
	'defaultPackageCreators' => 5,
);
