<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>" 
    <?php if (false) {
        echo 'class="no-responsive"';
    } ?>
>
    <head>
        <?php require 'partials/head.php'; ?>
    </head>

    <body class="layout-2 page-type-<?php echo $this->page->type; ?>">
        <?php require('partials/header.php'); ?>
        
        <div class="container">
            
            <div class="row">
                <div class="span3">
                    <?php $this->widget(
                        'bootstrap.widgets.TbMenu',
                        array(
                            'type' => 'tabs',
                            'encodeLabel' => false,
                            'items' => $website->getPagesForMenu($this->page->id, $this->previewMode),
                            'htmlOptions' => array('class'=>'main-menu nav-stacked')
                        )
                    ); ?>
                    
                    <?php if ($this->sideContent) {
                        echo $this->sideContent;
                    } ?>
                </div>
                
                <div class="span9">
                    <div id="content">
                        <?php echo $content; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
                
            </div>
        
            <hr />
            <div id="footer">
                <?php $this->widget(
                    'bootstrap.widgets.TbMenu',
                    array(
                        'type' => 'pills',
                        'encodeLabel' => false,
                        'items' => $website->getPagesForMenu($this->page->id, $this->previewMode),
                        'htmlOptions' => array('class'=>'main-menu footer-menu')
                    )
                ); ?>
                
                <?php require 'partials/footer.php'; ?>
                
                <div class="clearfix"></div>
            </div>

            <?php require 'partials/bottom.php'; ?>
        </div>

    </body>
</html>
