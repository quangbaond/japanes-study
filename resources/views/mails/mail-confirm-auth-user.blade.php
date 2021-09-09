<div>
    <p>{{ __('student.mail.content1') }} {{ $user->nickname }}</p>
    <p>{{ __('student.mail.content2') }}</p>
    <br>
    <p>{{ __('student.mail.content3') }}</p>
    <a href="{{ $user->activation_link }}" target="_blank">{{ $user->activation_link }}</a>
    <br>
    <br>
    <p>{{ __('student.mail.footer1') }}</p>
    <p>{{ __('student.mail.footer2') }}</p>
    <p>{{ __('student.mail.address') }}</p>
</div>
