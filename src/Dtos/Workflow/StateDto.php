<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Workflow;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Workflow\StateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class StateDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public string $key;

    public ?string $color;

    public bool $initial;

    public bool $isFinal;

    public bool $readonly;

    public int $workflowId;

    public string $textColor;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, StateTranslationDto>
     */
    public Collection $translations;

    public static function getAvailableIncludes(): array
    {
        return [
            'translations' => StateTranslationDto::class,
            // 'next_states',
            // 'actions',
            // 'workflow',
            // 'restricted_next_states',
            // 'restricted_actions',
            // 'order_seller_informations',
            // 'service_booking_informations',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'           => 'id',
            'key'          => 'key',
            'color'        => 'color',
            'initial'      => 'initial',
            'is_final'     => 'isFinal',
            'readonly'     => 'readonly',
            'workflow_id'  => 'workflowId',
            'text_color'   => 'textColor',
            'translations' => fn (object $translations) => $this->translations = $this->collectIncluded($translations, StateTranslationDto::class),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateDtoNotConstructableException($msg, previous: $previous);
    }
}
