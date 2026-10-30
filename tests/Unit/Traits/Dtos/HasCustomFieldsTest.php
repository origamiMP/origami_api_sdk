<?php

namespace Tests\Unit\Traits\Dtos;

use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldModelValueDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldModelValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\CustomFieldsDtoFixture;
use Tests\TestCase;

/**
 * Behavior shared by every Dto with custom fields, tested through a fixture (which adds nothing to it).
 */
class HasCustomFieldsTest extends TestCase
{
    /**
     * `additional_information` should be read as a collection of custom field values, keyed by their custom field
     * `key`.
     */
    public function test_additional_information_case_1(): void
    {
        $dto = $this->makeFixture(['id' => 1, 'additional_information' => [
            'delivery_time' => $this->mockCustomFieldModelValueApiData([
                'value'  => 'express',
                'config' => $this->mockCustomFieldModelValueConfigApiData(['id' => 42]),
            ]),
            'is_certified' => $this->mockCustomFieldModelValueApiData(['value' => true]),
        ]]);

        $this->assertContainsOnlyInstancesOf(CustomFieldModelValueDto::class, $dto->additionalInformation);
        $this->assertSame(['delivery_time', 'is_certified'], $dto->additionalInformation->keys()->all());
        $this->assertSame(42, $dto->additionalInformation->get('delivery_time')->id);
        $this->assertSame('express', $dto->additionalInformation->get('delivery_time')->value);
        $this->assertTrue($dto->additionalInformation->get('is_certified')->value);
    }

    /**
     * An entity without custom fields has its `additional_information` answered as `[]` (an empty PHP map is encoded
     * as a list), which should be read as an empty collection.
     */
    public function test_additional_information_case_2(): void
    {
        $dto = $this->makeFixture(['id' => 1, 'additional_information' => []]);

        $this->assertTrue($dto->additionalInformation->isEmpty());
    }

    /**
     * A response without `additional_information` should make the Dto not constructable, since the API always sends it.
     */
    public function test_additional_information_case_3(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['id' => 1]),
            ApiResponseDtoNotConstructableException::class,
            "[CustomFieldsDtoFixture] Missing key 'additional_information' in the API response",
        );
    }

    /**
     * An `additional_information` that is not a map should make the Dto not constructable.
     */
    #[DataProvider('invalidAdditionalInformationProvider')]
    public function test_additional_information_case_4(mixed $additionalInformation): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['id' => 1, 'additional_information' => $additionalInformation]),
            ApiResponseDtoNotConstructableException::class,
            "[CustomFieldsDtoFixture] Could not assign data 'additional_information' to property: ",
        );
    }

    public static function invalidAdditionalInformationProvider(): array
    {
        return [
            'null'    => [null],
            'string'  => ['express'],
            'integer' => [42],
        ];
    }

    /**
     * An invalid custom field in `additional_information` should make the Dto not constructable.
     */
    public function test_additional_information_case_5(): void
    {
        $exception = $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['id' => 1, 'additional_information' => [
                'delivery_time' => $this->mockCustomFieldModelValueApiData([
                    'config' => $this->mockCustomFieldModelValueConfigApiData(['type' => 'unknown']),
                ]),
            ]]),
            ApiResponseDtoNotConstructableException::class,
            "[CustomFieldsDtoFixture] Could not assign data 'additional_information' to property: "
            ."[CustomFieldModelValueDto] Could not assign data 'config.type' to property: "
            .'"unknown" is not a valid backing value for enum '.CustomFieldDtoTypeEnum::class,
        );

        $this->assertInstanceOf(CustomFieldModelValueDtoNotConstructableException::class, $exception->getPrevious());
    }

    private function makeFixture(array $apiData): CustomFieldsDtoFixture
    {
        return new CustomFieldsDtoFixture(Obj::fromArray($apiData));
    }
}
