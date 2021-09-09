<?php

namespace App\Services;

use App\Mail\ConfirmMailChanges;
use App\Mail\MailConfirmAuthUser;
use App\Mail\LoginInfoNotify;
use App\Mail\MailRegisterToNewAdmin;
use App\Mail\ResetPasswordNotify;
use App\Models\User;
use App\Models\UserActivation;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailConfirmationIsChangedByStudent;

class MailService
{
    /**
     * @var UserActivation
     */
    private $userActivation;

    public function __construct(UserActivation $userActivation)
    {
        $this->userActivation = $userActivation;
    }

    public function sendActivationMail($user, $flag = null)
    {
        $token = $this->userActivation->createActivation($user, $flag);
        $user->activation_link = route('student.register.activate', $token);
        $mailable = new MailConfirmAuthUser($user);
        Mail::to($user->email)->send($mailable);
    }

    public function sendEmailConfirmationToBeChanged($user, $new_email , $flag = null) {
        $token = $this->userActivation->createActivation($user, $flag);
        $user->activation_link = route('teacher.change.activate',[$new_email, $token]);
        $mailable = new ConfirmMailChanges($user);
        Mail::to($new_email)->send($mailable);
    }

    public function sendEmailConfirmationToStudentWhenChanged($user, $new_email , $flag = null) {
        $token = $this->userActivation->createActivation($user, $flag);
        $user->activation_link = route('student.change.activate',[$new_email, $token]);
        $mailable = new MailConfirmationIsChangedByStudent($user);
        Mail::to($new_email)->send($mailable);
    }

    public function activateUser($token)
    {
        $activation = $this->userActivation->getActivationByToken($token);
        if ($activation === null) {
            return [
                'status' => false,
                'flag' => 'token-null'
            ];
        }
        if ($activation->created_at->addMinutes(60*24) < now()) {
            return [
                'status' => false,
                'flag' => 'expire'
            ];
        } else {
            $user = User::find($activation->user_id);
            $user->auth = 1;
            $user->save();
            $this->userActivation->deleteActivation($token);
            return [
                'status' => true,
                'flag' => 'success',
                'user_id' => $activation->user_id
            ];
        }


    }

    private function shouldSend($user)
    {
        $activation = $this->userActivation->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }
    public function sendLoginInfoMail($data){
        $mailable = new LoginInfoNotify($data);
        Mail::to($data['email'])->send($mailable);
    }

    public function sendInfoResetPassword($data, $content_page){
        $mailable = new ResetPasswordNotify($data, $content_page);
        Mail::to($data['email'])->send($mailable);
    }

    public function sendMailRegisterToNewAdmin($data) {
        $mailable = new MailRegisterToNewAdmin($data);
        Mail::to($data['email'])->send($mailable);
    }

}
