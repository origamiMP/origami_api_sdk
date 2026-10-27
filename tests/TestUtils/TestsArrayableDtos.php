<?php

namespace Tests\TestUtils;

trait TestsArrayableDtos
{
    /**
     * Recursively asserts every string key of $array (and of any nested array) is camelCase,
     * guarding the convention `OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto::arrayFromPublicProperties()`
     * is meant to provide to every Arrayable Dto : property names are kept as-is (no snake_case
     * conversion), so keys must never contain an underscore.
     */
    protected function assertArrayKeysAreCamelCase(array $array): void
    {
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $this->assertMatchesRegularExpression(
                    '/^[a-z][a-zA-Z0-9]*$/',
                    $key,
                    "Expected key \"$key\" to be camelCase (no underscore).",
                );
            }

            if (is_array($value)) {
                $this->assertArrayKeysAreCamelCase($value);
            }
        }
    }
}
