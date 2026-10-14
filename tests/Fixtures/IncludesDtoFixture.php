<?php

namespace Tests\Fixtures;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTranslationDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

/**
 * Minimal Dto with includes, to test the HasIncludes trait: its own type is included as a collection (`children`)
 * and as a single item (`parent`), and a Dto without includes as `translation`.
 */
class IncludesDtoFixture extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    /** @var Collection<int, IncludesDtoFixture> */
    public Collection $children;

    public IncludesDtoFixture $parent;

    public static function getAvailableIncludes(): array
    {
        return [
            'children'    => self::class,
            'parent'      => self::class,
            'translation' => FormTranslationDto::class,
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'       => 'id',
            'children' => fn (object $children) => $this->children = $this->collectIncluded($children, self::class),
            'parent'   => fn (object $parent) => $this->parent = $this->makeIncluded($parent, self::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id' => ['required', 'integer'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new class($msg, previous: $previous) extends ApiResponseDtoNotConstructableException {};
    }
}
