<?php

namespace Tests\Unit\Dtos\CustomField;

use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldGroupDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldGroupTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldModelValueDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldTranslationDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldModelValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

class CustomFieldModelValueDtoTest extends TestCase
{
    /**
     * The value should be read along with its config: id, type, privacy, translations (a plain list) and params.
     */
    public function test_config_case_1(): void
    {
        $customFieldDto = $this->mockCustomFieldModelValueDto([
            'value'  => '2 days',
            'config' => $this->mockCustomFieldModelValueConfigApiData([
                'id'      => 42,
                'type'    => CustomFieldDtoTypeEnum::TEXTAREA->value,
                'private' => true,
                'params'  => ['max_length' => 255],
            ]),
        ]);

        $this->assertSame('2 days', $customFieldDto->value);
        $this->assertSame(42, $customFieldDto->id);
        $this->assertSame(CustomFieldDtoTypeEnum::TEXTAREA, $customFieldDto->type);
        $this->assertTrue($customFieldDto->isPrivate);
        $this->assertContainsOnlyInstancesOf(CustomFieldTranslationDto::class, $customFieldDto->translations);
        $this->assertSame(['Delivery time'], $customFieldDto->translations->pluck('name')->all());
        $this->assertEquals((object) ['max_length' => 255], $customFieldDto->params);
    }

    /**
     * A custom field without group nor params should have both read as null.
     */
    public function test_config_case_2(): void
    {
        $customFieldDto = $this->mockCustomFieldModelValueDto();

        $this->assertNull($customFieldDto->group);
        $this->assertNull($customFieldDto->params);
    }

    /**
     * The group of a custom field should be read with its key and translations (a plain list).
     */
    public function test_group_case_1(): void
    {
        $customFieldDto = $this->mockCustomFieldModelValueDto([
            'config' => $this->mockCustomFieldModelValueConfigApiData([
                'group' => $this->mockCustomFieldGroupApiData(['key' => 'logistics']),
            ]),
        ]);

        $this->assertInstanceOf(CustomFieldGroupDto::class, $customFieldDto->group);
        $this->assertSame('logistics', $customFieldDto->group->key);
        $this->assertContainsOnlyInstancesOf(CustomFieldGroupTranslationDto::class, $customFieldDto->group->translations);
        $this->assertSame(['Logistics'], $customFieldDto->group->translations->pluck('name')->all());
    }

    /**
     * A custom field with several values (ex: a multiple select) should have them read as a list.
     */
    public function test_value_case_1(): void
    {
        $customFieldDto = $this->mockCustomFieldModelValueDto([
            'value'  => ['express', 'standard'],
            'config' => $this->mockCustomFieldModelValueConfigApiData(['type' => CustomFieldDtoTypeEnum::MULTI_SELECT->value]),
        ]);

        $this->assertSame(['express', 'standard'], $customFieldDto->value);
    }

    /**
     * A custom field without any value is answered with a null value, which should be kept.
     */
    public function test_value_case_2(): void
    {
        $customFieldDto = $this->mockCustomFieldModelValueDto(['value' => null]);

        $this->assertNull($customFieldDto->value);
    }

    /**
     * A response without `value` should make the custom field not constructable.
     */
    public function test_value_case_3(): void
    {
        $apiData = $this->mockCustomFieldModelValueApiData();
        unset($apiData['value']);

        $this->assertThatExceptionWasThrown(
            fn () => new CustomFieldModelValueDto(Obj::fromArray($apiData)),
            CustomFieldModelValueDtoNotConstructableException::class,
        );
    }
}
