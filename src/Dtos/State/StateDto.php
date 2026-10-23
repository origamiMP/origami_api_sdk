<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\State;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\State\StateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;

class StateDto extends ApiResponseDto
{
    use HasIncludes;

    public int $id;

    public ?string $name;

    /**
     * Label of the button that moves an entity to this state, when it differs from the name.
     */
    public ?string $actionName;

    public ?string $description;

    /**
     * The state an entity starts in, e.g. the cart a quote request comes from.
     */
    public bool $isInitial;

    /**
     * The states the current user may move the entity to, given their user group type and roles.
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, StateDto>
     */
    public Collection $restrictedNextStates;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'next_states',
            // 'actions',
            // 'workflow',
            // 'next_states_by_user_group_type',
            'restricted_next_states' => StateDto::class,
            // 'actions_by_user_group_type',
            // 'restricted_actions',
            // 'order_seller_informations',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                     => 'id',
            'name'                   => 'name',
            'action_name'            => 'actionName',
            'description'            => 'description',
            'initial'                => 'isInitial',
            'restricted_next_states' => fn (object $states) => $this->restrictedNextStates = $this->collectIncluded($states, StateDto::class),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'          => ['required', 'integer'],
            'name'        => ['present', 'nullable', 'string'],
            'action_name' => ['present', 'nullable', 'string'],
            'description' => ['present', 'nullable', 'string'],
            'initial'     => ['required', 'boolean'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new StateDtoNotConstructableException($msg, previous: $previous);
    }
}
