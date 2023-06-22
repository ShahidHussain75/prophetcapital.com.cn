<?php
    $photo_shape = $styles['photo_shape'] == 'round' ? 'border-radius: 50%;' : ''; // set foto shape
    $photo_size = $styles['photo_size'];
    $photo_width = str_replace('px', '', $styles['photo_width']);
    if($photo_size == 'custom'){
        $sizes = array(
            $photo_width,
            $photo_width,
            true
        );
    }else{
        $sizes = A_Team_App_Shortcode::get_image_sizes($photo_size);
    }
    $top_margin = '';
    if(isset($styles['photo_top_margin'])){
        $top_margin = 'margin-top: ' . A_Team_App_Shortcode::get_size($styles['photo_top_margin']);
    }

    echo "<div class='employer_photo $layout-container sortable'
                style='$top_margin;'
                data-block-name='photo'
                data-tooltip-name='photo'
                style='width: " . $photo_width . "px'>";

    if(isset($employer->ID) && has_post_thumbnail($employer->ID)){
        $foto = get_the_post_thumbnail(
            $employer->ID,
            $sizes,
            array(
                'style' => $photo_shape,
                'class' => 'attachment-' . $photo_size
            ));
        echo $foto;
    }else{
        $$photo_src = plugins_url('../../img/default-avatar.jpg', dirname(__FILE__));
        if($preview) {
            $photo_src = plugins_url('../../img/john-doe.jpg', dirname(__FILE__));
        }
        echo '<img width="' . $sizes[0] . '"
                    height="' . $sizes[1] . '"
                    src="' . $photo_src . '"
                    class="attachment-'. $photo_size . '"
                    style="' . $photo_shape . '"
                    alt="' . $employer->post_title . '" ?>';
    }

    if($preview){
        include($base . 'public/partials/parts/tooltip-button.php');
    }
    echo '</div>';