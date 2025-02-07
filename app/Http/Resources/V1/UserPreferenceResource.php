<?php

namespace App\Http\Resources\V1;

use App\Enums\PreferenceForEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'preference_type' => Str::lower(PreferenceForEnum::tryFrom($this->preference_type)?->name),
            'preference_value' => $this->preference_value,
        ];
    }
}
