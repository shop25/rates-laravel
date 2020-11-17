<?php

namespace S25\RatesApiLaravel\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RateChangeRequest extends FormRequest
{
    public function getChanges(): array
    {
        return $this->input('changes');
    }

    public function rules(): array
    {
        return [
            'changes' => 'array|min:1|required',
            'changes.*.prev' => 'string|size:3|required',
            'changes.*.next' => 'string|size:3|required',
            'changes.*.direct' => 'numeric|gt:0|required',
            'changes.*.inverse' => 'numeric|gt:0|required',
            'changes.*.date' => 'date_format:Y-m-d H:i:s|required',
        ];
    }
}