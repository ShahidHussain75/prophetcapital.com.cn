<?php
    $font_size = 'font-size: ' . $styles['short_bio_font_size'];
    $color = 'color: ' . $styles['short_bio_color'];
    $bold = $styles['short_bio_bold'] == '1' ? 'font-weight: ' . 'bold' : 'font-weight: ' . 'normal';
    $italic = $styles['short_bio_italic'] == '1' ? 'font-style: ' . 'italic' : 'font-style: ' . 'normal';
    $text_transform = 'text-transform: ' . $styles['short_bio_text_transform'];
    $align = 'text-align: ' . $styles['short_bio_align'];
    //$gaps = $styles['card_gaps'];
    $top_margin = '';
    if(isset($styles['short_bio_top_margin'])){
        $top_margin = 'margin-top: ' . A_Team_App_Shortcode::get_size($styles['short_bio_top_margin']);
    }

    echo "<div class='employer_short_bio $layout-container sortable'
                style='$top_margin;'
                data-block-name='short_bio'
                data-tooltip-name='short_bio'>";
    echo "<span class='team-field-content' style='
                $font_size;
                $color;
                $bold;
                $italic;
                $align;
                $text_transform;
                '>";
    echo $employer->post_excerpt;
    echo '</span>';

    if($preview){
        include($base . 'public/partials/parts/tooltip-button.php');
    }
    ?>
</div>