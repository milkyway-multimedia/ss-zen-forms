<div id="{$ID}-Holder" class="$HolderClasses bootstrap-field-holder {$Type}-holder" $HolderAttributesHTML>
    <label class="control-label"><% if $RightTitle %>$RightTitle<% end_if %></label>
    <div class="controls checkbox">
	    $Field
        <label class="$LabelClasses" $LabelAttributesHTML>
            $Title<% if $Form && $Form.AppendToRequiredFields && $Required %> $Form.AppendToRequiredFields<% end_if %>
        </label>
        <% if $InlineHelpText %>
            <span class="help-inline">$InlineHelpText</span>
        <% end_if %>

        <% if $HelpText %>
            <p class="help-block">$HelpText</p>
        <% end_if %>
    </div>
</div>

