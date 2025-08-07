<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasAvailableIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasCustomFields;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class UserDto extends ApiResponseDto
{
    use HasAvailableIncludes, HasCustomFields, HasTimestamps;

    protected static array $availableIncludes = [
        'user_groups' => UserGroupDto::class,
        // 'roles',
        // 'user_group_users',
        // 'module',
        // 'user_reports_received',
    ];

    public int $id;

    public string $firstName;

    public string $lastName;

    public string $name;

    public string $email;

    /**
     * User-specific configuration used by Origami Back-office for certain behaviours.
     */
    public ?string $externalConfiguration;

    /**
     * Users have an assigned language, which will be for example used when sending notifications to them.
     */
    public ?int $languageId;

    /**
     * ISO code of the language of this User
     */
    public ?string $languageIso;

    /**
     * Some Users are automatically created by Origami API when certain modules are installed. In this case,
     * this field will be filled with the linked module id.
     */
    public ?int $moduleId;

    /**
     * True if this User has access to seller features, such as tickets, creating / updating a product...
     * This field can be updated depending on existing UserReports, or MFA validation.
     */
    public bool $hasAccessToSellerFeatures;

    /**
     * If UserReports are activated on your API, the state of this User will be updated depending on
     * if it is reported or not.
     *
     * May be undefined if not used by your Origami API.
     */
    public UserDtoStateEnum $state;

    /**
     * If MFA module is activated on your API, will be true if this User is validated, false otherwise.
     *
     * May be undefined if not used by your Origami API.
     */
    public bool $isAccountValidated;

    /**
     * UserGroups to which this User has access when logged in.
     *
     * May be undefined if the corresponding data was not included.
     *
     * @var UserGroupDto[]|Collection
     */
    public Collection $userGroups;

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
        ];

        return array_merge(
            $structure,
            $this->getTimestampsAsDataStructureToProperties(),
            $this->getCustomFieldsAsDataStructureToProperties(),
        );
    }

    protected function validationRulesForProperties(): array
    {
        $states = collect(UserDtoStateEnum::cases())->pluck('value');

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
            'state'                         => [Rule::in($states)],
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
