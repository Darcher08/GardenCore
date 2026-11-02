<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CropLogType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCropLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'log_type' => ['required', Rule::enum(CropLogType::class)],
            'recorded_at' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'metrics' => ['nullable', 'array'],
        ];
    }
}

