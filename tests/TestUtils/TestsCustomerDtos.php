<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerGroupDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsCustomerDtos
{
    /**
     * The API sends every key of the customer account (user group and its parent); only those read by the DTO are here.
     */
    protected function mockCustomerApiData(array $override = []): array
    {
        $baseData = [
            'id'                   => TestCase::unique(),
            'user_group_parent_id' => TestCase::unique(),
            'firstname'            => 'Jane',
            'lastname'             => 'Doe',
            'name'                 => 'Jane Doe',
            'email'                => 'jane@doe.com',
            'phone'                => '+33612345678',
            'created_at'           => '2024-05-12 16:12:56',
            'updated_at'           => '2024-05-12 16:12:56',
        ];

        return [...$baseData, ...$override];
    }

    /**
     * The `is_default` key is only sent when the group is included from a customer: add it by override, like the
     * `translations` include.
     */
    protected function mockCustomerGroupApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'external_uid' => null,
        ];

        return [...$baseData, ...$override];
    }

    protected function mockCustomerGroupTranslationApiData(array $override = []): array
    {
        $baseData = [
            'id'          => TestCase::unique(),
            'name'        => 'Professionals',
            'language_id' => TestCase::unique(),
            'locale'      => 'en',
        ];

        return [...$baseData, ...$override];
    }

    protected function mockCustomerGroupDto(array $override = []): CustomerGroupDto
    {
        return new CustomerGroupDto(Obj::fromArray($this->mockCustomerGroupApiData($override)));
    }
}
