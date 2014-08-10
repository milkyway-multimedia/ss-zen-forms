<div id="$ID-Holder" $HolderAttributesHTML>
    <label id="$ID-Label" for="$ID" $LabelAttributesHTML>
        <% if $Label %>$Label<% else %>$Title<% end_if %>
        <% if $Label || $Title %>
            <% if $Form && $Form.AppendToRequiredLabels && $isRequired %> $Form.AppendToRequiredLabels<% end_if %>
        <% end_if %>
    </label>
    <div class="controls">
        <% if $AppendedText || $PrependedText %>
        	<div class="<% if $AppendedText %>$input-append<% end_if %><% if $PrependedText %> input-prepend<% end_if %>">
        		<% if $PrependedText %><label class="add-on" for="$ID">$PrependedText</label><% end_if %>$Field<% if $AppendedText %><label class="add-on" for="$ID">$AppendedText</label><% end_if %>
        	</div>
        <% else %>
          $Field
        <% end_if %>

        <% if $RightTitle %>
		    <label class="help-inline control-label-right" for="$ID">$RightTitle</label>
        <% end_if %>
        
        <% if $isRequired && $RequiredText %>
        	<i class="required-sign $RequiredIcon<% if $RequiredText %> field-popover data-content="$RequiredText<% end_if %>" data-for="{$ID}"></i>
        <% end_if %>

        <% if $InlineHelpText %>
        <span class="help-inline">$InlineHelpText</span>
        <% end_if %>

        <% if $Description %>
		    <p class="help-block control-description">$Description</p>
        <% end_if %>

        <% if $HelpText %>
        <p class="help-block">$HelpText</p>
        <% end_if %>

        <% if $Message %>
            <p class="help-block $MessageType">$Message</p>
        <% end_if %>

        <% if $EmailSuggest %>
		    <div class="email-suggest"><div><% _t('EMAIL_SUGGEST-TEXT_', 'Did you mean:') %> <a class="full-email"></a>?</div></div>
        <% end_if %>
    </div>
</div>

