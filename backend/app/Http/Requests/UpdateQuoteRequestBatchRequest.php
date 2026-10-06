<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuoteRequestBatchRequest extends FormRequest
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
        $batchId = (int) $this->route('id');

        return [
            'status' => ['required', 'in:pending,quoted,accepted,rejected'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['nullable', 'array'],
            'items.*.id' => [
                'required',
                'integer',
                Rule::exists('quote_request_items', 'id')->where(
                    fn ($query) => $query->where('quote_request_batch_id', $batchId)
                ),
            ],
            'items.*.quoted_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
