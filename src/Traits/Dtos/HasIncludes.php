<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait HasIncludes
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
     * return [
     *      'users'       => UserDto::class,
     *      'user_groups' => UserGroup::class,
     * ]
     *
     * @return string[]
     */
    abstract public static function getAvailableIncludes(): array;

    /**
     * Includes come wrapped by Fractal: {"data": [...]}. An empty list gives an empty Collection.
     *
     * @template T of object
     *
     * @param  class-string<T>  $dtoClass
     * @return Collection<int, T>
     */
    protected function collectIncluded(object $included, string $dtoClass): Collection
    {
        $this->throwIfDataFieldOnObjectIsEmpty($included);

        return collect($included->data)->map(fn (object $item) => new $dtoClass($item));
    }

    /**
     * A single item include comes wrapped by Fractal too: {"data": {...}}.
     *
     * @template T of object
     *
     * @param  class-string<T>  $dtoClass
     * @return T
     */
    protected function makeIncluded(object $included, string $dtoClass): object
    {
        $this->throwIfDataFieldOnObjectIsEmpty($included);

        return new $dtoClass($included->data);
    }
}
