<?php 
/**
 * Layout główny dla wiadomości email.
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="pl" />
        <meta name="viewport" content="width=device-width">
    </head>
    
    <?php 
        $brand = Yii::app()->params['branding'];
        $colors = $brand['colors'];
    ?>

    <body style="font-family: Georgia, serif;">
        
        <div style="
                margin: 25px;
                padding: 3px;
                border: solid 1px #eee;
                background-color: white;
                box-shadow: 5px 5px 0px #EEE;
                overflow: hidden;
                border-radius: 8px;
                font-family: Verdana, Geneva, sans-serif;
        ">
            <div style="
                 padding: 0 25px; 
                 color: <?php echo $colors['logo']; ?>;
                 background-color: <?php echo $colors['top']; ?>;
                 border-radius: 8px 8px 0 0;
            ">
                <?php Yii::app()->mailer->AddEmbeddedImage('images/branding/email-logo.png', 'logo_'.Yii::app()->name); ?>
                <h1 style="
                    margin: 0;
				"><a href="<?php echo Yii::app()->request->hostInfo; ?>"
				><img src="cid:logo_<?php echo Yii::app()->name; ?>" alt="<?php echo Yii::app()->name; ?>" /><br /></a></h1>
				<div><?php echo Yii::t('site', 'Your profile on the first business community platform...', [], null, Yii::app()->language); ?></div>
				<br />
            </div>
            <div style="
                 padding: 25px;
                 color: <?php echo $colors['text']; ?>;
                 background-color: <?php echo $colors['bg']; ?>;
                 border: solid 1px rgba(0, 0, 0, 0.15);
                 border-left: none;
                 border-right: none;
             ">
                <div style="display: none;">
                    ------------------------------<br /><br />
                </div>
                
                <?php echo $content; ?><br />
                
                <div style="display: none;">
                    ------------------------------<br />
                </div>
            </div>
            <div style="
                 padding: 20px 25px; 
                 color: <?php echo $colors['logo']; ?>; 
                 text-align: right;
                 background-color: <?php echo $colors['bottom']; ?>;
                 border-radius: 0 0 8px 8px;
             ">
                <?php if (isset($brand['phone'])) : ?>
                    <b><?php echo Yii::t('contact', 'Phone contact', [], null, Yii::app()->language); ?>:</b><br />
                    <p>
                        <a style="color: <?php echo $colors['logo']; ?>" 
                                href="tel:<?php echo str_replace(
                                array(' ', '-', '+', '(', ')'), 
                                '', 
                                $brand['phone']
                                ); ?>">
                            <?php echo $brand['phone']; ?>
                        </a><br />
                    </p>
                <?php endif; ?>

                <?php if (isset($brand['email'])) : ?>
                    <b><?php echo Yii::t('contact', 'Email contact', [], null, Yii::app()->language); ?>:</b><br />
                    <p>
                        <a style="color: <?php echo $colors['logo']; ?>" 
                                href="mailto:<?php echo $brand['email']; ?>">
                            <?php echo $brand['email']; ?>
                        </a><br />
                    </p>
                <?php endif; ?>
            </div>
        </div>

    </body>
</html>