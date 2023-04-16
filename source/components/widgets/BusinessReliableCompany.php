<?php
/**
 * Widget BusinessReliableCompany
 * 
 * 
 * @category components
 * @package components\widgets
 * @author 
 * @copyright (C) 
 */
class BusinessReliableCompany extends CWidget 
{
    public $display = true;
    public $asBadge = false;
    public $inline = false;
    
    public function run() {
        if(!$this->display)
            return;
        if($this->asBadge)
        { 
            if(!$this->inline)
                echo '<span class="pull-right" style="margin-right:5px"><img src="/images/icons/tick.png" title="'.Yii::t("company", "E-business reliable company").'"></span>';           
            else
                echo '<span style="margin-left:15px"><img src="/images/icons/tick.png" title="'.Yii::t("company", "E-business reliable company").'"></span>';           
        } else
            echo '<div class="alert alert-info">  
                <img src="/images/icons/tick.png" />
                <strong>'.Yii::t("company", "E-business reliable company").'</strong>
                </div>';           
    }
    
}
