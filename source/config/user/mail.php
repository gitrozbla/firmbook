<?php

/**
 * Konfiguracja poczty wychodzącej.
 *
 * @category config
 * @package system\config\user
 * @author BAI
 * @copyright (C) 2014 BAI
 */

return array(
	'Mailer' => 'smtp',
	'Host' => 'smtp.firmbook.eu',
	//'SMTPSecure' => 'ssl',
	'Port' => 587,
	'SMTPAuth' => true,
	'Username' => 'www@firmbook.eu',
//    'Username' => 'kontakt@firmbook.eu',
	'Password' => 'F44Mbookeu',
	'FromDefault' => 'www@firmbook.eu',
//    'FromDefault' => 'kontakt@firmbook.eu',
	/*'Mailer' => 'smtp',
    'Host' => 'smtp.firmbook.pl',
    //'SMTPSecure' => 'ssl',
    'Port' => 587,
    'SMTPAuth' => true,
    'Username' => 'bok@firmbook.pl',
    'Password' => 'F44Mbookeu',
    'FromDefault' => 'bok@firmbook.pl',*/

    /*'Mailer' => 'smtp',
    'Host' => 'crypto.kylos.pl',
    'SMTPSecure' => 'ssl',
    'Port' => 465,
    'SMTPAuth' => true,
    'Username' => 'system@red.krt.pl',
    'Password' => 'testBai1234',
    'FromDefault' => 'system@red.krt.pl',*/
);