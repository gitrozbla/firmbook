<?php
/**
 * Klucze wewnętrzne systemu.
 * 
 * Dla autoryzacji wymiany danych.
 *
 * @category config
 * @package system\config\user
 * @author BAI
 * @copyright (C) 2014 BAI
 */
 
return array(
	// login systemowy i klucz (indywidualne hasło do systemu bai, używane przez system)
	'publicKey'=>'',
	'privateKey'=>'',
	'systemSalt'=>'****',	// 32 hex chars, like md5('whatever54frse')
);
