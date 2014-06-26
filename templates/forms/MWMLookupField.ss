<% if isReadonly %>
<span id="$ID" class="uneditable-input $extraClass"<% if $Description %> title="$Description"<% end_if %>>
$Value
</span>
<input $AttributesHTML value="$Value" />
<% end_if %>