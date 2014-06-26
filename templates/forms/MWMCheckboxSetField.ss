    <% if $Options.Count %>
            <% loop $Options %>
                <label class="checkbox <% if $Up.Inline %>inline<% end_if %> $Top.LabelClasses" for="$ID">
                    <input id="$ID" class="checkbox" name="$Name" type="checkbox" value="$Value"<% if $isChecked %> checked="checked"<% end_if %><% if $isDisabled %> disabled="disabled"<% end_if %><% if $First %> $Up.AttributesHTML<% end_if %>>
                    $Title
                </label>
            <% end_loop %>
        <% else %>
            <p class="none checkbox">No options available</p>
    <% end_if %>
