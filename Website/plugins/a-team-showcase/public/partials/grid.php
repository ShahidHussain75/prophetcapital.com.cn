<?php
    $base = plugin_dir_path(__FILE__);
    $layout = 'grid';
    $class = '';
    $slider_class = '';
    $hover_class = '';
    $layout_css = $this->data->custom_css ?: '';

    $preview = $this->data->preview;
    $styles = $this->data->styles[$layout] ?: $this->data->custom_fields['styles'][$layout];

    $reveal = $styles['reveal'];
    $hover = $styles['hover'];
    if($hover != 'off'){
        $hover_class = 'ats-effect-' . $hover;
    }

    include($base . 'parts/buttons_css.php');

    $slider = $styles['slider'];

    $gaps = preg_replace('/[^0-9]/', '', $styles['gaps']); // clear value from non-numeric characters
    $margin_left = 'margin-left: ' . $gaps / 2 . 'px';
    $margin_right = 'margin-right: ' . $gaps / 2 . 'px';
    $margin_top = 'margin-top: ' . $styles['gaps'];
    $margin = 'margin: 0 ' . $gaps / 2 . 'px ' . $gaps . 'px';
    $margin_fix = 'margin: 0 -'  . $gaps / 2 . 'px -' . $gaps / 2 . 'px';
    $width_fix = 'width: calc(100% + ' . $gaps . 'px)';
    $layout_align = 'text-align: ' . $styles['align'];
    $photo_size = $styles['photo_size'];

    $card_gaps = 'padding:  ' . A_Team_App_Shortcode::get_size($styles['card_gaps']);
    $body_visible = $styles['body_visible'];
    $card_columns = 'a-grid-' . $styles['card_width'];

    $card_base_color = '';
    $card_border_color = '';
    $card_shadow_color = '';

    if($body_visible == '1'){
        $card_base_color = 'background-color: ' . $styles['card_base_color'];
        $card_border_color = 'border: 1px solid ' . $styles['card_border_color'];
        $card_shadow_color = 'box-shadow: 0 0 5px 0 ' . $styles['card_shadow_color'];
    }

    $blocks_order = $styles['blocks_order'];

    if($styles['photo_size'] == 'a-full'){
        $class = 'a-full';
        $layout_css .= '.ats-layout .sortable-box.a-full .employer_photo + .grid-container{padding-top: ' . A_Team_App_Shortcode::get_size($styles['card_gaps']) . '}';
        $layout_css .= '.ats-layout .sortable-box.a-full .grid-container ~ .employer_photo{padding-top: ' . A_Team_App_Shortcode::get_size($styles['card_gaps']) . '}';
    }
    if($slider == '1' && !$preview){
        $slider_class = ' slider-enabled ';
    }

    echo '<div class="ats-layout-grid ats-layout ' .  $hover_class . '"
                id="ats-layout-' . $this->data->ID . '">';
    echo '<style type="text/css">' . $layout_css . '</style>';

    include($base . 'parts/team-information.php');
    include($base . 'parts/filter.php');

    echo "<ul team-id='" . $this->data->ID ."' class='$slider_class employers-box isotope-container' style='
                $width_fix;
                $layout_align;
                $margin_fix;'
                >";
    if(count($this->data->employers) > 0):
    $employers = $this->data->employers ?: $this->data->custom_fields['employers'];
    foreach ($employers as $employer):
        $id = $employer->ID;
        $terms = wp_get_post_terms($id, A_Team_App_Model_Employer::getTaxonomy(), array('fields' => 'ids'));
        $terms = array_map(function($term){
            return 'ats-filter-' . $term;
        }, $terms);
        $terms = implode(' ', $terms);

        $clicable = !empty($employer->profile) ? 'ats-profile' : '';
        $profile = htmlspecialchars($employer->profile);
        $profile_title = !empty($employer->profile) ? 'Open profile' : '';

        echo "<li class='$card_columns $terms isotope-item' style=''
                    employer-id='$id'
                    >";
        echo "<div $clicable data-profile='$profile'
                    class='sortable-box $class'
                    title='$profile_title'
                    style='
                    $card_base_color;
                    $card_border_color;
                    $card_shadow_color;
                    $card_gaps;
                    $margin;'>";

        foreach ($blocks_order as $block) {
            A_Team_App_Shortcode::include_block(
                array(
                    'block' => $block,
                    'layout' => $layout,
                    'styles' => $styles,
                    'preview' => $preview,
                    'employer' => $employer,
                    'id' => $id
                )
            );
        }
        echo '</div>';
        echo '</li>';
    endforeach;
    endif;
    echo '</ul>';
echo '</div>';
?>
<?php if(!$preview && $reveal == 1): ?>
<script id="ats-dynamic-script-<?php echo $this->data->ID?>">
    ATS.init_animations('<?php echo $this->data->ID?>');
</script>
<?php endif; ?>

<?php if(!$preview): ?>
<script type="text/javascript">
    // handle click on employer to open profile url
    jQuery(document).ready(function($){
        ATS.bind_profiles();
    });
</script>
<?php endif; ?>

<?php
if($slider == '1' && !$preview){
    $carousel = '<script type="text/javascript">';
    $carousel .= 'jQuery(document).ready(function($){
                          ATS.enable_slider(' . $this->data->ID . ', "' . $styles['card_width'] . '");
                        });';
    $carousel .= '</script>';
    echo $carousel;
    include_once('parts/svg.html');
}
?>
