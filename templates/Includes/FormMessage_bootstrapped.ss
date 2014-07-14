<% if $Message %>
    <% if $MessageType == "good" %>
		<div id="{$FormName}-Alert" class="alert alert-success subtle-bounce animated">$Message</div>
    <% else_if $MessageType == "info" %>
		<div id="{$FormName}-Alert" class="alert alert-info subtle-bounce animated">$Message</div>
    <% else_if $MessageType == "bad" %>
		<div id="{$FormName}-Alert" class="alert alert-danger subtle-bounce animated">$Message</div>
    <% else %>
		<div id="{$FormName}-Alert" class="alert $MessageType subtle-bounce animated">$Message</div>
    <% end_if %>
<% else %>
	<div id="{$FormName}-Alert" class="alert subtle-bounce hide invisible"></div>
<% end_if %>