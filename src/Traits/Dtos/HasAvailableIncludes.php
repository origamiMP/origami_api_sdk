<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use Illuminate\Support\Arr;

trait HasAvailableIncludes
{
    public static function isIncludeAvailable(string $include): bool
    {
        $remainingShards = explode('.', $include);
        $currentShard = Arr::pull($remainingShards, 0);

        $nextIncludeDto = Arr::get(self::getAvailableIncludes(), $currentShard);

        if (is_null($nextIncludeDto)) {
            return false;
        }

        if (count($remainingShards) === 0) {
            return true;
        }

        return method_exists($nextIncludeDto, 'isIncludeAvailable')
            && $nextIncludeDto::isIncludeAvailable(implode('.', $remainingShards));
    }

    /**
     * Available includes on this specific DTO. They should be declared this way :
     * protected static array $availableIncludes = [
     *      'users'       => UserDto::class,
     *      'user_groups' => UserGroup::class,
     * ]
     *
     * @return string[]
     */
    public static function getAvailableIncludes(): array
    {
        return self::$availableIncludes ?? [];
    }
}
