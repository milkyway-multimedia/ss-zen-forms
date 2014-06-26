<% if $Message %>
    <% if $MessageType == "good" %>
		<div id="{$FormName}_alert" class="alert alert-success wow subtle-bounce">$Message</div>
    <% else_if $MessageType == "info" %>
		<div id="{$FormName}_alert" class="alert alert-info wow subtle-bounce">$Message</div>
    <% else_if $MessageType == "bad" %>
		<div id="{$FormName}_alert" class="alert alert-danger wow subtle-bounce">$Message</div>
    <% else %>
		<div id="{$FormName}_alert" class="alert $MessageType wow subtle-bounce">$Message</div>
    <% end_if %>
<% else %>
	<div id="{$FormName}_alert" class="alert hide"></div>
<% end_if %>