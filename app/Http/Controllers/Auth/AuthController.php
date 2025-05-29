<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginShow(){
        return view('auth.login');
    }
    public function registerShow(){
        return view('auth.register');
    }

    public function register(UserRegisterRequest $request){

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if($user){
            return redirect()->route('login.show')->withSuccess('Üyeliğiniz Başarıyla Oluşturuldu Lütfen Giriş Yaparak Devam Ediniz.');
        }else{
            return redirect()->back()->withErrors('Üyeliğiniz Oluşturulamadı Lütfen Tekrar Deneyiniz.');
        }

    }
}
