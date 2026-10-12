<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\TestUtils\TestsArrayableDtos;
use Tests\TestUtils\TestsCountryDtos;
use Tests\TestUtils\TestsCustomFieldDtos;
use Tests\TestUtils\TestsErrorDtos;
use Tests\TestUtils\TestsLanguageDtos;
use Tests\TestUtils\TestsModuleDtos;
use Tests\TestUtils\TestsOauthDtos;
use Tests\TestUtils\TestsRestClient;
use Tests\TestUtils\TestsSellerDtos;
use Tests\TestUtils\TestsTaxDtos;
use Tests\TestUtils\TestsUserDtos;
use Tests\TestUtils\TestsUserGroupInvitationDtos;

abstract class TestCase extends BaseTestCase
{
    use TestsArrayableDtos,
        TestsCountryDtos,
        TestsCustomFieldDtos,
        TestsErrorDtos,
        TestsLanguageDtos,
        TestsModuleDtos,
        TestsOauthDtos,
        TestsRestClient,
        TestsSellerDtos,
        TestsTaxDtos,
        TestsUserDtos,
        TestsUserGroupInvitationDtos;

    /**
     * Builds the "pagination" part of a paginated Origami API list response's "meta" field, single-page.
     */
    protected function mockApiPagination(int $count): array
    {
        return [
            'total'        => $count,
            'count'        => $count,
            'per_page'     => $count,
            'current_page' => 1,
            'total_pages'  => 1,
        ];
    }

    /**
     * Builds a paginated Origami API list response, single-page, holding $items.
     */
    protected function mockApiPaginatedListData(array $items): array
    {
        return [
            'data' => $items,
            'meta' => ['pagination' => $this->mockApiPagination(count($items))],
        ];
    }
}
