<?php
/**
 * Dostęp do bazy danych.
 *
 * @package system\config\user
 * @author BAI
 * @copyright (C) 2013 BAI
 */
 
return array(
	// Zabezpieczenie, aby nie zapomnieć zmienić połączenia do bazy.
	// Tutaj należy ustawić domenę (bez http://www.), dla jakiej wykorzystywana jest baza.
	'forDomain'=>array('****', '****'),	
	
	// konfiguracja dla MySQL
	'connectionString' => 'mysql:host****;dbname=****',
	'emulatePrepare' => true,
	'username' => '****',
	'password' => '****',
	'charset' => 'utf8',
	'initSQLs'=>array('SET NAMES utf8'), 
	//'enableProfiling'=>true, 
);
