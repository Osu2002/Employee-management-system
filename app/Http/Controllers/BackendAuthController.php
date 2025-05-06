<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BackendAuthController extends Controller
{
    public function showProfile() {
        return Inertia::render('Profile/Profile');
    }

    public function twoStepRequest()
    {
        return Inertia::render('Auth/OtpVerification');
    }

    public function activateProfileView()
    {
        return Inertia::render('Auth/ActivateProfile');
    }

    public function uploadProfilePhoto(Request $request) {

        $request ->validate([
            'image' => ['nullable', 'mimes:jpeg,jpg,png, webp', 'max:10000'],
        ]);
        $user = User::find(auth()->user()->id);
        try {
            if($request->hasFile('image')) {
               $file = $user->addMedia($request->file('image'))->toMediaCollection('avatar');
               $user->profile_photo_path = str_replace(config('app.url'), '',$file->getUrl());
               $user->save();
            }
            return redirect()->route('profile');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function updateUserInfo(Request $request)
    {
        $user = auth()->user();
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('backend_users')->ignore($user->id)],
            'mobile_no' => ['nullable', 'max:15', Rule::unique('backend_users')->ignore($user->id)],
        ])->validateWithBag('updateProfileInformation');

        if (
            $request['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $request);
        } else {
            $user->forceFill([
                'name' => $request['name'],
                'email' => $request['email'],
            ])->save();
        }

        return back();
    }

    public function changePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $this->validatePassword($request, $user, 'updatePassword');


        $request->validate([
            'password' => ['required', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $user->forceFill([
            'password' => Hash::make($request['password']),
        ])->save();

        return redirect()->route('profile');
    }

    public function activateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $this->validatePassword($request, $user, 'activateAccount');

        $user->status = 1;
        $user->save();
        return redirect()->route('profile');
    }

    public function disableProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $this->validatePassword($request, $user, 'deactivateProfile');

        $user->status = 0;
        $user->save();
        return redirect()->route('profile');
    }

    public function deleteProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $this->validatePassword($request, $user, 'deleteProfile');

        Auth::guard('web')->logout();
        if ($user->delete()) {
            return redirect()->route('profile');
        }
    }

    public function validatePassword(Request $request, $user, $errorBag)
    {
        Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
        ])->after(function ($validator) use ($request, $user) {
            if (!isset($request['current_password']) || !Hash::check($request['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag($errorBag);
    }

    public function generate2FCode()
    {
        $code = rand(10000, 99999);

        $user = User::find(Auth::user()->id);
        $user->two_factor_code = $code;
        $user->save();

        try {

            $details = [
                'title' => 'Mail from ItSolutionStuff.com',
                'code' => $code
            ];

            Mail::to(auth()->user()->email)->send(new SendCodeMail($details));
        } catch (Exception $e) {
            info("Error: " . $e->getMessage());
        }
    }
}
