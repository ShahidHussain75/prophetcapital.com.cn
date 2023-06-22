<?php
    $photo_shape = $styles['photo_shape'] == 'round' ? 'border-radius: 50%;' : ''; // set foto shape
    $photo_class = $styles['photo_shape'] == 'round' ? 'ats-round' : 'ats-square'; // set foto shape
    $photo_size = $styles['photo_size'];
    $photo_width = preg_replace('/[^0-9]/', '', $styles['photo_width']);
    $gaps_fix = '';
    if($photo_size == 'custom'){
        $sizes = array(
            $photo_width,
            $photo_width,
            true
        );
        $photo_width = 'width: ' . A_Team_App_Shortcode::get_size($styles['photo_width']);
        $padding_bottom = 'padding-bottom: ' . $styles['photo_width'];
    }else{
        $sizes = A_Team_App_Shortcode::get_image_sizes($photo_size);
        $photo_width = 'width: 100%';
        $padding_bottom = 'padding-bottom: 100%';

        $gaps_fix = 'margin: -' . A_Team_App_Shortcode::get_size($styles['card_gaps']);
    }

    $top_margin = $photo_size != 'a-full' ? 'margin-top: ' . A_Team_App_Shortcode::get_size($styles['photo_top_margin']) : '';

    if(isset($employer->ID) && has_post_thumbnail($employer->ID)){
        $foto_id = get_post_thumbnail_id($employer->ID);
        $foto = wp_get_attachment_image_src($foto_id, 'large', false);
        $photo_src = $foto[0];
    }else{
        $photo_src = plugins_url('../../img/default-avatar.jpg', dirname(__FILE__));
        if($preview) {
            $photo_src = plugins_url('../../img/john-doe.jpg', dirname(__FILE__));
        }
    }
    $background = 'background-image:url(' . $photo_src . ')';
    $width = 'width: ' . $sizes[0] . 'px';
    $height = 'height: ' . $sizes[1] . 'px';

    $font_size = 'font-size: ' . ($sizes[0] < 300 ? $sizes[0] : 300 ) / 10 . 'px';

    echo "<div class='employer_photo $layout-container sortable'
                data-block-name='photo'
                data-tooltip-name='photo'
                style='
                $top_margin;
                $font_size;
                $gaps_fix;'>";

    echo "<div class='photo-wrapper $photo_class' style='$photo_width;$photo_shape'>";
    echo "<div class='photo-container' style='
            $background;
            '>";
    echo '</div>';
    echo '</div>';

    if($preview){
        include($base . 'public/partials/parts/tooltip-button.php');
    }
    echo '</div>';