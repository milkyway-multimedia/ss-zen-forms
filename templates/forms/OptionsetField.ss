<ul id="$ID" class="$extraClass">
	<% loop $Options %>
		<li class="$Class<% if $isDisabled %> selectable-disabled<% end_if %>">
			<input id="$ID" class="radio" name="$Name" type="radio" value="$Value"<% if $isChecked %> checked<% end_if %><% if $isDisabled %> disabled<% end_if %> />
			<label for="$ID">$Title</label>
		</li>
	<% end_loop %>
</ul>
