<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\Dtos;

use Illuminate\Support\Collection;

trait HasIncludedCollections
{
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
}
