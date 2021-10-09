<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class AuthenticateReferer
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    private $app_host;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
        $this->app_host = parse_url(env("APP_URL", 'http://localhost:3000'), PHP_URL_HOST);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $referrer = $request->header('Referer');
        error_log("referrer: ".$referrer);
        $referrer_host = parse_url($referrer, PHP_URL_HOST);
        if (empty($referrer) || !($referrer_host === $this->app_host || $referrer_host === "localhost")) {
            return response()->json(['success' => FALSE, 'error' => 'Unauthorized.'], 401)->header('Content-Type', "application/json");
        }

        return $next($request);
    }
}
