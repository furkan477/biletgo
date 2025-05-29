<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class FlightSearchRequest extends FormRequest
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
            'origin' => 'required|min:3|max:4|string',
            'destination' => 'required|min:3|max:4|string',
            'departure_date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(354)->toDateString(),
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'passengers.*' => 'nullable|integer|min:0|max:7',
        ];
    }

    public function messages(): array
    {
        return [
            'origin.required' => 'Nereden Alanı Boş Bırakılamaz',
            'origin.min' => 'Nereden minumum 3 karakterden oluşuyor',
            'origin.max' => 'Nereden Alanı maximum 4 karakterden oluşuyor',
            'origin.string' => 'Nereden Alanı Özel ve Sayısal Karakterler içermez',
            'destination.required' => 'Nereye Alanı Boş Bırakılamaz',
            'destination.min' => 'Nereye Alanı minumum 3 karakterden oluşuyor',
            'destination.max' => 'Nereye Alanı maximum 4 karakterden oluşuyor',
            'destination.string' => 'Nereye Alanı Özel ve Sayısal Karakterler içermez',
            'departure_date.required' => 'Gidiş Tarihi Alanı Boş Bırakılamaz',
            'departure_date.date' => 'Gidiş Tarihden oluşuyor, ',
            'departure_date.after_or_equal' => 'Gidiş Tarihi en az bugün nün tarihini alabiliyor',
            'departure_date.before_or_equal' => 'Gidiş Tarihi 355 gün sonrasına hizmet veremiyor , daha erken tarih seçiniz',

            'passengers.*.min'=> 'En az 0 yolcu olabilir',
            'passengers.*.max'=> 'En Fazla 7 yolcu olabilir',

            'return_date.after_or_equal'=> 'Dönüş Tarihi Gidiş Tarihinden Küçük Olamaz.',
            'return_date.date'=> 'Dönüş Gidiş Tarihden oluşuyor.',
        ];
    }

    public function withValidator($validator){
        $validator->after(function ($validator) {
           
            $passengers = $this->input('passengers',[]);

            $adults = $passengers['ADT'] ?? 0;
            $student = $passengers['STU'] ?? 0;
            $infant = $passengers['INF'] ?? 0;
            $old = $passengers['YCD'] ?? 0;
            $children = $passenger['CHD'] ?? 0;

            $total = array_sum($passengers);

            if($infant > $adults || $total > 7){
                $validator->errors()->add('passengers', 'Bebek Yolcu Yetişkin Yolcu Sayısından Fazla Olamaz , Bebek Yolcu Hariç Toplam 7 Yolcu Olmalıdır.');                
            }

            if($children > 0 && $adults == 0){
                $validator->errors()->add('passengers', 'Bebek Yolcu Yetişkin Yolcu Sayısından Fazla Olamaz , Bebek Yolcu Hariç Toplam 7 Yolcu Olmalıdır.');                
            } 
        });
    }
}
