<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TicketPaymentRequest extends FormRequest
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
            'passengers.*.name' => 'required|min:2|max:30|string|regex:/^[\pL\s]+$/u',
            'passengers.*.surname' => 'required|min:2|max:30|regex:/^[\pL\s]+$/u',
            'passengers.*.gender' => 'required|in:male,female',
            'checkbox' => 'boolean',
            'passengers.*.citizen_id' => 'digits:11|required_if:checkbox,false|integer',
            'phone_number' => 'required|regex:/^5[0-9]{9}$/',
            'email' => 'nullable|email|max:50',
            'invoice.name' => 'nullable|min:2|max:30|regex:/^[\pL\s]+$/u',
            'invoice.citizen_id' => ' nullable|digits:11|integer',
            'invoice.corporate_name' => 'nullable|min:2|max:50',
            'invoice.tax_number' => 'nullable|min:2|max:24',
            'invoice.tax_office' => 'nullable|min:2|max:50',
            'payment.pan' => 'required|integer|digits:16',
            'payment.Ecom_Payment_Card_ExpDate_Month' => 'required|numeric|between:01,12',
            'payment.Ecom_Payment_Card_ExpDate_Year' => 'required|numeric|between:0,99',
            'payment.cvv' => 'required|numeric|digits:3',
        ];

    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $currentMonth = (int) date('m');
            $currentYear = (int) date('y');

            $expiryMonth = (int) $this->input('payment.expiry_month');
            $expiryYear = (int) $this->input( 'payment.expiry_year');

            if ($expiryYear < $currentYear || ($expiryYear == $currentYear && $expiryMonth < $currentMonth)) {
                $validator->errors()->add('expiry_date', 'Kredi kartı son kullanım tarihi geçersiz.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'passengers.*.name.required' => 'Yolcuların isim alanı zorunludur',
            'passengers.*.name.min' => 'Yolcuların isim alanı minumum 2 karakterden olması zorunludur',
            'passengers.*.name.max' => 'Yolcuların isim alanı maximum 30 karakterden olması zorunludur',
            'passengers.*.name.string' => 'Yolcuların isim alanı harflerden oluşması zorunludur',
            'passengers.*.name.regex' => 'Yolcuların isim alanı özel karakterler içeremez',
            'passengers.*.surname.required' => 'Yolcuların soyisim alanı zorunludur',
            'passengers.*.surname.min' => 'Yolcuların soyisim alanı minumum 2 karakterden olması zorunludur',
            'passengers.*.surname.max' => 'Yolcuların soyisim alanı maximum 30 karakterden olması zorunludur',
            'passengers.*.surname.string' => 'Yolcuların soyisim alanı harflerden oluşması zorunludur',
            'passengers.*.surname.regex' => 'Yolcuların soyisim alanı özel karakterler içeremez',
            'passengers.*.gender.required' => 'Yolcuların cinsiyet alanı zorunludur',
            'passengers.*.gender.*.required' => 'Yolcuların cinsiyet alanı zorunludur',
            'passengers.*.gender.*.in' => 'Yolcuların cinsiyet alanı Bay veya Bayan olması zorunludur',
            'passengers.*.citizen_id.required_if' => 'Yolcuların TC kimlik alanı zorunludur',
            'passengers.*.citizen_id.digits' => 'Yolcuların TC kimlik alanı 11 haneli sayıdan oluşması zorunludur',
            'passengers.*.citizen_id.integer' => 'Yolcuların TC kimlik alanı sayılardan olması zorunludur',
            'phone_number.required' => 'Telefon numarası alanı zorunludur',
            'phone_number.regex' => 'Telefon numarası alanı 5 ile başlaması ve 10 sayıdan olması zorunludur',
            'email.email' => ',E-posta adresi alanı E-posta Adresine göre olması zorunludur',
            'email.max' => 'Email adresi alanı maximum 50 karakterden olması zorunludur',
            'invoice.name.min' => 'Bireysel fatura isim soyisim alanı minumum 2 karakterden olması zorunludur',
            'invoice.name.max' => 'Bireysel fatura isim soyisim alanı 50 karakterden olması zorunludur',
            'invoice.name.regex' => 'Bireysel fatura isim soyisim alanı harflerden olması zorunludur',
            'invoice.citizen_id.integer' => 'Bireysel fatura TC kimlik alanı sayılardan olması zorunludur',
            'invoice.citizen_id.min' => 'Bireysel fatura TC kimlik alanı minumum 11 sayılardan olması zorunludur',
            'invoice.citizen_id.max' => 'Bireysel fatura TC kimlik alanı 11 sayılardan olması zorunludur',
            'invoice.corporate_name.min' => 'Kurumsal fatura firma adı alanı minumum 2 karakterden olması zorunludur',
            'invoice.corporate_name.max' => 'Kurumsal fatura firma adi alanı 50 karakterden olması zorunludur',
            'invoice.tax_number.min' => 'Kurumsal fatura vergi numarası alanı minumum 2 karakterden olması zorunludur',
            'invoice.tax_number.max' => 'Kurumsal fatura vergi numarası alanı 24 karakterden olması zorunludur',
            'invoice.tax_office.min' => 'Kurumsal fatura vergi dairesi alanı minumum 2 karakterden olması zorunludur',
            'invoice.tax_office.max' => 'Kurumsal fatura vergi dairesi alanı 50 karakterden olması zorunludur',
            'payment.pan.required' => 'Kredi kartı numara alanı zorunludur.',
            'payment.pan.digits' => 'Kredi kartı numara alanı 16 sayıdan oluşması zorunludur.',
            'payment.pan.integer' => 'Kredi kartı numara alanı sayılardan oluşması zorunludur.',
            'payment.Ecom_Payment_Card_ExpDate_Month.required' => 'Ay alanı zorunludur.',
            'payment.Ecom_Payment_Card_ExpDate_Month.integer' => 'Ay, sayısal bir değer olmalıdır.',
            'payment.Ecom_Payment_Card_ExpDate_Month.min' => 'Ay 1 ile 12 arasında olmalıdır.',
            'payment.Ecom_Payment_Card_ExpDate_Month.max' => 'Ay 1 ile 12 arasında olmalıdır.',
            'payment.Ecom_Payment_Card_ExpDate_Year.required' => 'Yıl alanı zorunludur.',
            'payment.Ecom_Payment_Card_ExpDate_Year.integer' => 'Yıl sayısal bir değer olmalıdır.',
            'payment.Ecom_Payment_Card_ExpDate_Year.min' => 'Yıl, şu anki yıldan küçük olamaz.',
            'payment.cvv.digits' => 'Güvenlik kodu alanı maximum 3 sayıdan olmalıdır.',
            'payment.cvv.required' => 'Güvenlik kodu alanı zorunludur.',
            'payment.cvv.integer' => 'Güvenlik kodu sayısal bir değer olmalıdır.',
        ];
    }
}
