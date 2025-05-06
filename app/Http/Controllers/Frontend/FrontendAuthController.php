<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FrontendUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class FrontendAuthController extends Controller
{
    protected $providers = [
        'facebook', 'google'
    ];

    public function login()
    {
        return Inertia::render('Auth/Login');
    }

    public function makeLogin(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$fieldType => $request->login]);

        $credentials = $request->only($fieldType, 'password');
        if (Auth::guard('customers')->attempt($credentials)) {
            return redirect()->intended(route('home'))
                ->withSuccess('Signed in');
        }
        return redirect()->back()
            ->withInput()
            ->withErrors([
                'login' => 'These credentials do not match our records.',
            ]);
    }

    public function register()
    {
        return Inertia::render('Auth/Register');
    }

    public function makeRegister(Request $request)
    {
        request()->merge(['phone' =>$request->countryCode.$request->phone ]);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|unique:customers',
            'countryCode' => 'required',
            'password' => 'required|min:8:confirm',
        ]);

        // dd($request->all());
        FrontendUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('home');
    }
    public function makeLogout() {
        Auth::guard('customers')->logout();

        return redirect()->route('home');
    }
    public function otp()
    {
        $countryCode = '94';
        $phoneNumber= '0770435878';

        return Inertia::render('Auth/Otp',['countryCode'=>$countryCode,'phoneNumber'=>$phoneNumber]);
    }

    public function forgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }
    public function resetPassword()
    {
        return Inertia::render('Auth/ResetPassword');
    }

    public function redirectToProvider($driver)
    {
        if (!$this->isProviderAllowed($driver)) {
            Log::error("{$driver} is not currently supported");
            // dd("{$driver} is not currently supported");
        }

        try {
            return Socialite::driver($driver)->redirect();
        } catch (Exception $e) {
            // You should show something simple fail message
            Log::error($e);
            // dd($e->getMessage());
        }
    }

    public function handleProviderCallback($driver)
    {
        try {
            $user = Socialite::driver($driver)->stateless()->user();
        } catch (Exception $e) {
            Log::error($e);
            // dd($e);
            // dd($e->getMessage());
        }

        // check for email in returned user
        return empty($user->email)
            ? Log::error("No email id returned from {$driver} provider.")
            : $this->loginOrCreateAccount($user, $driver);
    }
    protected function loginOrCreateAccount($providerUser, $driver)
    {
        // check for already has account
        $user = FrontendUser::where('email', $providerUser->getEmail())->first();

        // if user already found
        if ($user) {
            // update the avatar and provider that might have changed
            $user->update([
                'avatar' => $providerUser->avatar,
                'provider' => $driver,
                'provider_id' => $providerUser->id,
                'access_token' => $providerUser->token
            ]);
        } else {
            if ($providerUser->getEmail()) { //Check email exists or not. If exists create a new user
                // create a new user
                $user = FrontendUser::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'avatar' => $providerUser->getAvatar(),
                    'provider' => $driver,
                    'provider_id' => $providerUser->getId(),
                    'access_token' => $providerUser->token,
                    // user can use reset password to create a password
                    'password' => ''
                ]);
            } else {
                Log::error('no email address');
                // dd('no email address');
            }
        }

        // login the user
        Auth::guard('customers')->login($user, true);
        return redirect()->intended(route('profile'));
        // dd('user created');
    }
    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}
