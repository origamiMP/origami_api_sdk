<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldModelValueDtoNotConstructableException;

/**
 * Represents a Custom Field's value as embedded on an entity (Seller, User...),
 * e.g. in the `additional_information` of `GET sellers/{id}`.
 *
 * Distinct from CustomFieldDto, which represents a Custom Field definition, as
 * returned by the `custom_fields` catalog endpoint.
 */
class CustomFieldModelValueDto extends ApiResponseDto
{
    public mixed $value;

    public int $id;

    public CustomFieldDtoTypeEnum $type;

    public bool $isPrivate;

    /** @var Collection<int, CustomFieldTranslationDto> */
    public Collection $translations;

    public ?CustomFieldGroupDto $group;

    public ?object $params;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'value'  => 'value',
            'config' => [
                'id'           => 'id',
                'type'         => fn ($type) => $this->type = CustomFieldDtoTypeEnum::from($type),
                'private'      => 'isPrivate',
                'translations' => fn ($translations) => $this->initTranslations($translations),
                'group'        => fn ($group) => $this->group = is_null($group) ? null : new CustomFieldGroupDto($group),
                'params'       => 'params',
            ],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldModelValueDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initTranslations(array $translations): void
    {
        $this->translations = collect($translations)->map(fn ($translation) => new CustomFieldTranslationDto($translation));
    }
}
