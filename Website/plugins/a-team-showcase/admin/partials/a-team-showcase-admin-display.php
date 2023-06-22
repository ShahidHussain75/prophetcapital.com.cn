<?php
    $default_avatar = plugins_url('img/default-avatar.png', dirname(__FILE__));

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class='wrap admin-wrap' xmlns="http://www.w3.org/1999/html">
    <?php include_once('svg.html'); ?>
    <div class="a-title">
        <h2>
            <svg viewBox="0 0 56 56">
                <use xlink:href="#face-dashboard"></use>
            </svg>
            <span>
                Manage Team & Employees
            </span>
        </h2>
        <ul class="actions">
            <li id="action-add-buttons">
                <button class="btn-add btn btn-success">
                    <svg viewBox="0 0 36 36">
                        <use xlink:href="#icon-plus"></use>
                    </svg>
                </button>
            </li>
            <!--<li id="action-settings">
                <button class="btn-settings btn btn-default" title="Settings">
                    <svg viewBox="0 0 36 36">
                        <use xlink:href="#icon-settings"></use>
                    </svg>
                </button>
            </li>-->
            <li id="action-help">
                <a class="icon action btn btn-default" title="Help" data-action="modal" href="#modal-help">
                    <svg viewBox="0 0 36 36">
                        <use xlink:href="#icon-help"></use>
                    </svg>
                </a>
                <!--<button class="icon action btn btn-default" title="Help">
                    <svg viewBox="0 0 36 36">
                        <use xlink:href="#icon-help"></use>
                    </svg>
                </button>-->
            </li>
        </ul>
    </div>
    <div class="ui-row version">
        <div class="col-md-12">
            <div>
                <?php
                echo A_Team_App_Plugin::$plugin_label;
                echo ' v. ';
                echo A_Team_App_Plugin::$version;
                ?>
            </div>
        </div>
    </div>
    <div class="clearfix ui-row">
        <div class="col-md-8 col-sm-8 ui-column">
            <div class="panel panel-main" id="panel-teams">
                <div class="panel-heading">
                    <h6>
                        <?php
                        echo A_Team_App_Model_Team::getLabel('all_items');
                        $teams_count = count($this->data['teams']);
                        if($teams_count > 0){
                            echo ' <span>(';
                            echo count($this->data['teams']);
                            echo ')</span>';
                        }
                        ?>
                    </h6>
                </div>
                <div class="panel-body a-scroll">
                    <div class="padding-wrapper">
                        <?php
                            Awesome_Post::display_empty_model_message(
                                $teams_count,
                                'teams',
                                'Type name of your first team below and hit enter.'
                            );
                        ?>
                        <ul class="model-list teams-list" id="teams-list">
                            <?php
                            if($teams_count > 0){
                                foreach($this->data['teams'] as $team):
                                    include('cards/team.php');
                                endforeach;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="panel-footer create-team-input">
                    <form action="" method="" class="form">
                        <div id="a-slide-panel-spinner" class="a-spinner" data-wr_replaced="true"  style="display: none;">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                        <input data-action="add-model"
                               data-model="team"
                               name="team_name"
                               type="text"
                               class="form-control"
                               placeholder="<?php echo A_Team_App_Model_Team::getLabel('add_new_item'); ?>"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 ui-column">
            <div class="panel panel-main" id="panel-employers">
                <div class="panel-heading">
                    <h6>
                        <?php
                        echo A_Team_App_Model_Employer::getLabel('all_items');
                        $employers_count = count($this->data['employers']);//
                        if($employers_count > 0){
                            echo ' <span>(';
                            echo count($this->data['employers']);
                            echo ')</span>';
                        }
                        ?>
                    </h6>
                </div>
                <div class="panel-body a-scroll">
                    <!--<form action="" method="">
                        <div class="form-search search-only">
                            <i class="search-icon glyphicon glyphicon-search"></i>
                            <input type="text" class="form-control search-query" placeholder="Find Employee" />
                        </div>
                    </form>-->
                    <div class="padding-wrapper">
                        <?php
                            Awesome_Post::display_empty_model_message(
                                $employers_count,
                                'employees',
                                'Type name of employee below and hit enter.'
                            );
                        ?>
                        <ul class="employers-list model-list connected-sortable" id="employers-list">
                            <?php
                                if($employers_count > 0){
                                    foreach($this->data['employers'] as $employer):
                                        include('cards/employer.php');
                                    endforeach;
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="panel-footer create-team-input">
                    <form action="" method="post" class="form">
                        <div id="a-slide-panel-spinner" class="a-spinner" data-wr_replaced="true"  style="display: none;">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                        <input data-action="add-model"
                               data-model="employer"
                               name="employer_name"
                               type="text"
                               class="form-control"
                               placeholder="<?php echo A_Team_App_Model_Employer::getLabel('add_new_item'); ?>"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Hidden Empty Slide panel -->
    <div class="a-slide-panel" id="a-slide-panel">
        <?php include( 'parts/spinner.php'); ?>
    </div>
</div>
<div class="hide" id="panel-html-pool">
    <?php
        // Include panels forms and icons

        include_once('panels/employer-edit.php');
        include_once('panels/employer-add.php');
        include_once('panels/team-edit.php');
        include_once('panels/team-add.php');
        //include_once('panels/settings.php');
        include_once('panels/help.php');
        include_once('parts/preview-help.php');

        include_once('tooltips/add-buttons.php');
        include_once('tooltips/font-settings.php');
        include_once('tooltips/photo-settings.php');
        include_once('tooltips/divider-settings.php');
        include_once('tooltips/social-settings.php');

    ?>
</div>