<?php

namespace Modules\Authentication\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Authentication\Http\Requests\Api\ResendCodeRequest;
use Modules\Authentication\Http\Requests\Api\VerifiedCodeRequest;
use Modules\Authentication\Repositories\Api\AuthenticationRepository;
use Modules\User\Transformers\Api\UserResource;
use Modules\Apps\Http\Controllers\Api\ApiController;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\Api\LoginRequest;

class LoginController extends ApiController
{
    use Authentication;

    protected $user;

    public function __construct(AuthenticationRepository $user)
    {
        $this->user = $user;
    }

    public function postLogin(LoginRequest $request)
    {
        $failedAuth = $this->login($request);

        if ($failedAuth)
            return $this->invalidData($failedAuth, [], 422);

        return $this->tokenResponse($request);
    }

    public function tokenResponse($request,$user = null)
    {
        $user = $user ?? auth()->user();
        $token = $this->generateToken($request,$user);
        return $this->response([
            'access_token' => $token->plainTextToken,
            'user' => new UserResource($user),
            'token_type' => 'Bearer',
            'expires_at' => date('Y-m-d',strtotime('+2 months'))
        ]);
    }

    public function logout(Request $request)
    {
        $this->tokenExpiresAt();
        return $this->response([], __('authentication::api.logout.messages.success'));
    }

}
