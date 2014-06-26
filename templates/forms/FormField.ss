<% if $isReadonly %>
	<span $AttributesHTML('type','value')>
		$Value
	</span>
<% else %>
	<input $AttributesHTML />
<% end_if %>
