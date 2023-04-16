<?php

Yii::import('ext.activerecord-relation-behavior.EActiveRecordRelationBehavior', true);

class ActiveRecordRelationBehavior extends EActiveRecordRelationBehavior
{
	public $useTransaction=false;
}
