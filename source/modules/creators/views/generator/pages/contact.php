<?php 

$company = $page->website->company;

$verificationLink = ($company->allow_verification 
        ? Html::link(Yii::t('contact', 'Check registers'), 
                'https://prod.ceidg.gov.pl/CEIDG/CEIDG.Public.UI/Search.aspx', 
                array(
                    'target' => '_blank',
                    'class' => 'registers-link',
                ))
            .'<i class="fa fa-check"></i>'
        : '');
?>

<?php if ($company->item->name) : ?>
    <h3><?php echo $company->item->name; ?></h3>
<?php endif; ?>

<?php if (isset($company->map_lat) && isset($company->map_lng)) : ?>
    
    <?php if ($company->street_view_embed) : ?>
        <div class="row">
            <div class="span6">
                <div id="map-canvas" style="height: 300px;"></div>
            </div>
            <div class="span6">
                <div id="pano" style="height: 300px;"></div>
            </div>
        </div>
    <?php else : ?>
        <div id="map-canvas" style="height: 300px;"></div>
    <?php endif; ?>
    
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo Yii::app()->params->google['map']['keyMapsJavaScriptApi'] ?>"></script>
    <script>
        var map;
        function initialize() {
                var markerLatLng =  new google.maps.LatLng(<?php echo $company->map_lat; ?>,<?php echo $company->map_lng; ?>);
                var mapLatLng;
                <?php if(!isset($mapLat) || !isset($mapLng)) : ?>
                mapLatLng = markerLatLng;
                <?php else: ?>
                mapLatLng = new google.maps.LatLng(<?php echo $mapLat; ?>,<?php echo $mapLng; ?>);
                <?php endif;?>

                var mapOptions = {
                        zoom: <?php echo Yii::app()->params->google['map']['locationZoom'];?>,
                    center: mapLatLng
                };
                map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);

                var marker = new google.maps.Marker({
                position: markerLatLng,
                map: map,
                <?php if(isset($company->item->name)) : ?>
                title: '<?php echo $company->item->name; ?>'
                <?php endif;?>    
                });
                
                <?php if ($company->street_view_embed) : ?>
                    var panoramaOptions = {
                                position: markerLatLng,
                                pov: {
                                  heading: 34,
                                  pitch: 10
                                }
                              };
                    var panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'), panoramaOptions);
                    map.setStreetView(panorama);
                <?php endif; ?>
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<?php endif; ?>
    
<div class="row">
    <?php 
        $profile = array();
        if ($company->street || $company->postcode || $company->city || $company->province) {
            $address = array();
            if ($company->street) {
                $address []= $company->street;
            }
            if ($company->postcode || $company->city) {
                $address []= $company->postcode.' '.$company->city;
            }
            if ($company->province) {
                $address []= $company->province;
            }
            $profile[Yii::t('Company', 'Address')] = implode('<br />', $address);
        }

        if ($company->nip) {
            $profile['NIP'] = $company->nip.' '.$verificationLink;
        }
        if ($company->regon) {
            $profile['REGON'] = $company->regon.' '.$verificationLink;
        }
        if ($company->krs) {
            $profile['KRS'] = $company->krs.' '.$verificationLink;
    } ?>

    <?php if (!empty($profile)) : ?>
    <div class="span6">
        <dl>
            <?php foreach($profile as $key=>$value) {
                echo '<dt>'.$key.':</dt>';
                echo '<dd>'.$value.'</dd>';
            } ?>
        </dl>
        <div class="clearfix"></div>
    </div>
    <?php endif; ?>
    
    <div class="span6">
        <?php 
            $contact = array();
            if ($company->phone) {
                $contact[Yii::t('Company','Phone')] = Html::link(
                        '<i class="fa fa-phone"></i> '.$company->phone, 
                        'tel:'.filter_var($company->phone, FILTER_SANITIZE_NUMBER_FLOAT));
            }
            if ($company->email) {
                $contact[Yii::t('Company','Email')] = Html::link(
                        '<i class="fa fa-envelope"></i> '.$company->email, 
                        'mailto:'.$company->email);
            }
            $www = $company->item->www;
            if ($www) {
                $url = substr($www, 0, 7) == 'http://' ? $www : 'http://'.$www;
                $contact[Yii::t('Item','Website')] = Html::link(
                        '<i class="fa fa-globe"></i> '.$www, 
                        $url, 
                        array('target'=>'_blank'));
            }

            if (!empty($contact)) {
                echo '<dl>';
                foreach($contact as $key=>$value) {
                    echo '<dt>'.$key.':</dt>';
                    echo '<dd>'.$value.'</dd>';
                }
                echo '</dl>';
                echo '<div class="clearfix"></div>';
            }
        ?>
    </div>
</div>

