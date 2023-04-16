<?php 
/**
 * Testy Editable.
 *
 * @category views
 * @package test
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<h1>Editable Test</h1>

<h2>Editable Field</h2>
<h3>Enabled</h3>

<div>
    <?php $this->widget('EditableField', array(
        'type'      => 'email',
        'model'     => $user,
        'attribute' => 'email',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'password',
        'model'     => $user,
        'attribute' => 'password',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'select2',
        'model'     => $user,
        'attribute' => 'type',
        'source'    => array('1' => 'Customer', '2' => 'Admin'),
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'image',
        'model'     => $user,
        'attribute' => 'profile_picture',
        'url'       => $this->createUrl('test/update'),
        'scaleMaxWidth' => 200,
        'scaleMaxHeight' =>  100,
        'convertTo' => 'jpg',
        'convertTo' => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'link',
        'model'     => $user,
        'attribute' => 'website',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'toggle',
        'model'     => $user,
        'attribute' => 'active',
        'url'       => $this->createUrl('test/update'),
        'fade'      => 0,
        'red'       => 0,
        'blue'      => 1,
        'closestFadeSelector' => 'div',
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'checkbox',
        'model'     => $user,
        'attribute' => 'ban',
        'url'       => $this->createUrl('test/update'),
        'red'       => 1,
        'blue'      => 0,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'text',
        'model'     => $user,
        'attribute' => 'iban',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'date',
        'model'     => $user,
        'attribute' => 'born',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'datetime',
        'model'     => $user,
        'attribute' => 'registered',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
     <?php $this->widget('EditableField', array(
        'type'      => 'email',
        'value'     => 'test',
        'url'       => $this->createUrl('test/update'),
    )); ?><br />
</div>

<h3>Disabled</h3>
<div>
    <?php $this->widget('EditableField', array(
        'type'      => 'email',
        'model'     => $user,
        'attribute' => 'email',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'password',
        'model'     => $user,
        'attribute' => 'password',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'select2',
        'model'     => $user,
        'attribute' => 'type',
        'source'    => array('1' => 'Customer', '2' => 'Admin'),
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'image',
        'model'     => $user,
        'attribute' => 'profile_picture',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'link',
        'model'     => $user,
        'attribute' => 'website',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'toggle',
        'model'     => $user,
        'attribute' => 'active',
        'url'       => $this->createUrl('test/update'),
        'fade'      => 0,
        'red'       => 0,
        'blue'      => 1,
        'apply'     => false,
        'closestFadeSelector' => 'div',

    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'checkbox',
        'model'     => $user,
        'attribute' => 'ban',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
        'red'       => 1,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'text',
        'model'     => $user,
        'attribute' => 'iban',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'date',
        'model'     => $user,
        'attribute' => 'born',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
    <?php $this->widget('EditableField', array(
        'type'      => 'datetime',
        'model'     => $user,
        'attribute' => 'registered',
        'url'       => $this->createUrl('test/update'),
        'apply'     => false,
    )); ?><br />
</div>

<hr />

<h2>Editable List View</h2>
<h3>Enabled</h3>
<?php $this->widget('EditableListView', array(
    'id' => 'user-list-view',
    'dataProvider' => new CActiveDataProvider('User', array(
        'pagination' => array(
            'pageSize' => 1,
            'route' => 'test/index',
        ),
    )),
    'itemView' => '/test/_listItem',
)); ?>

<hr />

<h2>Editable Detail View</h2>
<h3>Enabled</h3>
<?php $this->widget('EditableDetailView', array(
    //'id' => 'user-detail',
    'data'       => $user,
    'editableApply'   => true,
    'editableUrl'        => $this->createUrl('test/update'),
    'attributes' => array(
        array(
            'name' => 'email',
            'editableType' => 'email',
        ),
        array(
            'name' => 'password',
            'editableType' => 'password',
        ),
        array(
            'name' => 'type',
            'editableType' => 'select2',
            'editableSource' => array('1' => 'Customer', '2' => 'Admin'),
        ),
        array(
            'name' => 'profile_picture',
            'editableType' => 'image',
        ),
        array(
            'name' => 'website',
            'editableType' => 'link',
        ),
        array(
            'name' => 'active',
            'editableType' => 'toggle',
            'editable' => array(
                'fade' => 0,
                'closestFadeSelector' => 'table',
                'red'       => 0,
                'blue'      => 1,
            ),
        ),
        array(
            'name' => 'ban',
            'editableType' => 'checkbox',
            'editable' => array(
                'red' => 1,
            ),
        ),
        array(
            'name' => 'iban',
            'editableType' => 'text',
        ),
        array(
            'name' => 'born',
            'editableType' => 'date',
        ),
        array(
            'name' => 'registered',
            'editableType' => 'datetime',
        ),
    ),
)); ?>
<h3>Disabled</h3>
<?php $this->widget('EditableDetailView', array(
    'id' => 'user-detail-2',
    'data'       => $user,
    'editableApply'   => false,
    'attributes' => array(
        array(
            'name' => 'email',
            'editableType' => 'email',
        ),
        array(
            'name' => 'password',
            'editableType' => 'password',
        ),
        array(
            'name' => 'type',
            'editableType' => 'select2',
            'editableSource' => array('1' => 'Customer', '2' => 'Admin'),
        ),
        array(
            'name' => 'profile_picture',
            'editableType' => 'image',
        ),
        array(
            'name' => 'website',
            'editableType' => 'link',
        ),
        array(
            'name' => 'active',
            'editableType' => 'toggle',
            'editable' => array(
                'fade' => 0,
                'closestFadeSelector' => 'table',
                'red'       => 0,
                'blue'      => 1,
            ),
        ),
        array(
            'name' => 'ban',
            'editableType' => 'checkbox',
            'editable' => array(
                'red' => 1,
            ),
        ),
        array(
            'name' => 'iban',
            'editableType' => 'text',
        ),
        array(
            'name' => 'born',
            'editableType' => 'date',
        ),
        array(
            'name' => 'registered',
            'editableType' => 'datetime',
        ),
    ),
)); ?>

<hr />

<h2>Editable Column</h2>
<h3>Enabled</h3>
<?php $this->widget('EditableGridView', array(
    'id'=>'user-list',
    'dataProvider' => new CActiveDataProvider('User', array(
        'pagination' => array(
            'pageSize' => 1,
            'route' => 'test/index',
        ),
    )),
    'editableApply' => true,
    'editableUrl' => $this->createUrl('test/update'),
    'columns' => array(
        array(
            'name' => 'email',
            'editableType' => 'email',
        ),
        array(
            'name' => 'password',
            'editableType' => 'password',
        ),
        array(
            'name' => 'type',
            'editableType' => 'select2',
            'editableSource' => array('1' => 'Customer', '2' => 'Admin'),
        ),
        array(
            'name' => 'profile_picture',
            'editableType' => 'image',
        ),
        array(
            'name' => 'website',
            'editableType' => 'link',
        ),
        array(
            'name' => 'active',
            'editableType' => 'toggle',
            'editable' => array(
                'fade' => 0,
                'closestFadeSelector' => 'tr',
                'red'       => 0,
                'blue'      => 1,
            ),
        ),
        array(
            'name' => 'ban',
            'editableType' => 'checkbox',
            'editable' => array(
                'red' => 1,
            ),
        ),
        array(
            'name' => 'iban',
            'editableType' => 'text',
        ),
        array(
            'name' => 'born',
            'editableType' => 'date',
        ),
        array(
            'name' => 'registered',
            'editableType' => 'datetime',
        ),
    ),
)); ?>
<h3>Disabled</h3>
<?php $this->widget('EditableGridView', array(
    'id'=>'user-list-2',
    'dataProvider' => new CActiveDataProvider('User', array(
        'pagination' => array(
            'pageSize' => 1,
            'route' => 'test/index',
        ),
    )),
    'editableApply' => false,
    'columns' => array(
        array(
            'name' => 'email',
            'editableType' => 'email',
        ),
        array(
            'name' => 'password',
            'editableType' => 'password',
        ),
        array(
            'name' => 'type',
            'editableType' => 'select2',
            'editableSource' => array('1' => 'Customer', '2' => 'Admin'),
        ),
        array(
            'name' => 'profile_picture',
            'editableType' => 'image',
        ),
        array(
            'name' => 'website',
            'editableType' => 'link',
        ),
        array(
            'name' => 'active',
            'editableType' => 'toggle',
            'editable' => array(
                'fade' => 0,
                'closestFadeSelector' => 'tr',
                'red'       => 0,
                'blue'      => 1,
            ),
        ),
        array(
            'name' => 'ban',
            'editableType' => 'checkbox',
            'editable' => array(
                'red' => 1,
            ),
        ),
        array(
            'name' => 'iban',
            'editableType' => 'text',
        ),
        array(
            'name' => 'born',
            'editableType' => 'date',
        ),
        array(
            'name' => 'registered',
            'editableType' => 'datetime',
        ),
    ),
)); ?>
