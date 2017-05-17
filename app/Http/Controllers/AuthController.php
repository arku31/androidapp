<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /**
     * Реализация авторизации - совместимость со старой версией
     * @return array
     */
    public function auth(Request $request)
    {
        $login = $request->login;
        $password = $request->password;
        $register = $request->register;
        $social_user_id = $request->social_user_id;

        if ($social_user_id) {
            return $this->loginWithToken($request);
        }

        if (!isset($login) || !isset($password)) {
            return ['status' => 'Input error'];
        }
        if (isset($register) && $register == 1) {
            if (User::whereLogin($login)->count() != 0) {
                return ['status' => 'Login busy already'];
            } else {
                $user = new User;
                $user->login = $login;
                $user->password = $password;
                $user->auth_token = bcrypt($login.$password.rand(1, 999));
                $user->save();
                Auth::loginUsingId($user->id);
                return ['status' => 'success', 'id' => $user->id];
            }
        } else {
            //пытаемся залогинить
            $user = User::whereLogin($login)->first();
            if (empty($user)) {
                return ['status' => 'Wrong login'];
            }
            if ($user && $user->password == $password) {
                if ($user->remember_token) {
                    $user->remember_token = null;
                    $user->save();
                }
                Auth::loginUsingId($user->id, true);
                return [
                    'status' => 'success',
                    'id' => $user->id,
                    'auth_token' => Auth::user()->auth_token
                ];
            } else {
                return ['status' => 'Wrong password'];
            }
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return ['status' => 'success'];
    }

    private function loginWithToken($request)
    {
        $user = User::where('social_user_id', $request->social_user_id)->first();
        if (empty($user)) {
            $user = new User();
            $user->social_user_id = $request->social_user_id;
            $user->login = 'user_'.$request->social_user_id;
            $user->password = md5($request->social_token);
            $user->auth_token = bcrypt($request->social_user_id.rand(1, 999));
            $user->save();
            Auth::loginUsingId($user->id, true);
        } else {
            Auth::loginUsingId($user->id, true);
        }
        return response([
            'status' => 'success',
            'id' => $user->id,
            'auth_token' => $user->auth_token
        ]);
    }
}