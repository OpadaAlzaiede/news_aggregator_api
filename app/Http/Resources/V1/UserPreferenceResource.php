<?php

namespace App\Http\Resources\V1;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\PreferenceForEnum;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'preference_value' => $this->preference_value
        ];
    }
}
