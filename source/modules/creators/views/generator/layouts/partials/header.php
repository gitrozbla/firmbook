<?php 
$filesPath = 'files/CreatorsWebsite/'.$website->company_id.'/';

echo '<div id="header" ';
    if ($website->extended_header_bg == false) {
        echo 'class="container" ';
    }
    $style = array();
    if ($website->header_bg) {
        $style [] = 'background-image:url(\''.$this->mapFile($filesPath.$website->header_bg).'\')';
    }
    if ($website->header_text_align) {
        $style [] = 'text-align:'.$website->header_text_align;
    }
    if (!empty($style)) {
        echo 'style="'.implode(';', $style).'"';
    }
echo '>';
    echo '<div id="header-wrapper" ';
    $style = array();
    if ($website->header_bg_brightness != 0) {
        if ($website->header_bg_brightness > 0) {
            $color = 'rgba(255,255,255,'.$website->header_bg_brightness.')';
        } else {
            $color = 'rgba(0,0,0,'.(-($website->header_bg_brightness)).')';
        }
        $style [] = 'background:'.$color;
    }
    if (!empty($style)) {
        echo 'style="'.implode(';', $style).'"';
    }
    echo '>';
        echo '<div class="container">';
            echo '<div class="table">';
                echo '<div class="table-cell header-height" ';
                if ($website->header_height) {
                    echo 'style="height:'.$website->header_height.'px"';
                }
                echo '>';
					$socialIconsLocation = 'header';	// flag
					require 'socialIcons.php';

                    $style = $website->logo ? '':'display:none;';
                    echo Html::image(
                            $website->logo ? $this->mapFile($filesPath.$website->logo) : '', 
                            $website->name, 
                            array(
                                'class' => 'header-logo',
                                'style' => $style
                                ));
                    
                    $style = $website->name ? '':'display:none;';
                    $style .= $website->name_color ? 'color:'.$website->name_color.';' : '';
                    echo '<h1 style="'.$style.'">'.Html::encode($website->name).'</h1>';
                    
                    $style = $website->slogan ? '':'display:none';
                    $style .= $website->slogan_color ? 'color:'.$website->slogan_color.'' : '';
                    echo '<p class="lead" style="'.$style.'">'.Html::encode($website->slogan).'</p>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
echo '</div>';