<?php
/**
 * Add or Edit Employer Form
 */
?>
<div class="row" id="team-form">
    <div class="form" data-model="team" data-type="form">
        <div class="col-md-7">
            <div class="panel general-information">
                <div class="panel-heading">
                    <h6>General Information</h6>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="team_id"/>

                    <div class="input-group form-group">
                        <span class="input-group-addon">Team Name:</span>
                        <input type="text" class="form-control" name="team_name" placeholder="Awesome Team"/>
                    </div>
                    <div class="form-group team-employers">
                        <input type="hidden" name="team_employers" id="team-employers" value=""/>
                        <ul class="team-members connected-sortable sortable-placeholder" id="team-sortable">
                            <!--<li class="empty">
                                    <svg class="icon icon-plus"><use xlink:href="#icon-plus"></use></svg>
                                </li>-->
                        </ul>
                    </div>
                    <h5>Team Information</h5>

                    <p class="hint">
                        Fill fields below in order to display headings before team showcase on your site.
                    </p>

                    <div class="input-group form-group">
                        <span class="input-group-addon">Team Title:</span>
                        <input type="text" class="form-control" name="team_title" placeholder="Team Members"/>
                    </div>
                    <div class="input-group form-group">
                        <span class="input-group-addon">Description:</span>
                        <textarea class="form-control" name="team_description" id="" cols="1" rows="4" placeholder="Tell something about your awesome team"></textarea>
                    </div>
                    <div class="alert" id="ajax-form-result"></div>
                </div>
            </div>
            <div class="panel live-preview">
                <div class="panel-heading">
                    <h6>Builder</h6>
                    <a class="btn btn-transparent" title="Help" data-action="modal" href="#modal-help">
                        <svg viewBox="0 0 36 36">
                            <use xlink:href="#icon-help" transform="translate(-5, -5)"></use>
                        </svg>
                    </a>
                </div>
                <div class="panel-body">
                    <div class="team-preview-wrapper">
                        <p class="hint">Notice: Builder is not for preview purposes. The appearance on your site may differs due to design rules of your theme. Always check the result on front-end after saving.</p>
                        <div id="team-preview" class="team-preview"></div>
                        <div id="team-tooltips" class="team-tooltips"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="employers-list"></div>
            <div class="panel team-form panel-design" panel="design">
                <div class="panel-heading">
                    <h6>Design customization</h6>
                </div>
                <div class="panel-body a-scroll a-scroll-row">
                    <div class="padding-wrapper">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Layout:</span>
                                    <select name="team_layout">
                                        <option value="grid" selected>Grid</option>
                                        <option value="table">Table</option>
                                        <option value="widget">Widget</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Template:</span>
                                    <select name="team_template"></select>
                                </div>
                            </div>
                        </div>

                        <div class="interface-elements">
                            <h5>Interface elements</h5>
                            <div class="row">
                                <div class="col-md-6 model-field slider">
                                    <div class="input-group form-group">
                                        <span class="input-group-addon transparent">Slider</span>
                                        <div class="text-right">
                                            <label class="toggle">
                                                <input type="hidden" name="team_slider" data-checked="" checked>
                                                <span class="handle"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 model-field filter">
                                    <div class="input-group form-group">
                                        <span class="input-group-addon transparent">Filters</span>
                                        <div class="text-right">
                                            <label class="toggle">
                                                <input type="hidden" name="team_filter" data-checked="" checked>
                                                <span class="handle"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="interface-styling">
                            <h5>Interface styling</h5>
                            <div class="row">
                                <div class="col-md-6 button_color model-field">
                                    <div class="input-group bootstrap-colorpicker form-group">
                                        <span class="input-group-addon">Button BG:</span>
                                        <span class="input-group-addon color"><i></i></span>
                                        <input type="text" name="team_button_color" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6 button_hover_color model-field">
                                    <div class="input-group bootstrap-colorpicker form-group">
                                        <span class="input-group-addon">... On hover:</span>
                                        <span class="input-group-addon color"><i></i></span>
                                        <input type="text" name="team_button_hover_color" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6 button_text_color model-field">
                                    <div class="input-group bootstrap-colorpicker form-group">
                                        <span class="input-group-addon">Button label:</span>
                                        <span class="input-group-addon color"><i></i></span>
                                        <input type="text" name="team_button_text_color" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6 button_text_hover_color model-field">
                                    <div class="input-group bootstrap-colorpicker form-group">
                                        <span class="input-group-addon">... On hover:</span>
                                        <span class="input-group-addon color"><i></i></span>
                                        <input type="text" name="team_button_text_hover_color" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-group">
                                        <span class="input-group-addon">Filter font:</span>
                                        <div class="display-table">
                                            <div class="display-table-cell filter_font_size model-field">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    name="team_filter_font_size"
                                                    placeholder="14px"/>
                                            </div>
                                            <div class="display-table-cell">
                                                <div class="btn-group filter_font_style font-style btn-group-checkbox" data-field-name="filter">
                                                    <label type="button" class="btn btn-default filter_bold btn-bold model-field" data-option-name="bold">
                                                        <input type="hidden"
                                                               style="display: none;"
                                                               class="option-bold"
                                                               name="team_filter_bold"
                                                               value=""
                                                               checked
                                                               data-value=""
                                                               data-checked="" />
                                                        <strong>B</strong>
                                                    </label>
                                                    <label type="button" class="btn btn-default filter_italic btn-italic model-field" data-option-name="italic">
                                                        <input type="hidden"
                                                               style="display: none;"
                                                               name="team_filter_italic"
                                                               class="option-italic"
                                                               value=""
                                                               checked
                                                               data-value=""
                                                               data-checked=""/>
                                                        <em>I</em>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="display-table-cell">
                                                <div class="btn-group filter_text_transform btn-group-text-transform btn-group-radio model-field" data-field-name="filter">
                                                    <label type="button" class="btn btn-default active filter_text_transform_none" data-option-name="text_transform">
                                                        <input type="radio"
                                                               style="display: none;"
                                                               name="team_filter_text_transform"
                                                               class="option-text-transform"
                                                               checked
                                                               data-value="none" />
                                                        Aa
                                                    </label>
                                                    <label type="button" class="btn btn-default filter_text_transform_uppercase" data-option-name="text_transform">
                                                        <input type="radio"
                                                               style="display: none;"
                                                               name="team_filter_text_transform"
                                                               class="option-text-transform"
                                                               data-value="uppercase"/>
                                                        AA
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="effects-label">Effects</h5>
                        <div class="row">
                            <div class="col-md-6 reveal model-field">
                                <div class="input-group form-group">
                                    <span class="input-group-addon transparent">Revealing</span>
                                    <div class="text-right">
                                        <label class="toggle">
                                            <input type="hidden" name="team_reveal" data-checked="" checked>
                                            <span class="handle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 hover model-field">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Hover:</span>
                                    <select name="team_hover">
                                        <option value="off" selected>Off</option>
                                        <option value="apollo">Apollo</option>
                                        <option value="jazz">Jazz</option>
                                        <option value="hera">Hera</option>
                                        <option value="phoebe">Phoebe</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h5 class="layout-label">Layout settings</h5>
                        <div class="row">
                            <div class="col-md-6 card_width model-field">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Cards in row:</span>
                                    <select name="team_card_width">
                                        <option value="1" selected>1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 gaps model-field">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Gaps:</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="team_gaps"
                                        placeholder="In pixels"/>
                                </div>
                            </div>
                            <div class="col-md-6 align model-field">
                                <div class="input-group form-group">
                                    <span class="input-group-addon">Align:</span>

                                    <div class="btn-group btn-group-justified team-align">
                                        <label type="button" class="btn btn-default active align-left">
                                            <input type="radio" style="display: none;" name="team_align" checked data-value="left"/>
                                            <svg viewBox="0 0 36 36">
                                                <use xlink:href="#icon-align-left" transform="translate(10, 0)"></use>
                                            </svg>
                                        </label>
                                        <label type="button" class="btn btn-default align-center">
                                            <input type="radio" style="display: none;" name="team_align" data-value="center"/>
                                            <svg viewBox="0 0 36 36">
                                                <use xlink:href="#icon-align-center" transform="translate(-40, 0)"></use>
                                            </svg>
                                        </label>
                                        <label type="button" class="btn btn-default align-right">
                                            <input type="radio" style="display: none;" name="team_align" data-value="right"/>
                                            <svg viewBox="0 0 36 36">
                                                <use xlink:href="#icon-align-right" transform="translate(-90, 0)"></use>
                                            </svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="card-label">Card settings</h5>
                        <div class="row">
                            <div class="col-md-6 card_gaps model-field">
                                <div class="input-group form-">
                                    <span class="input-group-addon">Inner gaps:</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="team_card_gaps"
                                        placeholder="In pixels"/>
                                </div>
                            </div>
                            <div class="col-md-6 card_base_color model-field">
                                <div class="input-group bootstrap-colorpicker form-group">
                                    <span class="input-group-addon">Background:</span>
                                    <span class="input-group-addon color"><i></i></span>
                                    <input type="text" name="team_card_base_color" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-6 card_border_color model-field">
                                <div class="input-group bootstrap-colorpicker form-group">
                                    <span class="input-group-addon">Border:</span>
                                    <span class="input-group-addon color"><i></i></span>
                                    <input type="text" name="team_card_border_color" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-6 card_shadow_color model-field">
                                <div class="input-group bootstrap-colorpicker form-group">
                                    <span class="input-group-addon">Shadow:</span>
                                    <span class="input-group-addon color"><i></i></span>
                                    <input type="text" name="team_card_shadow_color" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-md-6 card_even_row_color model-field">
                                <div class="input-group bootstrap-colorpicker form-group">
                                    <span class="input-group-addon">Even row BG:</span>
                                    <span class="input-group-addon color"><i></i></span>
                                    <input type="text" name="team_card_even_row_color" value="" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <h5>Block visibility</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row team-blocks-visibility">
                                    <div class="col-md-6 body_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Body:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_body_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 photo_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Photo:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_photo_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 name_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Name:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_name_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 divider_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Divider:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_divider_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 position_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Occupation:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_position_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 short_bio_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Biography:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_short_bio_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 email_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Email:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_email_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 phone_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Phone:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_phone_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 skype_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Skype:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_skype_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 link_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Website:</span>
                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_link_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 social_visible visible">
                                        <div class="input-group form-group">
                                            <span class="input-group-addon transparent">Social:</span>

                                            <div class="text-right">
                                                <label class="toggle">
                                                    <input type="hidden" name="team_social_visible" data-checked="" checked>
                                                    <span class="handle"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="team_blocks_order" value="">
                            </div>
                        </div>
                        <h5>Custom CSS</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea name="team_custom_css" id="" cols="30" rows="10" class="form-control" placeholder="Put your custom CSS here"></textarea>
                            </div>
                        </div>
                    <!--<div class="col-md-12">
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#blocks_visibility">
                            Block visibility
                        </button>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#custom_css">
                            Custom CSS
                        </button>
                        <button class="btn btn-primary" type="button" data-action="random-team-style">
                            Random style
                        </button>
                    </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

