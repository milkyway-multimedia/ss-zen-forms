<% if $VisibleOnClickField %>
    $VisibleOnClickField.FieldHolder
<% end_if %>

<div id="$ID-Holder" class="$HolderClasses bootstrap-field-holder {$Type}-holder" $HolderAttributesHTML>
<label class="control-label $LabelClasses" $LabelAttributesHTML><% if Label %>$Label<% else %>$Title<% end_if %><% if $Form && $Form.AppendToRequiredLabels && $isRequired %> $Form.AppendToRequiredLabels<% end_if %></label>

<% if $PasswordField %>
<div class="controls password-field $PasswordField.removeHolderClass('form-group').HolderClasses" $PasswordField.HolderAttributesHTML>
		<% if $PasswordField.AppendedText || $PasswordField.PrependedText %>
		<div class="<% if $PasswordField.AppendedText %>input-append<% end_if %><% if $PasswordField.PrependedText %> input-prepend<% end_if %>">
			<% if $PasswordField.PrependedText %>
			<label class="add-on" for="$PasswordField.ID">$PasswordField.PrependedText</label>
			<% end_if %>

			$PasswordField.Field

			<% if $PasswordField.AppendedText %>
			<label class="add-on" for="$PasswordField.ID">$PasswordField.AppendedText</label>
			<% end_if %>
		</div>
		<% else %>
			$PasswordField.Field
		<% end_if %>

<% if $InlineHelpText %>
	<span class="help-inline">$InlineHelpText</span>
<% end_if %>

<% if $PasswordField.Message %><p class="alert alert-error help-block $PasswordField.MessageType">$PasswordField.Message</p><% end_if %>

<% if $PasswordGenerator %>
<div class="password-generator-holder help-inline">
	<button id="{$ID}-Generator" type="button" class="password-generator btn btn-small">$GeneratorLabel</button>
	<span class="password-generator-display hide">$Option(GeneratorText) <span class="password-generated"></span></span>
</div>
<% end_if %>

    <% if $PasswordStrengthGuide %>
		<div class="password-guide-holder">
			<div class="progress password-guide" id="{$PasswordField.ID}-Guide"<% if $PasswordStrengthHelper %> data-helper="$PasswordStrengthHelper"<% end_if %>><div class="progress-bar"></div></div>
		</div>
    <% end_if %>
</div>
<% end_if %>

<% if $ConfirmPasswordField.Title %>
	<label class="control-label confirmedpassword-field-label $ConfirmPasswordField.LabelClasses" $ConfirmPasswordField.LabelAttributesHTML>$ConfirmPasswordField.Title<% if $Form && $Form.AppendToRequiredLabels && $isRequired %> $Form.AppendToRequiredLabels<% end_if %></label>
<% end_if %>

<div class="controls confirmedpassword-field-col">
<% if $ConfirmPasswordField %>
<div class="password-field $ConfirmPasswordField.removeHolderClass('form-group').HolderClasses" $ConfirmPasswordField.HolderAttributesHTML>
	<% if $ConfirmPasswordField.AppendedText || $ConfirmPasswordField.PrependedText %>
	<div class="<% if $ConfirmPasswordField.AppendedText %>input-append<% end_if %><% if $ConfirmPasswordField.PrependedText %> input-prepend<% end_if %>">
		<% if $ConfirmPasswordField.PrependedText %>
			<label class="add-on" for="$ConfirmPasswordField.ID">$ConfirmPasswordField.PrependedText</label>
		<% end_if %>

		$ConfirmPasswordField.Field

		<% if $ConfirmPasswordField.AppendedText %>
			<label class="add-on" for="$ConfirmPasswordField.ID">$ConfirmPasswordField.AppendedText</label>
		<% end_if %>
	</div>
	<% else %>
		$ConfirmPasswordField.Field
	<% end_if %>
</div>
<% end_if %>

<% if $ConfirmPasswordField.Message %><p class="alert alert-error help-block $ConfirmPasswordField.MessageType">$ConfirmPasswordField.Message</p><% end_if %>

<% if $Message %><p class="alert alert-error help-block $MessageType">$Message</p><% end_if %>

    <% if $HelpText %>
		<p class="help-block<% if $PasswordStrengthGuide %> helper<% end_if %>">$HelpText</p>
    <% end_if %>
</div>

</div>

