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
    'Host' => 'smtp.firmbook.pl',
    //'SMTPSecure' => 'ssl',
    'Port' => 587,
    'SMTPAuth' => true,
    'Username' => '****',
    'Password' => '****',
    'FromDefault' => '****',
);