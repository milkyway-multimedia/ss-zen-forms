<% if not $FormOnly %>
    <div id="<% if $FormModalID %>$FormModalID<% else %>{$FormName}-Modal<% end_if %>" class="<% if $FormModalClasses %>$FormModalClasses<% else %>modal fade<% end_if %>" data-modal="modal">
	<div class="modal-dialog">
	<div class="modal-content">

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="{$FormName}-Modal-Title" role="title"><% if $FormModalTitle %>$FormModalTitle<% else %>&nbsp;<% end_if %></h3>
	</div>

<% end_if %>
	<% if $IncludeFormTag %>
	<form $AttributesHTML>
	<% end_if %>

	<div class="modal-body" id="{$FormName}-Modal-Content" role="content">

    <% include FormMessage_bootstrapped %>

	<fieldset $FieldsetAttributesHTML>
		<% if $Legend %><legend>$Legend</legend><% end_if %>
		<% loop $Fields %>
		$FieldHolder
		<% end_loop %>
	</fieldset>

	</div>
	<div class="modal-footer">
		<% if $Actions %>
			<% loop $Actions %>
			$Field
			<% end_loop %>
		<% end_if %>
		<a href="#" class="btn btn-link" data-dismiss="modal"><% _t('CANCEL', 'Cancel') %></a>
	</div>

	<% if $IncludeFormTag %>
	</form>
	<% end_if %>

<% if not $FormOnly %>
    </div>
    </div>
</div>
<% end_if %>