<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\CustomField;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\CustomField\CustomFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldDtoNotConstructableException;

class CustomFieldDto extends ApiResponseDto
{
    public mixed $value;

    public int $id;

    public CustomFieldDtoTypeEnum $type;

    public bool $isPrivate;

    /**
     * @var Collection|CustomFieldTranslationDto[]
     */
    public Collection $translations;

    public ?CustomFieldGroupDto $group;

    public ?object $params;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

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

    protected function validationRulesForProperties(): array
    {
        $types = collect(CustomFieldDtoTypeEnum::cases())->pluck('value');

        return [
            'value'               => ['present'],
            'config.id'           => ['required', 'int'],
            'config.type'         => ['required', Rule::in($types)],
            'config.private'      => ['required', 'boolean'],
            'config.translations' => ['required', 'array'],
            'config.group'        => ['nullable', 'array'],
            'config.params'       => ['present'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomFieldDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initTranslations(array $translations): void
    {
        $this->translations = collect($translations)->map(fn ($translation) => new CustomFieldTranslationDto($translation));
    }
}
