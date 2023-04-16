<?php 
/**
 * Element listy typu Editable.
 *
 * @category views
 * @package test
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div>
    <?php $this->widget('EditableField', array(
        'type'      => 'email',
        'model'     => $data,
        'attribute' => 'email',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'password',
        'model'     => $data,
        'attribute' => 'password',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'select2',
        'model'     => $data,
        'attribute' => 'type',
        'source'    => array('1' => 'Customer', '2' => 'Admin'),
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'image',
        'model'     => $data,
        'attribute' => 'profile_picture',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'link',
        'model'     => $data,
        'attribute' => 'website',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'toggle',
        'model'     => $data,
        'attribute' => 'active',
        'url'       => $this->createUrl('test/update'),
        'fade'      => 0,
        'red'       => 0,
        'blue'      => 1,
        'closestFadeSelector' => 'div',
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'checkbox',
        'model'     => $data,
        'attribute' => 'ban',
        'url'       => $this->createUrl('test/update'),
        'red'       => 1,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'text',
        'model'     => $data,
        'attribute' => 'iban',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'date',
        'model'     => $data,
        'attribute' => 'born',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'datetime',
        'model'     => $data,
        'attribute' => 'registered',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />

    <hr />
</div>