<?php

namespace App\Http\Controllers\Api\V2;

use App\Notifications\AppEmailVerificationNotification;
use Illuminate\Http\Request;
use App\User;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Illuminate\Support\Str;
use App\Http\Controllers\OTPVerificationController;

use Hash;

class PasswordResetController extends Controller
{
    public function forgetRequest(Request $request)
    {
        if ($request->send_code_by == 'email') {
            $user = User::where('email', $request->email_or_phone)->first();
        } else {
            $user = User::where('phone', $request->email_or_phone)->first();
        }


        if (!$user) {
            return response()->json([
                'result' => false,
                'status_code'=>0,
                'message' => trans('messages.api.user_not_found')], 200);
        }

        if ($user) {
            $user->verification_code = rand(100000, 999999);
            $user->save();
            if ($request->send_code_by == 'phone') {

                $otpController = new OTPVerificationController();
                $otpController->send_code($user);
            } else {
                $user->notify(new AppEmailVerificationNotification());
            }
        }

        return response()->json([
            'result' => true,
             'status_code'=>1,
            'message' => trans('messages.api.code_sent')
        ], 200);
    }

    public function confirmReset(Request $request)
    {
        $user = User::where('verification_code', $request->verification_code)->first();

        if ($user != null) {
            $user->verification_code = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'result' => true,
                'message' => trans('messages.api.pwd_reset'),
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.user_found'),
            ], 200);
        }
    }

    public function resendCode(Request $request)
    {

        if ($request->verify_by == 'email') {
            $user = User::where('email', $request->email_or_phone)->first();
        } else {
            $user = User::where('phone', $request->email_or_phone)->first();
        }


        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => trans('messages.api.user_not_found')], 404);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

        if ($request->verify_by == 'phone') {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        } else {
            $user->notify(new AppEmailVerificationNotification());
        }



        return response()->json([
            'result' => true,
            'message' => trans('messages.api.code_sent_again'),
        ], 200);
    }
}
