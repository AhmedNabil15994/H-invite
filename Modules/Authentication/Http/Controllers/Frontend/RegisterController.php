<?php

namespace Modules\Authentication\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\Country;
use PragmaRX\Countries\Package\Countries;
use Modules\Authentication\Mail\WelcomeMail;
use Modules\Authentication\Foundation\Authentication;
use Modules\Authentication\Http\Requests\Frontend\RegisterRequest;
use Modules\Authentication\Notifications\Frontend\WelcomeNotification;
use Modules\Authentication\Repositories\Frontend\AuthenticationRepository as AuthenticationRepo;
use Modules\Cart\Traits\CartTrait;

class RegisterController extends Controller
{
    use Authentication, CartTrait;

    protected $auth;

    public function __construct(AuthenticationRepo $auth)
    {
        $this->auth = $auth;
    }

    public function show(Request $request)
    {
        return view('authentication::frontend.register', compact('request'));
    }

    public function register(RegisterRequest $request)
    {
        $registered = $this->auth->register($request->validated());
        if ($registered) {
            $redirectRoute = '';
            $this->loginAfterRegister($request);
//            auth()->user()->notify(new WelcomeNotification);
            $token = request()->has('user_token') ? request()->get('user_token') : session()->get('user_token');
            if($token){
                $redirectRoute = 'frontend.cart.index';
                $this->updateCartKey($token,auth()->id());
                session()->forget(['user_token']);
            }
            return $redirectRoute ? redirect()->to(route($redirectRoute)) : $this->redirectTo($request);
        } else {
            return redirect()->back()->with(['errors' => 'try again']);
        }
    }

    public function redirectTo($request)
    {
        return redirect()->route('frontend.home');
    }

    public function countries()
    {
        $countries = Country::pluck('title', 'id');

        return $countries;
    }

}
