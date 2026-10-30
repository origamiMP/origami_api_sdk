<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\User;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\User\UserGroupDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasIncludes;
use OrigamiMp\OrigamiApiSdk\Traits\Dtos\HasTimestamps;

class UserGroupDto extends ApiResponseDto
{
    use HasIncludes, HasTimestamps;

    public int $id;

    public UserGroupDtoTypeEnum $type;

    public int $userGroupParentId;

    public ?string $firstName;

    public ?string $lastName;

    public string $name;

    public string $email;

    public ?string $phone;

    public ?string $phoneAreaCode;

    public ?int $addressId;

    public string $addressLine1;

    public string $addressLine2;

    public string $addressCity;

    public string $addressPostCode;

    public string $addressCountry;

    public string $addressCountryIso;

    public string $addressLongitude;

    public string $addressLatitude;

    public string $addressRegion;

    public string $fullAddress;

    public ?string $imageUrl;

    public ?string $imageCoverUrl;

    public float $rateTotal;

    public int $rateCount;

    public ?string $languageIso;

    public ?int $languageId;

    public ?string $externalUid;

    public bool $emailValidated;

    public bool $phoneValidated;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, SellerDocumentDto>
     */
    public Collection $documents;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, SellerBankAccountDto>
     */
    public Collection $bankAccounts;

    /**
     * May be undefined if the corresponding data was not included.
     *
     * @var Collection<int, UserDto>
     */
    public Collection $users;

    public static function getAvailableIncludes(): array
    {
        return [
            // 'shipping_offers',
            // 'product_offers',
            // 'tickets',
            'documents' => SellerDocumentDto::class,
            // 'payment_reports',
            'bank_accounts' => SellerBankAccountDto::class,
            // 'invoices',
            // 'subscriptions',
            // 'subscription_lines',
            'users' => UserDto::class,
            // 'legal_information',
            // 'psp_wallets',
            // 'current_psp_wallet',
            // 'psp_users',
            // 'current_psp_user',
            // 'translations',
            // 'user_group_users',
            // 'mandates',
            // 'customer_groups',
        ];
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        $structure = [
            'id'                   => 'id',
            'type'                 => fn ($type) => $this->type = UserGroupDtoTypeEnum::from($type),
            'user_group_parent_id' => 'userGroupParentId',
            'firstname'            => 'firstName',
            'lastname'             => 'lastName',
            'name'                 => 'name',
            'email'                => 'email',
            'phone'                => 'phone',
            'phone_area_code'      => 'phoneAreaCode',
            'address_id'           => 'addressId',
            'address_line1'        => 'addressLine1',
            'address_line2'        => 'addressLine2',
            'address_city'         => 'addressCity',
            'address_postcode'     => 'addressPostCode',
            'address_country'      => 'addressCountry',
            'address_country_iso'  => 'addressCountryIso',
            'address_longitude'    => 'addressLongitude',
            'address_latitude'     => 'addressLatitude',
            'address_region'       => 'addressRegion',
            'address_full'         => 'fullAddress',
            'image_url'            => 'imageUrl',
            'image_cover_url'      => 'imageCoverUrl',
            'rate_total'           => 'rateTotal',
            'rate_count'           => 'rateCount',
            'language_iso'         => 'languageIso',
            'language_id'          => 'languageId',
            'external_uid'         => 'externalUid',
            'email_validated'      => 'emailValidated',
            'phone_validated'      => 'phoneValidated',

            'documents'     => fn (object $documents) => $this->documents = $this->collectIncluded($documents, SellerDocumentDto::class),
            'bank_accounts' => fn (object $bankAccounts) => $this->bankAccounts = $this->collectIncluded($bankAccounts, SellerBankAccountDto::class),
            'users'         => fn (object $users) => $this->users = $this->collectIncluded($users, UserDto::class),
        ];

        return [
            ...$structure,
            ...$this->getTimestampsAsDataStructureToProperties(),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new UserGroupDtoNotConstructableException($msg, previous: $previous);
    }
}
