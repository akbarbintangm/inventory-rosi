<?php

namespace App\Http\Requests\bahanbaku;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class storebahanbakurequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fotobahan'                 => 'image|file|max:2048',
            'namabahan'                 => 'required|string',
            'category_id'               => 'required|string',
            'unit_id'                   => 'required|string',
            'stokbahan'                 => 'required|integer',
            'jenisbahan'                => 'required|string',
            'detailbahan'               => 'nullable|max:1000',
            'tanggalmasuk'              => 'required|date',
            'hargabeli'                 => 'required|numeric',
            'stokperingatan'            => 'nullable|integer',
        ];
    }

    // protected function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'slug' => Str::slug($this->name, '-'),
    //         'code' =>
    //     ]);
    // }
}
