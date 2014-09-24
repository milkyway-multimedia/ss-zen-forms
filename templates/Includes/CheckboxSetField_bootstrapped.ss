<ul $AttributesHTML>
    <% if $Options.Count %>
        <% loop $Options %>
            <li class="$Class">
                <span class="checkbox-control">
                    <input $Up.OptionAttributesHTML id="$ID" name="$Name" type="checkbox" value="$Value"<% if $isChecked %> checked="checked"<% end_if %><% if $isDisabled %> disabled="disabled"<% end_if %><% if $Up.getAttribute('required') %> required="required"<% end_if %> />
                    <label for="$ID" class="checkable"></label>
                </span>
                <label for="$ID" class="checkbox-control--label">$Title</label>
            </li>
        <% end_loop %>
    <% else %>
        <li class="none">No options available</li>
    <% end_if %>
</ul>