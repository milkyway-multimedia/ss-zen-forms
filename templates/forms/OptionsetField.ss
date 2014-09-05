<ul id="$ID" class="$extraClass">
	<% loop $Options %>
		<li class="$Class<% if $isDisabled %> selectable-disabled<% end_if %>">
            <span class="radio-control">
                <input $AttributesHTML />
                <label for="$ID" class="checkable"></label>
            </span>
			<label for="$ID">$Title</label>
		</li>
	<% end_loop %>
</ul>
