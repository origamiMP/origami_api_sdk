<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Customer\CreateCustomer;

use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

/**
 * The user created with the customer account, who can then book or order through it.
 */
class CreateCustomerUserParamBag extends ParamBag
{
    public string $firstName;

    public string $lastName;

    public string $email;

    public string $password;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'firstName' => 'firstname',
            'lastName'  => 'lastname',
            'email'     => 'email',
            'password'  => 'password',
        ];
    }
}
