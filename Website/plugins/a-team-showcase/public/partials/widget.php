<?php
    $base = plugin_dir_path(__FILE__);
    $layout = 'widget';
    $hover_class = '';
    $layout_css = $this->data->custom_css ?: '';
    $preview = $this->data->preview;
    $styles = $this->data->styles[$layout] ?: $this->data->custom_fields['styles'][$layout];

    $hover = $styles['hover'];
    if($hover != 'off'){
        $hover_class = 'ats-effect-' . $hover;
    }

    $gaps = 'padding: ' . A_Team_App_Shortcode::get_size($styles['card_gaps']);
    $blocks_order = $styles['blocks_order'];
    $border_color = $styles['card_border_color'];
    $i = 1;

    $even_css = '#ats-layout-' . $this->data->ID . ' .employers-box li:not(.isotope-hide):nth-child(even){background-color:' . $styles['card_even_row_color'] . ';}';

    echo "<h2 class='ats-team-title'>";
    echo $this->data->title;
    echo '</h2>';

    echo '<div class="ats-layout-widget ats-layout ' .  $hover_class . '" id="ats-layout-' . $this->data->ID . '">';
    echo '<style type="text/css">' . $layout_css . $even_css . '</style>';

        echo "<ul team-id='" . $this->data->ID ."' class='isotope-container employers-box'>";
            if(count($this->data->employers) > 0):
            $employers = $this->data->employers ?: $this->data->custom_fields['employers'];
            foreach($employers as $employer):
                $id = $employer->ID;
                $terms = wp_get_post_terms($id, A_Team_App_Model_Employer::getTaxonomy(), array('fields' => 'ids'));
                $terms = array_map(function($term){
                    return 'ats-filter-' . $term;
                }, $terms);
                $terms = implode(' ', $terms);

                $clicable = !empty($employer->profile) ? 'ats-profile' : '';
                $profile = htmlspecialchars($employer->profile);
                $profile_title = !empty($employer->profile) ? 'Open profile' : '';

                echo "<li class='$terms isotope-item' employer-id='$id'>";
                echo "<div $clicable data-profile='$profile'
                            class='clearfix'
                            title='$profile_title'
                            style='
                    border-top: 1px solid $border_color;
                    border-bottom: 1px solid $border_color;
                    $gaps;'
                    >";
                        A_Team_App_Shortcode::include_block(
                            array(
                                'block' => 'photo',
                                'layout' => $layout,
                                'styles' => $styles,
                                'preview' => $preview,
                                'employer' => $employer
                            )
                        );
                echo '<div class="sortable-box">';
                        foreach($blocks_order as $block){
                            A_Team_App_Shortcode::include_block(
                                array(
                                    'block' => $block,
                                    'layout' => $layout,
                                    'styles' => $styles,
                                    'preview' => $preview,
                                    'employer' => $employer
                                )
                            );
                        }
                echo '</div>';
                echo '</div>';
                echo '</li>';
            endforeach;
            endif;
        ?>
    </ul>
</div>
<?php if(!$preview): ?>
<script type="text/javascript">
    // handle click on employer to open profile url
    jQuery(document).ready(function($){
        ATS.bind_profiles();
    });
</script>
<?php endif; ?>