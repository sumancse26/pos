<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    function UserRegistration(Request $req)
    {
        try {
            User::create([
                'firstName' => $req->input('firstName'),
                'lastName' => $req->input('lastName'),
                'email' => $req->input('email'),
                'mobile' => $req->input('mobile'),
                'password' => $req->input('password')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Created Successful'
            ]);
        } catch (\Exception $ex) {
            Log::error('Registration error: ' . $ex->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration Failed'
            ]);
        }
    }

    function UserLogin(Request $req)
    {
        try {
            $count = User::where('email', $req->input('email'))
                ->where('password', $req->input('password'))
                ->select('id')->first();

            if ($count === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized user'
                ], 401);
            }

            $token = JWTToken::CreateToken($req->input('email'), $count->id);

            return response()->json([
                'success' => true,
                'message' => 'Login Successful',
                'token' => $token
            ])->cookie('token', $token, 7 * 60 * 24 * 30, '/');
        } catch (\Exception $ex) {

            return response()->json([
                'success' => false,
                'message' => 'Login Failed'
            ]);
        }
    }

    function SendOTPCode(Request $req)
    {
        try {
            $email = $req->input('email');
            $otp = rand(1000, 9999);

            $count = User::where('email', $email)->count();

            if ($count != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized user'
                ], 401);
            }

            Mail::to($email)->send(new OTPMail($otp));
            $token = JWTToken::ResetPasswordToken($email);
            User::where('email', $email)->update(['otp' => $otp]);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email'
            ])->cookie('token', $token, 60 * 24 * 30, '/');
        } catch (\Exception $ex) {

            return response()->json([
                'success' => false,
                'message' => 'OTP sending Failed'
            ]);
        }
    }
    function verifyOTP(Request $req)
    {
        try {
            $email = $req->header('email');
            $otp = $req->input('otp');

            $count = User::where('email', $email)
                ->where('otp', $otp)->count();

            if ($count != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized user'
                ], 401);
            }


            User::where('email', $email)->update(['otp' => 0]);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified'
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'OTP Verification Failed'
            ]);
        }
    }

    public function resetPassword(Request $req)
    {
        try {
            $email = $req->header('email');
            $password = $req->input('password');

            User::where('email', $email)->update(['password' => $password]);
            return response()->json([
                'success' => true,
                'email' => $email,
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Password change failed'
            ]);
        }
    }

    public function getUserProfile(Request $req)
    {
        try {
            $email = $req->header('email');
            $id = $req->header('id');
            $user = User::where('email', $email)
                ->where('id', $id)
                ->first();
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user'
            ], 401);
        }
    }

    #update profile

    public function updateProfile(Request $req)
    {
        try {
            $email = $req->header('email');
            $id = $req->header('id');
            $user = User::where('email', $email)
                ->where('id', $id)
                ->update([
                    'firstName' => $req->input('firstName'),
                    'lastName' => $req->input('lastName'),
                    'email' => $email,
                    'mobile' => $req->input('mobile'),
                    'password' => $req->input('password')
                ]);
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed'
            ]);
        }
    }

    public function logoutPage(Request $req)
    {
        return redirect('/login')->cookie('token', '', -1, '/');
    }



    #function for page route
    public function loginPage()
    {
        return view('pages.auth.login-page');
    }
    public function registrationPage()
    {
        return view('pages.auth.registration-page');
    }
    public function resetPasswordPage()
    {
        return view('pages.auth.reset-pass-page');
    }
    public function sendOtpPage()
    {
        return view('pages.auth.send-otp-page');
    }
    public function verifyOtpPage()
    {
        return view('pages.auth.verify-otp-page');
    }

    public function profilePage()
    {
        return view('pages.dashboard.profile-page');
    }
}
