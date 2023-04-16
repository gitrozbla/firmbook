<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>" 
    <?php if (false) {
        echo 'class="no-responsive"';
    } ?>
>
    <head>
        <?php require 'partials/head.php'; ?>
    </head>

    <body class="layout-1 page-type-<?php echo $this->page->type; ?>">
        <?php require('partials/header.php'); ?>
        
        <div class="container">
            
            <?php $this->widget(
                'bootstrap.widgets.TbNavbar',
                array(
                    'brand' => false,
                    'fixed' => false,
                    'items' => array(
                        array(
                            'class' => 'bootstrap.widgets.TbMenu',
                            'encodeLabel' => false,
                            'items' => $website->getPagesForMenu($this->page->id, $this->previewMode)
                        )
                    ),
                    'htmlOptions' => array('id'=>'main-menu')
                )
            ); ?>
        
            <div class="row">
                <?php if ($this->sideContent) : ?>
                <div class="span3">
                    <?php echo $this->sideContent; ?>
                </div>
                <div class="span9">
                <?php else : ?>
                <div class="span12">
                <?php endif; ?>
                    <div id="content">
                        <?php echo $content; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            
            <hr />
            <div id="footer">
                <div class="pull-right">
                    <b><?php echo Yii::t('CreatorsModule.generator', 'Contact'); ?></b><br />
                    <?php
                        if ($email = $website->company->email) {
                            echo Yii::t('CreatorsModule.generator', 'E-mail')
                                    .': <b>'.Html::link($email, 'mailto:'.$email).'</b><br />';
                        }
                        if ($phone = $website->company->phone) {
                            echo Yii::t('CreatorsModule.generator', 'Phone')
                                    .': <b>'.Html::link($phone, 'tel:'.$phone).'</b><br />';
                        }
                    ?>
                </div>
                
                
                <?php require 'partials/footer.php'; ?>
                
                <div class="clearfix"></div>
            </div>

            <?php require 'partials/bottom.php'; ?>
        </div>

    </body>
</html>
