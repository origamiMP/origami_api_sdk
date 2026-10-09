<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Form;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludedCollections;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class FormDto extends ApiResponseDto implements Arrayable
{
    use HasIncludedCollections, HasTimestamps;

    public int $id;

    public bool $isActive;

    public bool $isPurchaseEnabled;

    public bool $isQuoteEnabled;

    public bool $isStandaloneEnabled;

    /** Only sent with `with_count=fields`. */
    public ?int $fieldsCount = null;

    /** @var Collection<int, FormTranslationDto> */
    public Collection $translations;

    /** Null when the `pages` include is absent (list responses). @var Collection<int, FormPageDto>|null */
    public ?Collection $pages = null;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                    => 'id',
            'is_active'             => 'isActive',
            'is_purchase_enabled'   => 'isPurchaseEnabled',
            'is_quote_enabled'      => 'isQuoteEnabled',
            'is_standalone_enabled' => 'isStandaloneEnabled',
            'fields_count'          => 'fieldsCount',
            'translations'          => fn (object $translations) => $this->translations = $this->collectIncluded($translations, FormTranslationDto::class),
            'pages'                 => fn (object $pages) => $this->pages = $this->collectIncluded($pages, FormPageDto::class),
        ] + $this->getTimestampsAsDataStructureToProperties();
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                    => ['required', 'integer'],
            'is_active'             => ['required', 'boolean'],
            'is_purchase_enabled'   => ['required', 'boolean'],
            'is_quote_enabled'      => ['required', 'boolean'],
            'is_standalone_enabled' => ['required', 'boolean'],
            'fields_count'          => ['nullable', 'integer'],
            'translations'          => ['required'],
        ] + $this->getTimestampsValidationRules();
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new FormDtoNotConstructableException($msg, previous: $previous);
    }

    public function toArray(): array
    {
        return $this->arrayFromPublicProperties();
    }
}
