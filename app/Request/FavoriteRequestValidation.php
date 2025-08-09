<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class FavoriteRequestValidation extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|int',
            'product_id' => 'required|int',

        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'client_id' => 'invalid value for client_id.',
            'product_id' => 'invalid value for product_id.',
        ]);
    }
}
