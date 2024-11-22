<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendMail;
use App\Mail\ForgotPasswordEmail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends UserController
{
    public function waitingForEmailVerification(Request $request)
    {
        if (!Auth::user()->token_verify_email)
            $this->sendVerifyEmail();
        // dd(User::find(Auth::id())->token_verify_email,Auth::user()->token_verify_email);
        $urlForDev = url('/verify-email?email=' . Auth::user()->email . '&token=' . Auth::user()->token_verify_email);
        return view('pages.waitVerifyMail', compact('urlForDev'));
    }
    public function resendVerificationEmail()
    {
        if (Auth::user()->email_verified_at)
            return redirect()->route('home');
        $this->sendVerifyEmail();
        return redirect()->route('waitingForEmailVerification')->with('message', __('public.Resend success'));
    }
    public function sendVerifyEmail()
    {
        $tokenRandom = Str::random(30);
        $url = url('/verify-email?email=' . Auth::user()->email . '&token=' . $tokenRandom);
        $data = [
            'user' => User::where([['email', Auth::user()->email]])->first(),
            'verificationUrl' => $url
        ];
        SendMail::dispatch(
            'verify email',
            Auth::user()->email,
            $data
        );
        DB::table('users')
            ->where('email', Auth::user()->email)
            ->update([
                'token_verify_email' => $tokenRandom
            ]);
        Auth::user()->token_verify_email = $tokenRandom;
        return $url;
    }

    public function verifyEmail(Request $request)
    {

        // dd($request->all(),Auth::user()->token_verify_email==$request->token);
        if (Auth::user()->token_verify_email == $request->token) {
            DB::table('users')
                ->where('email', Auth::user()->email)
                ->update([
                    'email_verified_at' => now()
                ]);
            return redirect()->route('home')->with('message', __("public.Verify success"));
        }
        return redirect()->route('login')->with('message', __("public.Verify fail"));
    }
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function loginWithGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $findUser = User::where([['email', $user->email]])->first();
            // dd($findUser);
            if ($findUser) {
                if ($findUser->google_id != $user->id)
                    return redirect()->route('login')->with('message', __("public.Email has been used"));
                if (Auth::loginUsingId($findUser->id))
                    return redirect()->route('home');
                else
                    return redirect()->route('login')->with('message', 'Has account but can not login');
            } else {
                $newUser = new User();
                $result = $this->useTransaction(function () use ($user, &$newUser) {
                    $newUser = User::create([
                        "email" => $user->email,
                        'name' => $user->name,
                        'google_id' => $user->id,
                        'avatar_filename' => $user->avatar,
                        'password' => Str::random(20),
                        'email_verified_at' => now()
                    ]);
                });
                if ($result && Auth::loginUsingId($newUser->id))
                    return redirect()->route('home');
                else
                    return redirect()->route('login')->with('message', 'Created account but can not login');
            }
        } catch (Exception $e) {
            // throw new Exception($e);
            return redirect()->route('login')->with('message', $e->getMessage());
        };
    }
    public function getLogin()
    {
        return view("auth.login");
    }
    public function postLogin(LoginRequest $request)
    {

        if (Auth::attempt(["email" => $request->email, 'password' => $request->password])) {
            Auth::logoutOtherDevices($request->password);
            return redirect()->route('home');
        }
        return redirect()->back()->withErrors(['incorrect' => __('Email or Password incorrect')])->withInput();
    }
    public function getRegister(Request $request)
    {
        return view("auth.register");
    }
    public function postRegister(RegisterRequest $request)
    {
        //create user
        $result = $this->createUser($request);
        //login
        if ($result && Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            //verify mail
            $this->sendVerifyEmail();
            return redirect()->route('waitingForEmailVerification');
        }
        return redirect()->route('register')->with('message', __('Create failed'));
    }
    public function logout(Request $request)
    {
        //forget session
        Cache::forget('user-is-online-' . Auth::user()->id);
        session(null)->flush();
        //logout
        Auth::logout();
        $request->session()->invalidate(); //xóa dữ liêu người dùng cũ
        $request->session()->regenerateToken(); //tạo token mới
        return redirect()->route('login');
    }
    public function forgotPassword()
    {
        return view('auth.forgotPassword');
    }
    public function postForgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required'],
        ]);
        if (!User::where([['email', $request->email]])->exists())
            return redirect()->back()->withErrors(['email' => __('public.Email is not exist')]);
        else {
            $tokenRandom = Str::random(30);
            $urlResetPassword = url('/reset-password?email=' . $request->email . '&token=' . $tokenRandom);
            $record = DB::table('password_reset_tokens')->where([['email', $request->email]])->first();
            $result = $this->useTransaction(function () use ($request, $tokenRandom, $record) {
                if ($record)
                    DB::table('password_reset_tokens')
                        ->where('email', $request->email)
                        ->update([
                            'token' => $tokenRandom,
                            'created_at' => now(),
                        ]);
                else
                    DB::table('password_reset_tokens')->insert([
                        'email' => $request->email,
                        'token' => $tokenRandom,
                        'created_at' => now(),
                    ]);
            });
            $data = [
                'user' => User::where([['email', $request->email]])->first(),
                'url' => $urlResetPassword
            ];
            SendMail::dispatch(
                'forgot password',
                $request->email,
                $data
            );

            if ($result)
                return redirect()->back()
                    ->with('message', __('public.Check mail to reset password'))
                    ->with('urlResetPassword', $urlResetPassword);
            else
                return redirect()->back()->with('message', 'Has error');
        };
    }
    public function resetPassword(Request $request)
    {
        if (!$request->email || !$request->token) abort(404);
        $passReset = DB::table('password_reset_tokens')->where([['email', $request->email], ['token', $request->token]])->first();
        if (!$passReset)
            abort(404);
        if (!Carbon::parse($passReset->created_at)->addMinutes(60)->isFuture())
            return redirect()->back()->with('message', __('public.Verify mail fail'));
        return view('auth.resetPassword', ['email' => $request->email, 'token' => $request->token]);
    }
    public function postResetPassword(Request $request)
    {
        // dd($request->input('email') , $request->token);
        if (!$request->email || !$request->token) return redirect()->back();
        $request->validate([
            'password' => [
                'required',
                'min:8',
                Password::min(8)
                    ->letters()
                    // ->mixedCase()
                    ->numbers()
                    ->symbols()
                // ->uncompromised()
            ],
            'confirm' => ['required', 'same:password'],
        ]);
        $passReset = DB::table('password_reset_tokens')->where([['email', $request->email], ['token', $request->token]])->first();
        if (!$passReset)
            abort(404);
        $result = $this->useTransaction(function () use ($request, $passReset) {
            DB::table('password_reset_tokens')->where([['email', $request->email], ['token', $request->token]])->delete();
            User::where([['email', $request->email]])->first()->update([
                'password' => $request->password
            ]);
        });
        if ($result)
            return redirect()->route('login')->with('email', $request->email)->with('password', $request->password)->with('message', __('public.Saved password'));
        else
            return redirect()->back()->with('message', 'Has error');
    }
    function test()
    {
        $controllerA = app()->make('App\Http\Controllers\GroupChatController');
        return $controllerA->methodA();
    }
}
