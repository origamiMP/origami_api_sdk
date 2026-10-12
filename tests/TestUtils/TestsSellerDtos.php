<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoStateEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Seller\SellerDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Integer;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;

trait TestsSellerDtos
{
    protected function mockSellerApiData(array $override = []): array
    {
        $baseData = [
            'id'                       => Integer::unique(),
            'user_group_parent_id'     => Integer::unique(),
            'firstname'                => 'John',
            'lastname'                 => 'Doe',
            'name'                     => 'John Doe',
            'email'                    => 'john@doe.com',
            'phone'                    => null,
            'phone_area_code'          => null,
            'address_id'               => null,
            'address_line1'            => '',
            'address_line2'            => '',
            'address_city'             => '',
            'address_postcode'         => '',
            'address_country'          => '',
            'address_country_iso'      => '',
            'address_longitude'        => '',
            'address_latitude'         => '',
            'address_region'           => '',
            'address_full'             => '',
            'image_url'                => null,
            'image_cover_url'          => null,
            'rate_total'               => 0,
            'rate_count'               => 0,
            'language_iso'             => null,
            'language_id'              => null,
            'external_uid'             => null,
            'email_validated'          => false,
            'phone_validated'          => false,
            'mangopay_sca_status'      => null,
            'seller_type'              => SellerDtoTypeEnum::MARKETPLACE->value,
            'state'                    => SellerDtoStateEnum::VALIDATED->value,
            'default_tax_id'           => Integer::unique(),
            'fees_profile_id'          => null,
            'seller_fees_profile_id'   => null,
            'customer_fees_profile_id' => null,
            'is_active'                => true,
            'invoice_tax_id'           => null,
            'holiday_departure_date'   => null,
            'holiday_return_date'      => null,
            'deactivated_until'        => null,
            'is_in_holiday'            => false,
            'created_at'               => '2024-05-12 16:12:56',
            'updated_at'               => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockSellerDto(array $override = []): SellerDto
    {
        return new SellerDto(Obj::fromArray($this->mockSellerApiData($override)));
    }

    protected function mockSellerListDto(): SellerListDto
    {
        return new SellerListDto(Obj::fromArray($this->mockApiPaginatedListData([])));
    }

    protected function mockSellerBankAccountApiData(array $override = []): array
    {
        $baseData = [
            'id'                   => Integer::unique(),
            'user_group_id'        => Integer::unique(),
            'bank_name'            => 'Bank of Origami',
            'owner_name'           => 'John Doe',
            'owner_address'        => '10 rue de Rivoli, 75001 Paris',
            'iban'                 => 'FR7630006000011234567890189',
            'bic'                  => 'AGRIFRPP',
            'is_default'           => true,
            'is_active'            => true,
            'psp_validation_state' => false,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockSellerDocumentApiData(array $override = []): array
    {
        $baseData = [
            'id'               => Integer::unique(),
            'seller_id'        => Integer::unique(),
            'document_type_id' => Integer::unique(),
            'name'             => 'ID card',
            'validate'         => false,
            'url'              => 'https://example.com/document.pdf',
            'created_at'       => '2024-05-12 16:12:56',
            'updated_at'       => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockDocumentTypeApiData(array $override = []): array
    {
        $baseData = [
            'id'           => Integer::unique(),
            'type'         => 'identity',
            'mandatory_on' => 'seller',
            'format'       => 'pdf',
            'key'          => 'id_card',
            'required_by'  => ['mangopay'],
            'created_at'   => '2024-05-12 16:12:56',
            'updated_at'   => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }
}
