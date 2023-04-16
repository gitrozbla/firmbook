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

<div class="container error-container text-center">
    <p>
        <h1>
            <i class="fa fa-times"></i>
            <?php echo Yii::t('error', 'Error').' '.$code ?>
        </h1>
    </p>
    <p>
        <?php
        switch ($code) {
            case 404:
                if ($message) {
                    echo '<h2>'.$message.'</h2>';
                } else {
                    echo '<h2>'.Yii::t('error', 'Page does not exist.').'</h2>';
                }
                //echo '('.Yii::t('error', 'Error').' '.$code.')<br />';
                break;

            case 400:
                echo '<h2>'.Yii::t('error', 'Your request is invalid. Please makse sure that url is correct.').'</h2>';
                //echo '('.Yii::t('error', 'Error').' '.$code.')';
                break;

            case 403:
                echo '<h2>'.Yii::t('error', 'You are not allowed to perform this operation.').'</h2>';
                //echo '('.Yii::t('error', 'Error').' '.$code.')';
                break;

            default:
                echo '<h2>'.Yii::t('error', 'An error has occured.').'</h2>';
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
    <p>
        <?php echo Yii::t('error', 'Go to') . ' ' . CHtml::link(Yii::t('error', 'homepage'), Yii::app()->homeUrl) . '.'; ?>
    </p>
</div>
