<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyHuman
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->recaptcha, 403, trans('auth.recaptcha'));

        $secret_key = env('RECAPTCHA_SECRET_KEY');
        $responseObject = Http::post("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$request->recaptcha}");
        $response = json_decode($responseObject->body());

        abort_unless($response->success, 403, trans('auth.recaptcha'));

        return $next($request);
    }
}
