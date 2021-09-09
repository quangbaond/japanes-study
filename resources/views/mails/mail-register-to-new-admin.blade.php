<div>
    <p>{{$data['nickname']}}さん、<br></p>
    <p>管理者によって、Study Japaneseのアドミンのアカウントが作成されました。<br></p>
    <p>ログイン情報：<br>
        メールアドレス: {{$data['email']}}<br>
        パスワード: {{$data['password']}}<br>
        ログインリンク: <a href="{{ $data['url'] }}">{{ $data['url'] }}</a><br>
    </p>
    <p>以上、よろしくお願いいたします。<br>
        The Student Japanese Team.<br>
        31 Tran Phu Street, Da Nang, Viet Nam<br>
    </p>
</div>
