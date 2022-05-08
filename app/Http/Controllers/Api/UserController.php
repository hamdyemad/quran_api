<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use Res;
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => "required"
        ], [
            'email.required' => 'البريد الألكترونى مطلوب',
            'email.email' => 'البريد الألكترونى يجب أن يكون من صيغة الميل',
            'email.exists' => 'البريد الألكترونى غير موجود',
        ]);
        if($validator->fails()) {
            return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
        }
        $user = User::where('email', $request->email)->first();
        $token = auth()->login($user);
        return $this->respondWithToken($token, true, 'تم تسجيل الدخول بنجاح', $user);
        return $this->sendRes('تم اضافة الصورة بنجاح', true);
    }

    public function logout(Request $request) {
        auth()->logout();
        return $this->sendRes('تم تسجيل الخروج بنجاح', true);
    }


}
