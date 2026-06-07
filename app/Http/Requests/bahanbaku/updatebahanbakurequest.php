<?php

namespace App\Http\Requests\bahanbaku;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class updatebahanbakurequest extends FormRequest
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
            'category_id'               => 'required|integer',
            'unit_id'                   => 'required|integer',
            'stokbahan'                 => 'required|integer',
            'jenisbahan'                => 'required|integer',
            'detailbahan'               => 'nullable|max:1000',
            'tanggalmasuk'              => 'required|integer',
            'hargabeli'                 => 'required|numeric',
            'stokperingatan'            => 'nullable|integer',
        ];
    }

}
