<div>
	<p>Hi {{ $data['nickname'] }},</p>
	<p>You have been created an account by the admin to join the Japanese lesson of Study Japanese.</p>
	<p>The login information:<br>
		Email: {{ $data['email'] }}<br>										
		Password: {{ $data['password'] }}<br>								
		Login link: <a href="{{ $data['url'] }}">{{ $data['url'] }}</a><br>								
	<br>
	At Study Japanese, we are committed to helping you improve your Japanese quickly.</p>

	<p>With love,<br>
	The Student Japanese Team.<br>
	31 Tran Phu Street, Da Nang, Viet Nam<br>
	</p>

</div>
