<?php 
/**
 * Wiadomość email o nowych obiektach.
 * @category views
 * @package main
 * @author
 * @copyright
 */ 
?>
<?php
    $message = '<h3>'.Yii::t('site', 'New business publications on Firmbook', [], null, $user->language).'</h3>';
    foreach($itemsGrouped as $itemsGroupKey => $itemsGroup)
    {
        if(!$itemsGroup)
            continue;
        $message .= '<h4 style="margin: 10px 0 5px 0">'.Yii::t('item', 'New '.$itemsGroupKey, [], null, $user->language).':</h4>';
        foreach($itemsGroup as $item)
        {
            $itemLink = '<b>'.Html::link($item['item']->name, $item['item']->url(true), array('target'=>'_blank')).'</b>';
            $message .= $itemLink.'<br>';
            foreach($item['categories'] as $url => $name)
            {
                $catLink = Html::link($name, $url);
                $message .= Yii::t('site', 'Category', [], null, $user->language).': '.$catLink.'<br>';
            }  
            if($item['item']->cache_type == 'p' || $item['item']->cache_type == 's')
            {   
                $company = null;
                if($item['item']->product)
                    $company = $item['item']->product->company;
                if($item['item']->service)
                    $company = $item['item']->service->company;
                if($company)
                {
    //                $companyLink = Html::link(Yii::t('packages', $item['item']->package->name, [], null, $user->language), $this->createAbsoluteUrl('/packages/comparison'));
                    $companyLink = Html::link($company->item->name, $company->item->url(true), array('target'=>'_blank'));
                    $message .= Yii::t('site', 'Company', [], null, $user->language).': '.$companyLink.'<br>';
                }    
            }    
            $packagesLink = Html::link(Yii::t('packages', $item['item']->package->name, [], null, $user->language), $this->createAbsoluteUrl('/packages/comparison'));
            $message .= Yii::t('site', 'Package', [], null, $user->language).': '.$packagesLink.'<br>';
            if($item['item']->cache_type == 'c') {
                if($item['counts']['products'])
                    $message .= Yii::t('site', 'Products', [], null, $user->language).': '.Html::link($item['counts']['products'], $this->createAbsoluteUrl('/companies/offer/', array(
								        'name' => $item['item']->alias,
                                        'type' => 'product'))).'<br>';
                if($item['counts']['services'])
                    $message .= Yii::t('site', 'Services', [], null, $user->language).': '.Html::link($item['counts']['services'], $this->createAbsoluteUrl('/companies/offer/', array(
								        'name' => $item['item']->alias,
                                        'type' => 'service'))).'<br>';
            }    
            $message .= '<br>';
        }    
    }
?>
<p>    
    <?php echo Yii::t('site', 'FIRMBOOK SYSTEM POLAND informs', [], null, $user->language); ?><br />
</p>
<hr />
<p>
    <?php 
        echo $message;
    ?>
    <br />    
    <br />
</p>
<?php   
    $brand2 = Yii::app()->params['branding'];
    $colors2 = $brand2['colors'];
?>
<div style="
        padding: 15px 25px;
        color: <?php echo $colors2['logo']; ?>;
        background-color: <?php echo $colors2['bottom']; ?>;
        border: solid 1px rgba(0, 0, 0, 0.15);
        border-left: none;
        border-right: none;
    ">
   <p>
       <?php 
           $link = $this->createAbsoluteUrl('/packages/comparison');
           echo Yii::t('site', 
               'Use the {link} for transfer business to the internet and promote it, advertise, boost positioning in web searches and finding new business contacts...', 
               array('{link}' => Html::link(Yii::t('site', 'firmbook\'s tools, possibilities and solutions', [], null, $user->language), $link)), null, $user->language); ?>
   </p>
</div>
<div style="
                 padding: 15px 25px;
                 color: <?php echo $colors2['logo']; ?>;
                 background-color: <?php echo $colors2['bottom']; ?>;
                 border: solid 1px rgba(0, 0, 0, 0.15);
                 border-left: none;
                 border-right: none;
                 margin-top: 5px;
             ">
            <p>
                <?php 
                    $link = $this->createAbsoluteUrl('site/signout_newsletter', array(
                        'username' => $user->username,
                        'verification_code' => $user->sign_out_verification_code,
                    ));
                    echo Yii::t('site', 
                        'If you have not registered at {link} please ignore this message, use {thislink} and resign of receiving similar informations in the future', 
                        array('{link}' => Html::link($brand2['domain'], Yii::app()->params['hostInfo']), '{thislink}' => Html::link(Yii::t('common', 'this link', [], null, $user->language), $link)), null, $user->language); ?>
            </p>
        </div>
    <?php /*
        $link = $this->createAbsoluteUrl('site/signout_newsletter', array(
            'username' => $user->username,
            'verification_code' => $user->sign_out_verification_code,
        ));
        echo Yii::t('common', 
            'If you have received information from us that you did not want to receive,<br />'
    .' use {thislink} and sign out of receiving similar informations in the future', 
            array('{thislink}' => Html::link(Yii::t('common', 'this link', [], null, $user->language), $link)), null, $user->language); 
     * */?>
     

