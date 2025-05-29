<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' =>  'required|min:3|max:52|string',
            'email' =>  'required|min:3|max:52|email|unique:users',
            'password' =>  'required|min:3|max:52|confirmed',
        ];
    }

    public function messages(): array{
        return [
            'name.required' => 'İsim Soyisim Alanı Giriniz.',
            'name.min' => 'İsim Soyisim Alanı Min 3 Giriniz.',
            'name.max' => 'İsim Soyisim Alanı Max 52 Giriniz.',
            'name.string' => 'İsim Soyisim Alanı Sayısal İfadeler İçeremez.',
            'email.required' => 'Email Alanı Giriniz.',
            'email.min' => 'Email Alanı Min 3 Giriniz.',
            'email.max' => 'Email Alanı Max 52 Giriniz.',
            'email.email' => 'Email Alanı Doğru Bir Şekilde Giriniz.',
            'email.unique' => 'Email Zaten Kullanımda',
            'password.required' => 'Şifre Alanı Giriniz.',
            'password.min' => 'Şifre Alanı Min 3 Giriniz.',
            'password.max' => 'Şifre Alanı Max 52 Giriniz.',
            'password.password' => 'Şifre Alanı Giriniz.',
            'password.confirmed' => 'Şifre Tekrar Alanını Giriniz.',
        ];
    }
}
