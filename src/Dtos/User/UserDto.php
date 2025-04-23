<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\UserDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCustomFields;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

// TODO DEV : Documentation
class UserDto extends ApiResponseDto
{
    use HasCustomFields, HasTimestamps;

    public int $id;

    public string $firstName;

    public string $lastName;

    public string $name;

    public string $email;

    public ?string $externalConfiguration;

    public ?int $languageId;

    public ?string $languageIso;

    public ?int $moduleId;

    public bool $hasAccessToSellerFeatures;

    /**
     * May be undefined if not used by your Origami API.
     */
    public UserDtoStateEnum $state;

    /**
     * May be undefined if not used by your Origami API.
     */
    public bool $isAccountValidated;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var UserGroupDto[]|Collection
     */
    public Collection $userGroups;

    // TODO public array $roles;

    // TODO public array $userGroupUsers;

    // TODO public ModuleDto $module;

    // TODO public array $userReportsReceived;

    /**
     * @throws UserDtoNotConstructableException
     */
    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                            => 'id',
            'firstname'                     => 'firstName',
            'lastname'                      => 'lastName',
            'name'                          => 'name',
            'email'                         => 'email',
            'external_configuration'        => 'externalConfiguration',
            'language_id'                   => 'languageId',
            'language_iso'                  => 'languageIso',
            'module_id'                     => 'moduleId',
            'has_access_to_seller_features' => 'hasAccessToSellerFeatures',
            'state'                         => fn ($state) => $this->state = UserDtoStateEnum::from($state),
            'is_account_validated'          => 'isAccountValidated',

            'user_groups' => fn ($userGroups) => $this->initUserGroups($userGroups),
            // TODO 'roles' => 'roles',
            // TODO 'user_group_users' => 'userGroupUsers',
            // TODO 'module' => 'module',
            // TODO 'user_reports_received' => 'userReportsReceived',
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
            $this->getCustomFieldsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $rules = [
            'id'                            => ['required', 'integer'],
            'firstname'                     => ['required', 'string'],
            'lastname'                      => ['required', 'string'],
            'name'                          => ['required', 'string'],
            'email'                         => ['required', 'email'],
            'external_configuration'        => ['present', 'nullable', 'json'],
            'language_id'                   => ['present', 'nullable', 'integer'],
            'language_iso'                  => ['present', 'nullable', 'string'],
            'module_id'                     => ['present', 'nullable', 'integer'],
            'has_access_to_seller_features' => ['required', 'boolean'],
            'state'                         => [Rule::in([UserDtoStateEnum::BANNED, UserDtoStateEnum::ENABLED])],
            'is_account_validated'          => ['boolean'],
        ];

        return array_merge(
            $rules,
            $this->getTimestampsValidationRules(),
            $this->getCustomFieldsValidationRules(),
        );
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initUserGroups($userGroups): void
    {
        $this->throwIfDataFieldOnObjectIsEmpty($userGroups);

        $this->userGroups = collect($userGroups->data)->map(fn ($userGroup) => new UserGroupDto($userGroup));
    }
}
