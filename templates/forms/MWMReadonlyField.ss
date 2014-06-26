<% if $isReadonly %>
    <p id="$ID" class="form-control-static $extraClass"<% if $Description %> title="$Description"<% end_if %>>
        $Value
    </p>
    <input $AttributesHTML value="$Value" />
<% end_if %>
