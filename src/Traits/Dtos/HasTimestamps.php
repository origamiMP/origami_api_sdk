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
            'created_at' => fn (string $createdAt) => $this->createdAt = Carbon::parse($createdAt),
            'updated_at' => fn (string $updatedAt) => $this->updatedAt = Carbon::parse($updatedAt),
        ];
    }
}
