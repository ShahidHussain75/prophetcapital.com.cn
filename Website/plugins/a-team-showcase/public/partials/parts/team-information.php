<?php
$layout_align = 'text-align: left';
if(isset($styles['align'])){
    $layout_align = 'text-align: ' . $styles['align'];
}
if(!empty($this->data->title)){
    echo "<h2 class='ats-team-title'
                style='$layout_align'>";
    echo htmlspecialchars($this->data->title);
    echo '</h2>';
}

if(!empty($this->data->post_excerpt)) {
    echo "<p class='ats-team-description'
                style='$layout_align'>";
        echo htmlspecialchars($this->data->post_excerpt);
    echo '</p>';
}

