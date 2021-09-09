<div>
	<p>{{$user->nickname}}先生<br></p>
	<p>メールアドレス変更手続きはまだ完了しません。<br></p>
    <p>24時間以内に下記の認証URLをクリックして、メールアドレス変更手続きを完了してください。</p>

    <a href="{{ $user->activation_link }}" target="_blank">{{ $user->activation_link }}</a>

	<p>With love,<br>
	The Student Japanese Team.<br>
	31 Tran Phu Street, Da Nang, Viet Nam<br>
	</p>
</div>
