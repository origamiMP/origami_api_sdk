<?php

namespace Tests\Unit\Dtos\User;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use Tests\TestCase;

class UserGroupDtoTest extends TestCase
{
    /**
     * The `documents` include should be read as a collection of documents.
     */
    public function test_documents_case_1(): void
    {
        $userGroupDto = $this->mockUserGroupDto(['documents' => ['data' => [
            $this->mockSellerDocumentApiData(['id' => 1]),
            $this->mockSellerDocumentApiData(['id' => 2]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(SellerDocumentDto::class, $userGroupDto->documents);
        $this->assertSame([1, 2], $userGroupDto->documents->pluck('id')->all());
    }

    /**
     * The `bank_accounts` include should be read as a collection of bank accounts.
     */
    public function test_bank_accounts_case_1(): void
    {
        $userGroupDto = $this->mockUserGroupDto(['bank_accounts' => ['data' => [
            $this->mockSellerBankAccountApiData(['id' => 1]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(SellerBankAccountDto::class, $userGroupDto->bankAccounts);
        $this->assertSame([1], $userGroupDto->bankAccounts->pluck('id')->all());
    }

    /**
     * The `users` include should be read as a collection of users.
     */
    public function test_users_case_1(): void
    {
        $userGroupDto = $this->mockUserGroupDto(['users' => ['data' => [
            $this->mockUserApiData(['id' => 1]),
            $this->mockUserApiData(['id' => 2]),
        ]]]);

        $this->assertContainsOnlyInstancesOf(UserDto::class, $userGroupDto->users);
        $this->assertSame([1, 2], $userGroupDto->users->pluck('id')->all());
    }

    /**
     * Without any include, the included collections should stay undefined.
     */
    public function test_includes_case_1(): void
    {
        $userGroupDto = $this->mockUserGroupDto();

        $this->assertFalse(isset($userGroupDto->users));
        $this->assertFalse(isset($userGroupDto->documents));
        $this->assertFalse(isset($userGroupDto->bankAccounts));
    }
}
