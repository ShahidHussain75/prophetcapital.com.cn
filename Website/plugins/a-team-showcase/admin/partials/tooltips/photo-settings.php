<div class="input-group form-group team-photo-settings {{field_name}}_settings" id="team-photo-settings" data-field-name="{{field_name}}">
    <!--<div class="photo_shape">
        <select name="team_photo_shape" data-field="photo_shape">
            <option value="square" selected>Square</option>
            <option value="round">Round</option>
        </select>
    </div>-->
    <div class="btn-group {{field_name}}_shape btn-group-photo-shape btn-group-radio model-field" data-field-name="{{field_name}}">
        <label type="button" class="btn btn-default active {{field_name}}_shape_square" data-option-name="shape">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_shape"
                   class="option-photo-shape"
                   checked
                   data-value="square" />
            <svg viewBox="0 0 36 36">
                <use xlink:href="#icon-image-square" transform="translate(-248, -547)"></use>
            </svg>
        </label>
        <label type="button" class="btn btn-default {{field_name}}_shape_round" data-option-name="shape">
            <input type="radio"
                   style="display: none;"
                   name="team_{{field_name}}_shape"
                   class="option-photo-shape"
                   data-value="round"/>
            <svg viewBox="0 0 36 36">
                <use xlink:href="#icon-image-circle" transform="translate(-197, -547)"></use>
            </svg>
        </label>
    </div>
    <div class="{{field_name}}_size model-field">
        <input type="hidden" name="team_photo_size" value="">
        <select name="team_photo_size_select" data-field="photo_size">
            <option value="a-full" selected>Full</option>
            <!--<option value="a-large">Large</option>
            <option value="a-medium">Medium</option>
            <option value="a-thumbnail">Thumbnail</option>
            <option value="a-small">Small</option>-->
            <option value="custom">Custom</option>
        </select>
    </div>
    <div class="{{field_name}}_width model-field">
        <span class="field_label">Width</span>
        <input type="text" name="team_{{field_name}}_width" class="form-control" placeholder="100px">
    </div>
    <div class="blocks_top_margin {{field_name}}_top_margin model-field">
        <span class="field_label">Top Margin</span>
        <input type="text" name="team_{{field_name}}_top_margin" class="form-control" placeholder="10px">
    </div>
</div>
