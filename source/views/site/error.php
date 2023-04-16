<?php 
/**
 * Komunikat o błędzie.
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php
if ($code == 404 && $message) $this->pageTitle = Yii::app()->name . ' - ' . $message;
else $this->pageTitle = Yii::app()->name . ' - ' . Yii::t('error', 'Error');

$this->breadcrumbs = array(
    Yii::t('error', 'Error'),
);
?>

<div class="error">
    <p>
        <?php
        if ($message) 
        	echo '<h1>v/s/error.php'.$message.'</h1>';
        switch ($code) {
            case 404:
                if ($message) {
                    echo '<h1>'.$message.'</h1>';
                } else {
                    echo '<h1>'.Yii::t('error', 'Page does not exist.').'</h1>';
                }
                //echo '('.Yii::t('error', 'Error').' '.$code.')<br />';
                echo Yii::t('error', 'Go to') . ' ' . CHtml::link(Yii::t('error', 'homepage'), Yii::app()->homeUrl) . '.';
                break;

            case 400:
                echo '<h1>'.Yii::t('error', 'Your request is invalid. Please makse sure that url is correct.').'</h1>';
                //echo '('.Yii::t('error', 'Error').' '.$code.')';
                break;

            case 403:
                echo '<h1>'.Yii::t('error', 'You are not allowed to perform this operation.').'</h1>';
                //echo '('.Yii::t('error', 'Error').' '.$code.')';
                break;

            default:
                echo '<h1>'.Yii::t('error', 'An error has occured.').'</h1>';
                //echo '('.Yii::t('error', 'Error').' '.$code.')<br />';
                echo '<br />' . Yii::t('error', 'We have been informed about this '
                        . 'situation. If the problem repeats, please use our '
                        . 'contact form or send information to').' '
                . '<a href="mailto:' . Yii::app()->params['admin']['email'] . '">'
                . Yii::app()->params['admin']['email']
                . '</a>.';
        }
        ?>
    </p>
</div>
