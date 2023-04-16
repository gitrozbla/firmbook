<?php
/**
 * ## TbListView class file.
 *
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php) 
 */

//Yii::import('zii.widgets.CListView');
Yii::import('bootstrap.widgets.TbListView');

/**
 * Bootstrap Zii list view.
 *
 * @package booster.widgets.grouping
 */
class AppTbListView extends TbListView
{
	/**
	 * @var string the CSS class name for the pager container. Defaults to 'pagination'.
	 */
	public $pagerCssClass = 'pagination';

	/**
	 * @var array the configuration for the pager.
	 * Defaults to <code>array('class'=>'ext.bootstrap.widgets.TbPager')</code>.
	 */
	public $pager = array('class' => 'bootstrap.widgets.TbPager');

	/**
	 * @var string the URL of the CSS file used by this detail view.
	 * Defaults to false, meaning that no CSS will be included.
	 */
	public $cssFile = false;

	/**
	 * Renders the sorter.
	 */
	public function renderSorter()
	{
		if($this->dataProvider->getItemCount()<=0 || !$this->enableSorting || empty($this->sortableAttributes))
			return;
		echo CHtml::openTag('div',array('class'=>$this->sorterCssClass))."\n";
		echo $this->sorterHeader===null ? Yii::t('zii','Sort by: ') : $this->sorterHeader;
		echo "<ul>\n";
		$sort=$this->dataProvider->getSort();
                if(count($sort->getDirections()))
                    $sortDirection = $sort->getDirections();
		foreach($this->sortableAttributes as $name=>$label)
		{
			echo "<li>";
			if(is_integer($name))
				echo $sort->link($label);
			else
				echo $sort->link($name,$label);
			if(isset($sortDirection) && isset($sortDirection[$label]))
                        {
                            if($sortDirection[$label])
                                echo '&nbsp;<i class="fa fa-angle-down"></i>';
                            else
                                echo '&nbsp;<i class="fa fa-angle-up"></i>';
                        }
                        echo "</li>\n";
		}
		echo "</ul>";
		echo $this->sorterFooter;
		echo CHtml::closeTag('div');
	}	
	
}
