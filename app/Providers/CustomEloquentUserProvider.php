<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;


class CustomEloquentUserProvider extends EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['senha'];

        error_log($plain);
        error_log($user);
        error_log(Hash::check($plain, $user->getAuthPassword()));

        if (Hash::check($plain, $user->getAuthPassword())) {
            return true;
        } else {
            return false;
        }
    }
}
