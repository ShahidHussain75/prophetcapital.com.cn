<?php
    $name = $employer->post_title;
    $foto = get_the_post_thumbnail($employer->ID, 'thumbnail');
?>

<li data-action="edit-employer"
    class="clearfix"
    employer-id="<?php echo $employer->ID; ?>"
    title="<?php echo $name; ?>">
    <div class="wrapper">
        <div class="foto">
            <?php
            if(has_post_thumbnail($employer->ID)){
                echo $foto;
            }else{
                echo '<img src="' . $default_avatar . '" alt="" ?>';
            }
            ?>
        </div>
        <div class="name_position">
            <span class="name"><?php echo $name ?></span>
            <span class="position"><?php echo $employer->position ?></span>
        </div>
        <button class="delete-employer" title="<?php echo A_Team_App_Model_Employer::getLabel('delete_item'); ?>">
            <span><?php echo A_Team_App_Model_Employer::getLabel('delete_item'); ?></span>
            <svg viewBox="0 0 20 20">
                <use xlink:href="#icon-delete"></use>
            </svg>
        </button>

        <?php
        $employer_teams = $employer->teams;
        if(gettype($employer->teams) == 'array' && sizeof($employer->teams) > 0){
            $count = sizeof($employer_teams);
            echo '<div class="teams">';
            echo '<span class="teams_count">' . $count . '</span> ';
            echo $count > 1 ? A_Team_App_Model_Team::getLabel('name') : A_Team_App_Model_Team::getLabel('singular_name') ;
            echo '</div>';
        }
        ?>
    </div>
</li>