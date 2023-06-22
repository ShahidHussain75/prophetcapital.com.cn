<?php
    /**
        Add or Edit Employer Form
    */
?>
<div id="employer-form" data-model="employer" data-type="form">
    <input type="hidden" name="employer_id"/>
    <div class="panel">
        <div class="panel-heading">
            <h6>General Information</h6>
        </div>
        <div class="panel-body">
            <div class="form-group foto-upload clearfix">
                <input type="hidden" name="employer_thumbnail_id"/>
                <div class="foto-wrapper" id="employer-form-foto"></div>
                <div>
                    <h5>Add Employee's Photo</h5>
                    <p class="hint">At least 100x100px. JPG and PNG are OK.</p>
                    <ul>
                        <li>
                            <button data-action="upload_photo" class="btn btn-default" title="Browse">
                                <span>Browse</span>
                            </button>
                        </li>
                        <li>
                            <button id="employer-form-foto-remove" data-action="remove_photo" class="btn btn-danger" title="Remove">
                                <span>Remove</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <h5>Describe Employee</h5>
            <div class="input-group form-group">
                <span class="input-group-addon">Employee Name:</span>
                <input type="text" class="form-control" name="employer_name" placeholder="e.g. John Doe"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Occupation:</span>
                <input type="text" class="form-control" name="employer_position" placeholder="e.g. Designer"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Department:</span>
                <input type="text" class="form-control" name="employer_department" placeholder="e.g. Designers"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Profile URL:</span>
                <input type="text" class="form-control" name="employer_profile" placeholder="Individual bio page on your website (if exist)"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Biography:</span>
                <textarea name="employer_short_bio" class="form-control" rows="3" placeholder="Tell awesome story" ></textarea>
            </div>
            <div class="alert" id="ajax-form-result"></div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-heading">
            <h6>Contacts & Social profiles</h6>
        </div>
        <div class="panel-body">
            <h5>Contacts</h5>
            <div class="input-group form-group">
                <span class="input-group-addon">Email:</span>
                <input type="text" class="form-control" name="employer_email" placeholder="e.g. john@example.com"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Phone:</span>
                <input type="text" class="form-control" name="employer_phone" placeholder="e.g. 555-555-5555"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Skype:</span>
                <input type="text" class="form-control" name="employer_skype" placeholder="e.g. john_doe"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Website:</span>
                <input type="text" class="form-control" name="employer_link" placeholder="Personal website URL"/>
            </div>
            <h5>Social Profiles</h5>
            <div class="input-group form-group">
                <span class="input-group-addon">Facebook:</span>
                <input type="text" class="form-control" name="employer_facebook" placeholder="https://www.facebook.com/username"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Twitter:</span>
                <input type="text" class="form-control" name="employer_twitter" placeholder="https://twitter.com/username"/>
            </div>
            <div class="input-group form-group">
                <span class="input-group-addon">Linkedin:</span>
                <input type="text" class="form-control" name="employer_linkedin" placeholder="https://www.linkedin.com/profile/view?id=0000000"/>
            </div>
        </div>
    </div>
</div>
