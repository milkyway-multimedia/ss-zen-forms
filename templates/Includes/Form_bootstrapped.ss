<% if $IncludeFormTag %>
<form $AttributesHTML>
<% end_if %>
    <% include FormMessage_bootstrapped %>

	<fieldset $FieldsetAttributesHTML>
		<% if $Legend %><legend>$Legend</legend><% end_if %>
		<% loop $Fields %>
			$FieldHolder
		<% end_loop %>
	</fieldset>

	<% if $Actions %>
	<div class="form-actions">
		<% loop $Actions %>
			$Field
		<% end_loop %>
	</div>
	<% end_if %>
<% if $IncludeFormTag %>

<div id="<% if $FormModalID %>$FormModalID<% else %>{$FormName}-Modal<% end_if %>" class="<% if $FormModalClasses %>$FormModalClasses<% else %>modal fade<% end_if %>" data-modal="modal">
	<div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 id="{$FormModal}-Title" role="title"><% if $FormModalTitle %>$FormModalTitle<% else %>&nbsp;<% end_if %></h4>
	  </div>
	  <div class="modal-body" id="{$FormModal}-Content" role="content">
		<p>$OnCompleteMessage</p>
	  </div>
	  <div class="modal-footer">
		<a href="#" class="btn btn-link" data-dismiss="modal">Close</a>
	  </div>
    </div>
    </div>
</div>

</form>
<% end_if %>