<?php

namespace Tests\Unit\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

/**
 * Guards that `SellerDto` accepts every legacy seller state the central API can send in a seller's `state` field
 * (`seller_information.state`). `SellerDto` converts it to `SellerDtoStateEnum`: a missing case makes the Dto — and
 * the whole `SellerListDto` holding it — not constructable.
 */
class SellerDtoTest extends TestCase
{
    /**
     * A seller that registered but has not been reviewed yet (default value of `seller_information.state`).
     */
    public function test_state_case_1(): void
    {
        $sellerDto = $this->mockSellerDto(['state' => 'REGISTERED']);

        $this->assertSame(SellerDtoStateEnum::REGISTERED, $sellerDto->state);
    }

    /**
     * A seller pending review (legacy value of the `pending_review` lifecycle state).
     */
    public function test_state_case_2(): void
    {
        $sellerDto = $this->mockSellerDto(['state' => 'WAITING']);

        $this->assertSame(SellerDtoStateEnum::WAITING, $sellerDto->state);
    }

    /**
     * A validated seller.
     */
    public function test_state_case_3(): void
    {
        $sellerDto = $this->mockSellerDto(['state' => 'VALIDATED']);

        $this->assertSame(SellerDtoStateEnum::VALIDATED, $sellerDto->state);
    }

    /**
     * A response without fees_profile_id should give a null fees profile.
     */
    public function test_fees_profile_id_case_1(): void
    {
        $apiData = $this->mockSellerApiData();
        unset($apiData['fees_profile_id']);

        $sellerDto = new SellerDto(Obj::fromArray($apiData));

        $this->assertNull($sellerDto->feesProfileId);
    }

    /**
     * A response without mangopay_sca_status should still expose mangopayScaStatus, as null.
     */
    public function test_to_array_case_1(): void
    {
        $apiData = $this->mockSellerApiData();
        unset($apiData['mangopay_sca_status']);

        $array = (new SellerDto(Obj::fromArray($apiData)))->toArray();

        $this->assertArrayHasKey('mangopayScaStatus', $array);
        $this->assertNull($array['mangopayScaStatus']);
    }
}
