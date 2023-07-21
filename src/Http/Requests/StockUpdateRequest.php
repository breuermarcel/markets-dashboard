<?php

namespace Breuermarcel\MarketsDashboard\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StockUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "wkn" => "nullable|string|max:25|unique:bm_stocks",
            "isin" => "nullable|string|max:25|unique:bm_stocks",
            "name" => "nullable|string|max:150"
        ];
    }
}
