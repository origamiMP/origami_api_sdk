<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use Carbon\Carbon;

trait HasTimestamps
{
    public Carbon $createdAt;

    public Carbon $updatedAt;

    protected function getTimestampsAsDataStructureToProperties(): array
    {
        return [
            'created_at' => fn ($createdAt) => $this->createdAt = Carbon::parse($createdAt),
            'updated_at' => fn ($updatedAt) => $this->updatedAt = Carbon::parse($updatedAt),
        ];
    }

    protected function getTimestampsValidationRules(): array
    {
        return [
            'created_at' => ['required', 'date'],
            'updated_at' => ['required', 'date'],
        ];
    }
}
