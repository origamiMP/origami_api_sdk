<?php

namespace Tests\Unit\Dtos\Customer;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CustomerGroupDtoTest extends TestCase
{
    /**
     * A group read on its own has no `is_default` key: it should be read as null.
     */
    public function test_is_default_case_1(): void
    {
        $customerGroup = $this->mockCustomerGroupDto();

        $this->assertNull($customerGroup->isDefault);
    }

    /**
     * A group included from a customer has its `is_default` flag as an integer: it should be read as a boolean.
     */
    #[DataProvider('isDefaultProvider')]
    public function test_is_default_case_2(mixed $isDefault, bool $expected): void
    {
        $customerGroup = $this->mockCustomerGroupDto(['is_default' => $isDefault]);

        $this->assertSame($expected, $customerGroup->isDefault);
    }

    public static function isDefaultProvider(): array
    {
        return [
            'default group'     => [1, true],
            'not default group' => [0, false],
        ];
    }
}
