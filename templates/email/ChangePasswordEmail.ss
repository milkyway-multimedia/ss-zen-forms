<% include Email_Top %>

<p><% _t('HELLO', 'Hello') %> $Name,</p>

<p>
	<% _t('ChangePasswordEmail.ss.CHANGEPASSWORDTEXT1', 'You changed your password for', 'for a url') %> <a href="$AbsoluteBaseURL"><% if $SiteConfig %>{$SiteConfig.Title}: <% end_if %>$AbsoluteBaseURL</a>.<br />
	<% _t('ChangePasswordEmail.ss.CHANGEPASSWORDTEXT2', 'You can now use the following credentials to log in:') %>
</p>

<p>
	<% _t('ChangePasswordEmail.ss.EMAIL', 'Email') %>: $Email<br />
	<% _t('ChangePasswordEmail.ss.PASSWORD', 'Password') %>: $CleartextPassword
</p>

<% include Email_Bottom %>