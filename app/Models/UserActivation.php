<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserActivation extends Model
{
    protected $table = 'user_activations';

    protected function getToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function createActivation($user, $flag = null)
    {

        $activation = $this->getActivation($user);

        if (!$activation) {
            return $this->createToken($user, $flag);
        }
        return $this->regenerateToken($user, $flag);

    }

    private function regenerateToken($user, $flag = null)
    {
        $token = $this->getToken();
        UserActivation::where('user_id', $user->id)->update([
            'token' => $token,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    private function createToken($user, $flag = null)
    {
        $token = $this->getToken();
        UserActivation::insert([
            'user_id' => $user->id,
            'token' => $token,
            'flag' => $flag,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    public function getActivation($user)
    {
        return UserActivation::where('user_id', $user->id)->first();
    }

    public function getActivationByToken($token)
    {
        return UserActivation::where('token', $token)->first();
    }

    public function deleteActivation($token)
    {
        UserActivation::where('token', $token)->delete();
    }
}
