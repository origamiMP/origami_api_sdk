<?php

namespace Tests\Unit\Dtos\Seller;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoStateEnum;
use Tests\TestCase;

/**
 * Guards that `SellerDto` accepts every legacy seller state the central API can send in a seller's `state` field
 * (`seller_information.state`). `SellerDto` validates it against `SellerDtoStateEnum`: a missing case makes the
 * Dto — and the whole `SellerListDto` holding it — not constructable.
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
}
