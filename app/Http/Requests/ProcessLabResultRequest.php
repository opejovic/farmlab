<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessLabResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $keyHeldDown = 'not_regex:/(.)\\1{4,}/';

        return [
            'vet_comment' => ['required', 'min:10', 'max:255', $keyHeldDown],
            'vet_indicator' => ['required', 'min:10', 'max:255', $keyHeldDown]
        ];
    }
}
