<?php

namespace App\Actions\Fortify;

use App\Http\Responses\LoginResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Laravel\Fortify\LoginRateLimiter;

class AuthenticatedApiSession
{
    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new class instance.
     *
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $user = Auth::user();

        $this->limiter->clear($request);

        $token = $user->createToken($user->email)->plainTextToken;
        return Response::json(['access_token' => $token, 'token_type' => 'Bearer', 'expires_in' => 60*60*24], HttpResponse::HTTP_OK);
    }
}
