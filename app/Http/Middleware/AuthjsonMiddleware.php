<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class AuthjsonMiddleware
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $token = $request->input('auth-token');
        if (Auth::guest() && empty($token)) {
            return response(["status" => "unauthorized", 'token' => $token]);
        } else {
            $user = User::where('auth_token', $token)->first();
            if ($user) {
                Auth::loginUsingId($user->id);
            } else {
                return \response(['status' => 'bad token']);
            }
        }
        return $next($request);
    }
}
