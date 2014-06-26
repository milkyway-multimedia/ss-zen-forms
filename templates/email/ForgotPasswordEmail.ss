<% include Email_Top %>

<p><% _t('HELLO', 'Hello') %> $Name,</p>

<% if $Content %>
	$Content
<% else %>
	<p><% _t('ForgotPasswordEmail.ss.TEXT1', 'Here is your') %> <a href="$PasswordResetLink"><% _t('ForgotPasswordEmail.ss.TEXT2', 'password reset link') %></a>:</p>
	<p><a href="$PasswordResetLink">$PasswordResetLink</a></p>
    <p><% _t('ForgotPasswordEmail.ss.TEXT3', 'for') %> <a href="$AbsoluteBaseURL"><% if $SiteConfig %>{$SiteConfig.Title} &lt;$baseWebsiteURL&gt;<% else %>$baseWebsiteURL<% end_if %></a></p>
<% end_if %>

<% include Email_Bottom %>