<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    function UserRegistration(Request $request)
    {
        try {
            $email = $request->input('email');
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $password = $request->input('password');

            User::create([
                'email' => $email,
                'name' => $name,
                'mobile' => $mobile,
                'password' => ($password)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Registered Successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }
    function UserLogin(Request $request)
    {
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->select('id')->first();

        if ($count !== null) {
            $token = JWTToken::CreateToken($request->input('email'), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Logged In Successfully',
                'token' => $token
            ], 200)->cookie('token', $token, 60 * 24 * 30);

        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid Credentials'

            ], 401);
        }
    }

    public function UserLogout(Request $request)
    {
        // Create a response instance
        $response = response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully'
        ], 200)->withCookie(cookie('token', '', -1));

        /*   // If it's a web request, redirect instead of returning JSON
          if (!$request->expectsJson()) {
              return redirect('/')->withCookies([$response->headers->getCookies()[0]]);
          } */

        return $response;
    }

    public function SendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = mt_rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();
        if ($count == 1) {
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', '=', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => "($otp)Code Sent Successfully",
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Not Found'
            ]);
        }


    }

    function VerifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();
        if ($count == 1) {
            User::where('email', '=', $email)->update(['otp' => $otp ]);
            $token = JWTToken::CreateTokenForPasswordReset($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verified Successfully',
                'token' => $token

            ],200)->cookie('token', $token, 60 * 24 * 30);

        }
        else{
            return response()->json([
            'status' => 'failed',
            'message' => 'Invalid OTP'
            ]);
        }
    }
    function ResetPassword(Request $request){
        try{
        $email = $request->input('email');
        $password = $request->input('password');
        User::where('email', '=', $email)->update(['password' => $password]);
        return response()->json([
            'status' => 'success',
            'message' => 'Password Reset Successfully'

        ]);
    }
    catch (Exception $e) {
        return response()->json([
            'status' => 'failed',
            'message' => 'something went wrong'

        ]);
    }

    }

    function UserProfile(Request $request){
        $email = $request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data'=>$user

        ],200);
    }
    function UpdateUserProfile(Request $request){
        try{
        $email = $request->header('email');
        $name = $request->input('name');
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        User::where('email','=',$email)->update(
            ['name'=>$name,
            'mobile'=>$mobile,'password'=>$password]);
        return response()->json([
            'status' => 'success',
            'message' => 'Profile Updated Successfully'

        ],200);}
        catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'something went wrong'

            ]);

        }

    }



}
