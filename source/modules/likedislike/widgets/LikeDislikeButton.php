<?php
/**
 *
 * @author Hemc 
 * 
 */

/**
 * Widget that renders the node with the given name.
 */
class LikeDislikeButton extends CWidget
{
	/**
	 * @property Int Id of the post
	 */
	public $post_id;
        
        /**
	 * @property String Type of the post(like on coment, like on post)
	 */
	public $post_type;
        
	/**
	 * Runs the widget.
	 */
	public function run()
	{
		if(!Yii::app()->user->isGuest){
			$assetsurl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('likedislike.assets') );
			Yii::app()->clientScript->registerScriptFile($assetsurl.'/js/likedislike.js', CClientScript::POS_HEAD);
			$userLikeIt = Yii::app()->getModule('likedislike')->defaultOnload($this->post_id,  $this->post_type);
			?>
            <span>            	 
                <a href="javascript:void(0);" onclick="likedislike('<?php echo $this->post_id; ?>','<?php echo $this->post_type; ?>')">
                	<i class="fa fa-thumbs-up"<?php if(!$userLikeIt) echo 'style="color: blue;"' ?>></i>&nbsp;
                    <?php /*<span id="displaytext_<?php echo $this->post_id; ?>_<?php echo $this->post_type; ?>"><?php echo Yii::app()->getModule('likedislike')->defaultOnload($this->post_id,  $this->post_type); ?></span>*/?>
                </a>
                <small>
                    <span id="likedislikecount_<?php echo $this->post_id; ?>_<?php echo $this->post_type; ?>"><?php echo Yii::app()->getModule('likedislike')->countlikes($this->post_id,  $this->post_type); ?></span>
                </small>
                <input type="hidden" id="mybaseurl" value="<?php echo Yii::app()->baseUrl; ?>/_likedislike/default/likedislike">
            </span>
			<?php
		} else {
			?>
			<small><i class="fa fa-thumbs-up"></i>&nbsp; <?php echo Yii::app()->getModule('likedislike')->countlikes($this->post_id,  $this->post_type);?></span> <?php //echo Yii::t('like', 'Likes')?></small>
			<?php
        }
	}
}
