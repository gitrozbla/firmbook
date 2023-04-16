SIMPLE LIKEDISLIKE

DEPENDENCIES
1. yii-user module
2. jquery.js
3. url without index.php and urlmanager enabled in config/main.php

INSTALLATION
1. Unzip
2. Copy the likedislike folder to protected/modules/
3. under config/main/
	'modules'=>array(
		'likedislike',
	),
4. Import protected/modules/data/tbl_likedislike.sql
5. You are ready to go

USAGE

To display comment on post 
<?php $this->widget('likedislike.widgets.LikeDislikeButton', array('post_id' => 5, 'post_type' => 'post')); ?>

To display comment on the comment.
<?php $this->widget('likedislike.widgets.LikeDislikeButton', array('post_id' => 5, 'post_type' => 'comment')); ?>


Can use for other models by changing  post_type.