<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
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
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // See: https://stackoverflow.com/questions/31273673/lumen-how-can-i-get-url-parameters-from-middleware
        $secret = $request->route()[2]['secret'];
        error_log("secret: ".$secret);
        if (empty($secret) || $secret != env("ADMIN_SECRET", "secret")) {
            return response()->json(['success' => FALSE, 'error' => 'Unauthorized.'], 401)->header('Content-Type', "application/json");
        }

        return $next($request);
    }
}
