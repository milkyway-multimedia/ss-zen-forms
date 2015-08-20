
    $PasswordField.FieldHolder

    <% if $PasswordStrengthGuide %>
        <div class="form-group form-group_guide password-guide--holder">
            <div class="progress password-guide" id="{$PasswordField.ID}-Guide"<% if $PasswordStrengthHelper %> data-helper="$PasswordStrengthHelper"<% end_if %>><div class="progress-bar password-guide--bar"></div></div>
        </div>
    <% end_if %>

    $ConfirmPasswordField.FieldHolder