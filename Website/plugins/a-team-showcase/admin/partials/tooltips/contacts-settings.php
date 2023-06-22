<div class="input-group form-group team-font-settings {{field_name}}_settings" id="team-font-settings" data-field-name="{{field_name}}">
    <div class="{{field_name}}_font_size model-field">
        <select name="team_{{field_name}}_font_size" class="option-font-size">
            <option value="8px" selected>8</option>
            <option value="8px">9</option>
            <option value="10px">10</option>
            <option value="12px">12</option>
            <option value="14px">14</option>
            <option value="16px">16</option>
            <option value="18px">18</option>
            <option value="20px">20</option>
            <option value="26px">26</option>
            <option value="32px">32</option>
        </select>
    </div>
    <div class="{{field_name}}_color bootstrap-colorpicker model-field">
        <span class="input-group-addon color"><i></i></span>
        <input type="hidden"
               name="team_{{field_name}}_color"
               class="option-color"
               value=""
               class="form-control" />
    </div>
    <div class="btn-group {{field_name}}_font_style font-style btn-group-checkbox" data-field-name="{{field_name}}">
        <label type="button" class="btn btn-default {{field_name}}_bold btn-bold model-field" data-option-name="bold">
            <input type="hidden"
                   style="display: none;"
                   class="option-bold"
                   name="team_{{field_name}}_bold"
                   value=""
                   checked
                   data-value=""
                   data-checked="" />
            <strong>B</strong>
        </label>
        <label type="button" class="btn btn-default {{field_name}}_italic btn-italic model-field" data-option-name="italic">
            <input type="hidden"
                   style="display: none;"
                   name="team_{{field_name}}_italic"
                   class="option-italic"
                   value=""
                   checked
                   data-value=""
                   data-checked=""/>
            <em>I</em>
        </label>
    </div>
    <div class="btn-group {{field_name}}_text_transform btn-group-text-transform btn-group-radio model-field" data-field-name="{{field_name}}">
        <label type="button" class="btn btn-default active {{field_name}}_text_transform_none" data-option-name="text_transform">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_text_transform"
                   class="option-text-transform"
                   checked
                   data-value="none" />
            Aa
        </label>
        <label type="button" class="btn btn-default {{field_name}}_text_transform_uppercase" data-option-name="text_transform">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_text_transform"
                   class="option-text-transform"
                   data-value="uppercase"/>
            AA
        </label>
    </div>
    <div class="btn-group {{field_name}}_align btn-group-align btn-group-radio model-field" data-field-name="{{field_name}}">
        <label type="button" class="btn btn-default active {{field_name}}_align_left" data-option-name="align">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_align"
                   class="option-align"
                   checked
                   data-value="left" />
            <svg viewBox="0 0 36 36">
                <use xlink:href="#icon-align-left" transform="translate(10, 0)"></use>
            </svg>
        </label>
        <label type="button" class="btn btn-default {{field_name}}_align_center" data-option-name="align">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_align"
                   class="option-align"
                   data-value="center"/>
            <svg viewBox="0 0 36 36">
                <use xlink:href="#icon-align-center" transform="translate(-40, 0)"></use>
            </svg>
        </label>
        <label type="button" class="btn btn-default {{field_name}}_align_right" data-option-name="align">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_align"
                   class="option-align"
                   data-value="right"/>
            <svg viewBox="0 0 36 36">
                <use xlink:href="#icon-align-right" transform="translate(-90, 0)"></use>
            </svg>
        </label>
    </div>
    <div class="blocks_top_margin {{field_name}}_top_margin model-field">
        <span class="field_label">Top Margin</span>
        <input type="text" name="team_{{field_name}}_top_margin" class="form-control" placeholder="10px">
    </div>
    {{hint}}
</div>
