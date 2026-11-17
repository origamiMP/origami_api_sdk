<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomer\CreateCustomerUserParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

/**
 * Without `user`, the customer account has no user: nobody can book or order through it.
 */
class CreateCustomerRequestParamBag extends RequestParamBag implements HasIncludesContract
{
    use HasIncludes;

    public string $firstName;

    public string $lastName;

    public string $email;

    public ?string $phone;

    public ?string $languageIso;

    public CreateCustomerUserParamBag $user;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'firstName'   => 'firstname',
            'lastName'    => 'lastname',
            'email'       => 'email',
            'phone'       => 'phone',
            'languageIso' => 'language_iso',
            'user'        => 'user',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return CustomerDto::class;
    }
}
