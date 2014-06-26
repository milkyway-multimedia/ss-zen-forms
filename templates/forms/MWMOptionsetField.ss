<% if $Options.Count %>
    <% loop $Options %>
        <div class="<% with $Up %><% if $instanceOfField('OptionsetField') %>radio<% else %>checkbox<% end_if %><% if $Inline %>-inline<% end_if %><% end_with %><% if $isDisabled %> disabled-selectable<% end_if %> $OptionClasses">
	    <input id="$ID" class="radio" name="$Name" type="radio" value="$Value"<% if $isChecked %> checked="checked"<% end_if %><% if $isDisabled %> disabled="disabled"<% end_if %><% if $First %> $Up.AttributesHTML<% end_if %>>
        <label for="$ID" class="checkable"></label>
        <label for="$ID" class="$Up.LabelClasses">$Title</label>
    </div>
    <% end_loop %>
<% else %>
    <% _t('NONE_AVAILABLE', 'No options available') %>
<% end_if %>