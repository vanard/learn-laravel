<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'home_admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function redirectToProvider($oauth)
    // {
    //     return Socialite::driver($oauth)->redirect();
    // }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
      $userSocial = Socialite::driver('facebook')->user();

      //return $userSocial->name;

      $findUser = User::where('email', $userSocial->email)->first();

      if ($findUser) {
        Auth::login($findUser);

        return redirect('home_user');
      }else{
        $user = new User;

        $user->name = $userSocial->name;
        $user->email = $userSocial->email;
        $user->password = Hash::make(123098);
        $user->level = 'user';

        $user->save();

        Auth::login($user);

        return redirect('home_user');
      }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
      $userSocial = Socialite::driver('google')->user();

      $findUser = User::where('email', $userSocial->email)->first();

      if ($findUser) {
        Auth::login($findUser);

        return redirect('home_user');
      }else{
        $user = new User;

        $user->name = $userSocial->name;
        $user->email = $userSocial->email;
        $user->password = Hash::make(123098);
        $user->level = 'user';

        $user->save();

        Auth::login($user);

        return redirect('home_user');
      }
    }
}
