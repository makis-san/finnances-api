<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use JWTAuth;
use JWTFactory;
use \Firebase\JWT\JWT;

class AuthController extends Controller
{
    protected $key = "QU4CK5";

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', $credentials['email'])->first();

        if (!Hash::check($credentials['password'], $user->getAuthPassword())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token, $user);
    }

    public function verify(Request $request)
    {
        $token = JWTAuth::fromUser($request->usuario);
        return response()->json(['novoToken' => $token]);
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 10000
        ]);
    }

    public function SendResetPasswordToken(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email']);
        $token = JWT::encode($credentials, $this->key);

        return response()->json(["token" => $token]);
    }

    public function ResetPasswordByToken(Request $request, $token)
    {
        $newPassword = $request->validate(['newPassword' => 'required']);
        $decodedEmail = JWT::decode($token, $this->key, array('HS256'));

        dd($newPassword);

        return response()->json(["token" => $token]);
    }

    public function guard()
    {
        return Auth::guard();
    }
}
