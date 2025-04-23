<?php

namespace OrigamiMp\OrigamiApiSdk\Helpers\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Obj
{
    /**
     * This method was created from Laravel data_get() helper, to fix an issue in the original :
     *
     * When $target is an array and $key points to a field that is null, original method returns
     * null, which is the correct thing to do.
     * But when $target is an object, original method returns $default instead of null, which is wrong.
     *
     * This version fixes this issue by using property_exist() along with isset().
     */
    public static function get($target, $key, $default = null): mixed
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_iterable($target)) {
                    return value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = data_get($item, $key);
                }

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            $segment = match ($segment) {
                '\*' => '*',
                '\{first}' => '{first}',
                '{first}' => array_key_first(is_array($target) ? $target : (new Collection($target))->all()),
                '\{last}' => '{last}',
                '{last}' => array_key_last(is_array($target) ? $target : (new Collection($target))->all()),
                default => $segment,
            };

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target)) {
                if (isset($target->{$segment})) {
                    $target = $target->{$segment};
                } elseif (property_exists($target, $segment)) {
                    $target = $target->{$segment};
                } else {
                    return value($default);
                }
            } else {
                return value($default);
            }
        }

        return $target;
    }
}
