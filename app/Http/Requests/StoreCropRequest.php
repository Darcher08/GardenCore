<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CropOrigin;
use App\Enums\CropStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCropRequest extends FormRequest
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
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'container_id' => [
                'nullable',
                'integer',
                Rule::exists('containers', 'id')->where('user_id', $this->user()?->id),
            ],
            'variety_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'planted_at' => ['required', 'date', 'before_or_equal:today'],
            'origin' => ['required', Rule::enum(CropOrigin::class)],
            'stage' => ['required', Rule::enum(CropStage::class)],
        ];
    }
}

