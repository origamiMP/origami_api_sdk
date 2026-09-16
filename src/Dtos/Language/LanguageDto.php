<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Language;

use Illuminate\Contracts\Support\Arrayable;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageDtoNotConstructableException;

class LanguageDto extends ApiResponseDto implements Arrayable
{
    public int $id;

    public string $locale;

    public string $name;

    public bool $isDefault;

    public bool $isActive;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'         => 'id',
            'locale'     => 'locale',
            'name'       => 'name',
            'is_default' => 'isDefault',
            'is_active'  => 'isActive',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'         => ['required', 'integer'],
            'locale'     => ['required', 'string'],
            'name'       => ['required', 'string'],
            'is_default' => ['required', 'boolean'],
            'is_active'  => ['required', 'boolean'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new LanguageDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
