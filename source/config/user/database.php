<?php
/**
 * Dostęp do bazy danych.
 *
 * @package system\config\user
 * @author BAI
 * @copyright (C) 2013 BAI
 */
 
//return array(
//	// Zabezpieczenie, aby nie zapomnieć zmienić połączenia do bazy.
//	// Tutaj należy ustawić domenę (bez http://www.), dla jakiej wykorzystywana jest baza.
//	'forDomain'=>array('firmset.eu', 'firmbook.eu'),	
//	
//	// konfiguracja dla MySQL
//	'connectionString' => 'mysql:host=mysql51-156.perso;dbname=firmbookzreu',
//	'emulatePrepare' => true,
//	'username' => 'firmbookzreu',
//	'password' => 'firmB44Keu',
//	'charset' => 'utf8',
//	'initSQLs'=>array('SET NAMES utf8'), 
//	//'enableProfiling'=>true, 
//);

return array(
	// Zabezpieczenie, aby nie zapomnieć zmienić połączenia do bazy.
	// Tutaj należy ustawić domenę (bez http://www.), dla jakiej wykorzystywana jest baza.
	'forDomain'=>array('firmbook.localhost', 'creators.localhost'),	
	
	// konfiguracja dla MySQL
	'connectionString' => 'mysql:host=localhost;dbname=firmbookzrnew',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => 'blaza1234',
	'charset' => 'utf8',
	'initSQLs'=>array('SET NAMES utf8'), 
	//'enableProfiling'=>true, 
);

// return array(
// 	// Zabezpieczenie, aby nie zapomnieć zmienić połączenia do bazy.
// 	// Tutaj należy ustawić domenę (bez http://www.), dla jakiej wykorzystywana jest baza.
// 	'forDomain'=>array('firmset.eu', 'firmbook.eu'),	
	
// 	// konfiguracja dla MySQL
// 	'connectionString' => 'mysql:host=firmbookzrnew.mysql.db;dbname=firmbookzrnew',
// 	'emulatePrepare' => true,
// 	'username' => 'firmbookzrnew',
// 	'password' => 'firmB44Keu',
// 	'charset' => 'utf8',
// 	'initSQLs'=>array('SET NAMES utf8'), 
// 	//'enableProfiling'=>true, 
// );