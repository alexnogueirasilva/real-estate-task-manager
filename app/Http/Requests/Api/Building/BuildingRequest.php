<?php

namespace App\Http\Requests\Api\Building;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<string>|string>
     */
    public function rules(): array
    {
        return [
            'task_keyword'    => ['nullable', 'string', 'max:255'],
            'status'          => ['nullable', 'string', 'in:open,in_progress,canceled,done'],
            'comment_keyword' => ['nullable', 'string', 'max:255'],
            'comment_user'    => ['nullable', 'string', 'max:255'],
            'created_from'    => ['nullable', 'date'],
            'created_to'      => ['nullable', 'date', 'after_or_equal:created_from'],
        ];
    }
}
