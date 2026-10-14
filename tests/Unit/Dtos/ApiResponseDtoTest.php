<?php

namespace Tests\Unit\Dtos;

use Tests\TestCase;

/**
 * Guards the convention for Dtos: any Dto implementing `Illuminate\Contracts\Support\Arrayable` must expose
 * its properties as-is in `toArray()` (camelCase, matching the Dto's own PHP property names) — never converted
 * to snake_case. This is what `ApiResponseDto::arrayFromPublicProperties()` provides to every Dto using it.
 */
class ApiResponseDtoTest extends TestCase
{
    public function test_seller_dto_to_array_case_1(): void
    {
        $sellerDto = $this->mockSellerDto();

        $array = $sellerDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('isActive', $array);
        $this->assertTrue($array['isActive']);
    }

    public function test_seller_list_dto_to_array_case_1(): void
    {
        $sellerListDto = $this->mockSellerListDto();

        $array = $sellerListDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('perPage', $array['meta']['pagination']);
        $this->assertArrayHasKey('currentPage', $array['meta']['pagination']);
        $this->assertArrayHasKey('totalPages', $array['meta']['pagination']);
    }

    public function test_language_dto_to_array_case_1(): void
    {
        $languageDto = $this->mockLanguageDto(['is_default' => true]);

        $array = $languageDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('isDefault', $array);
        $this->assertTrue($array['isDefault']);
    }
}
