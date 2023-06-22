<?php
    $social_color = 'color: ' . $styles['social_color'];
    $social_size = 'font-size: ' . $styles['social_size'];
    $align = 'text-align: ' . $styles['social_align'];
    $top_margin = '';
    if(isset($styles['social_top_margin'])){
        $top_margin = 'margin-top: ' . A_Team_App_Shortcode::get_size($styles['social_top_margin']);
    }
    echo "
<div class='employer_social $layout-container sortable'
                style='
                $top_margin;
                $social_color;
                $social_size;
                $align;
                '
                data-block-name='social'
                data-tooltip-name='social'>";
    echo "<span class='team-field-content'>";

    if(!empty($employer->facebook)){
        if($preview){
            echo '<i class="fa fa-facebook-official"></i>';
        }else{
            echo "<a class='ats-social-fb' href='$employer->facebook' target='_blank'>";
            echo '<i class="fa fa-facebook-official"></i><span class="ats-tooltip">Facebook</span>';
            echo '</a>';
        }
    }

    if(!empty($employer->twitter)){
        if($preview){
            echo '<i class="fa fa-twitter"></i>';
        }else{
            echo "<a class='ats-social-tw' href='$employer->twitter' target='_blank'>";
            echo '<i class="fa fa-twitter"></i><span class="ats-tooltip">Twitter</span>';
            echo '</a>';
        }
    }

    if(!empty($employer->linkedin)){
        if($preview){
            echo '<i class="fa fa-linkedin"></i>';
        }else{
            echo "<a class='ats-social-ln' href='$employer->linkedin' target='_blank'>";
            echo '<i class="fa fa-linkedin"></i><span class="ats-tooltip">Linkedin</span>';
            echo '</a>';
        }
    }
    echo '</span>';

    if($preview){
        include($base . 'public/partials/parts/tooltip-button.php');
    }
    echo '</div>';