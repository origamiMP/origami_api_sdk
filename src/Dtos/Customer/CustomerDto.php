<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Customer;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

/**
 * A customer account, with the users who book or order through it (`users` include): a booking is made for one of them.
 */
class CustomerDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public int $userGroupParentId;

    public ?string $firstName;

    public ?string $lastName;

    public string $name;

    public string $email;

    public ?string $phone;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, UserDto>
     */
    public Collection $users;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'bank_accounts',
            // 'customer_groups',
            // 'documents',
            // 'invoices',
            // 'legal_information',
            // 'mandates',
            // 'order_sellers',
            // 'order_addresses',
            // 'payment_reports',
            // 'product_offers',
            // 'psp_wallets',
            // 'shipping_offers',
            // 'subscriptions',
            // 'tickets',
            'users' => UserDto::class,
            // 'user_group_users',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'                   => 'id',
            'user_group_parent_id' => 'userGroupParentId',
            'firstname'            => 'firstName',
            'lastname'             => 'lastName',
            'name'                 => 'name',
            'email'                => 'email',
            'phone'                => 'phone',
            'users'                => fn (object $users) => $this->users = $this->collectIncluded($users, UserDto::class),
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new CustomerDtoNotConstructableException($msg, previous: $previous);
    }
}
