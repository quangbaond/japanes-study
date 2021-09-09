<div>
    <p>{{$data['nickname']}}<br></p>
    <p>The administrator has reset the password for your account.<br></p>
    <p>Login information：<br>
        Email: {{$data['email']}}<br>
        Password: {{$data['password']}}<br>
        Link login: <a href="{{ $data['url'] }}">{{ $data['url'] }}</a><br>
    </p>
    <p>With love,<br>
        The Student Japanese Team.<br>
        31 Tran Phu Street, Da Nang, Viet Nam<br>
    </p>
</div>
