<?php

namespace Tests\Unit\Dtos;

use OrigamiMp\OrigamiApiSdk\Dtos\Address\AddressDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Configuration\ConfigurationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Customer\CustomerListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldGroupDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldGroupTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldModelValueDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldValueDto;
use OrigamiMp\OrigamiApiSdk\Dtos\CustomField\CustomFieldValueListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorsDto;
use OrigamiMp\OrigamiApiSdk\Dtos\FeatureFlag\FeatureFlagListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormConditionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormFieldDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormFieldOptionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormFieldOptionTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormFieldParamsDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormFieldTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPageDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPageTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPrefillSourceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPrefillSourcesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSectionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSectionTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTypeDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTypesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Oauth\OauthTokenDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\ResetPasswordDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\SendResetPasswordEmailDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductOfferDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Product\ProductTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerLegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingCustomerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingCustomerUserDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingOrderDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceBookingSellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServicePlanningDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServicePlanningListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Service\ServiceSlotDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesFailedDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewPresenceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewRejectSeriesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceAvailabilityReviewSlotDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalSellerSummaryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceLateWithdrawalSummaryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePlanningSiteDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceCreateSeriesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceCreateSeriesSkippedDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServicePresenceListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceResourceUpdateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardBookingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardEventDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardResourceDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardSiteDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteBoardSlotDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventCreateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteEventListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSiteOpeningHourDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources\ServiceSitePlanningDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Tax\TaxTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\RoleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\RolePermissionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationSendDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsDto;
use OrigamiMp\OrigamiApiSdk\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Address\AddressDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Configuration\ConfigurationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Customer\CustomerListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldGroupTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldModelValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\CustomField\CustomFieldValueListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Error\OrigamiApiErrorDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Error\OrigamiApiErrorsDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\FeatureFlag\FeatureFlagListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormConditionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldOptionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldOptionTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldParamsDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormFieldTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPageDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPageTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPrefillSourceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormPrefillSourcesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSectionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSectionTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypeDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Oauth\OauthTokenDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\ResetPasswordDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\SendResetPasswordEmailDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductOfferDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Product\ProductTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\DocumentTypeDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\DocumentTypeListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerBankAccountDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDocumentDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerLegalTypeListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingCustomerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingCustomerUserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingOrderDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceBookingSellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServicePlanningDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServicePlanningListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Service\ServiceSlotDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewApproveSeriesFailedDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewPresenceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewRejectSeriesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceAvailabilityReviewSlotDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalSellerSummaryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceLateWithdrawalSummaryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePlanningSiteDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceCreateSeriesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceCreateSeriesSkippedDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServicePresenceListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceResourceUpdateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardBookingDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardEventDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardResourceDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardSiteDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardSlotDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventCreateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteEventListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteOpeningHourDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSitePlanningDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RoleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RolePermissionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationSendDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Workflow\StateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\IncludesDtoFixture;
use Tests\TestCase;

/**
 * Behavior shared by every Dto (`ApiResponseDto::fill()`), tested through a fixture (which adds nothing to it), and
 * guards the conventions shared by every Dto:
 * - every Dto must expose its properties as-is in `toArray()` (camelCase, matching the Dto's own PHP property
 *   names) — never converted to snake_case. This is what `ApiResponseDto::arrayFromPublicProperties()` provides;
 * - any Dto must reject an invalid API response with its own NotConstructable exception, so that the caller knows
 *   which part of the response is wrong. Every Dto must be listed in `notConstructableProvider()`.
 */
class ApiResponseDtoTest extends TestCase
{
    public function test_seller_dto_to_array_case_1(): void
    {
        $sellerDto = $this->mockSellerDto();

        $array = $sellerDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('isActive', $array);
        $this->assertTrue($array['isActive']);
    }

    public function test_seller_list_dto_to_array_case_1(): void
    {
        $sellerListDto = $this->mockSellerListDto();

        $array = $sellerListDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('perPage', $array['meta']['pagination']);
        $this->assertArrayHasKey('currentPage', $array['meta']['pagination']);
        $this->assertArrayHasKey('totalPages', $array['meta']['pagination']);
    }

    public function test_language_dto_to_array_case_1(): void
    {
        $languageDto = $this->mockLanguageDto(['is_default' => true]);

        $array = $languageDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('isDefault', $array);
        $this->assertTrue($array['isDefault']);
    }

    /**
     * A Dto without its own toArray() should expose its properties as-is, its enums as their value and its included
     * collections as arrays.
     */
    public function test_tax_dto_to_array_case_1(): void
    {
        $taxDto = $this->mockTaxDto();

        $array = $taxDto->toArray();

        $this->assertArrayKeysAreCamelCase($array);
        $this->assertArrayHasKey('isDefault', $array);
        $this->assertArrayHasKey('idScsTax', $array);
        $this->assertSame('percentage', $array['type']);
        $this->assertSame(['VAT'], array_column($array['translations'], 'name'));
    }

    /**
     * Timestamps should be exposed in the DATE_ATOM format.
     */
    public function test_custom_field_dto_to_array_case_1(): void
    {
        $customFieldDto = $this->mockCustomFieldDto(['created_at' => '2024-05-12 16:12:56']);

        $array = $customFieldDto->toArray();

        $this->assertSame('2024-05-12T16:12:56+00:00', $array['createdAt']);
    }

    /**
     * A key of the data structure missing from the response should make the Dto not constructable.
     */
    public function test_fill_case_1(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->makeFixture(['children' => ['data' => []]]),
            ApiResponseDtoNotConstructableException::class,
            "[IncludesDtoFixture] Missing key 'id' in the API response",
        );
    }

    /**
     * A missing include should leave its property unset.
     */
    public function test_fill_case_2(): void
    {
        $dto = $this->makeFixture(['id' => 42]);

        $this->assertFalse(isset($dto->children));
    }

    /**
     * A missing key declared optional by the Dto should leave its property as is.
     */
    public function test_fill_case_3(): void
    {
        $error = $this->mockOrigamiApiErrorDto(422, 'The name is required.', '0');

        $this->assertNull($error->field);
    }

    /**
     * An invalid API response (an empty one, or one with a value of the wrong type when nothing is required) should
     * make the Dto not constructable, with its own exception.
     *
     * @param  class-string<ApiResponseDto>  $dtoClass
     * @param  class-string<ApiResponseDtoNotConstructableException>  $expectedExceptionClass
     */
    #[DataProvider('notConstructableProvider')]
    public function test_not_constructable_case_1(string $dtoClass, string $expectedExceptionClass, object $apiResponse): void
    {
        $exception = $this->assertThatExceptionWasThrown(fn () => new $dtoClass($apiResponse), $expectedExceptionClass);

        $this->assertStringStartsWith('['.class_basename($dtoClass).']', $exception->getMessage());
    }

    /**
     * Every Dto of the SDK should be listed in `notConstructableProvider()`, so that none of them misses its error
     * case.
     */
    public function test_not_constructable_case_2(): void
    {
        $dtoClasses = collect(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__DIR__, 3).'/src/Dtos')))
            ->filter(fn (\SplFileInfo $file) => $file->getExtension() === 'php' && $file->getFilename() !== 'ApiResponseDto.php')
            ->map(fn (\SplFileInfo $file) => $file->getBasename('.php'))
            ->values()
            ->all();

        $this->assertEqualsCanonicalizing($dtoClasses, array_keys(self::notConstructableProvider()));
    }

    public static function notConstructableProvider(): array
    {
        return [
            'ConfigurationDto'                                => [ConfigurationDto::class, ConfigurationDtoNotConstructableException::class, (object) []],
            'CountryDto'                                      => [CountryDto::class, CountryDtoNotConstructableException::class, (object) []],
            'CountryListDto'                                  => [CountryListDto::class, CountryListDtoNotConstructableException::class, (object) []],
            'CustomFieldDto'                                  => [CustomFieldDto::class, CustomFieldDtoNotConstructableException::class, (object) []],
            'CustomFieldGroupDto'                             => [CustomFieldGroupDto::class, CustomFieldGroupDtoNotConstructableException::class, (object) []],
            'CustomFieldGroupTranslationDto'                  => [CustomFieldGroupTranslationDto::class, CustomFieldGroupTranslationDtoNotConstructableException::class, (object) []],
            'CustomFieldListDto'                              => [CustomFieldListDto::class, CustomFieldListDtoNotConstructableException::class, (object) []],
            'CustomFieldModelValueDto'                        => [CustomFieldModelValueDto::class, CustomFieldModelValueDtoNotConstructableException::class, (object) []],
            'CustomFieldTranslationDto'                       => [CustomFieldTranslationDto::class, CustomFieldTranslationDtoNotConstructableException::class, (object) []],
            'CustomFieldValueDto'                             => [CustomFieldValueDto::class, CustomFieldValueDtoNotConstructableException::class, (object) []],
            'CustomFieldValueListDto'                         => [CustomFieldValueListDto::class, CustomFieldValueListDtoNotConstructableException::class, (object) []],
            'CustomerDto'                                     => [CustomerDto::class, CustomerDtoNotConstructableException::class, (object) []],
            'CustomerListDto'                                 => [CustomerListDto::class, CustomerListDtoNotConstructableException::class, (object) []],
            'OrigamiApiErrorDto'                              => [OrigamiApiErrorDto::class, OrigamiApiErrorDtoNotConstructableException::class, (object) []],
            'OrigamiApiErrorsDto'                             => [OrigamiApiErrorsDto::class, OrigamiApiErrorsDtoNotConstructableException::class, (object) []],
            'FeatureFlagListDto'                              => [FeatureFlagListDto::class, FeatureFlagListDtoNotConstructableException::class, (object) ['some_flag' => 'yes']],
            'FormConditionDto'                                => [FormConditionDto::class, FormConditionDtoNotConstructableException::class, (object) []],
            'FormDto'                                         => [FormDto::class, FormDtoNotConstructableException::class, (object) []],
            'FormFieldDto'                                    => [FormFieldDto::class, FormFieldDtoNotConstructableException::class, (object) []],
            'FormFieldOptionDto'                              => [FormFieldOptionDto::class, FormFieldOptionDtoNotConstructableException::class, (object) []],
            'FormFieldOptionTranslationDto'                   => [FormFieldOptionTranslationDto::class, FormFieldOptionTranslationDtoNotConstructableException::class, (object) []],
            'FormFieldParamsDto'                              => [FormFieldParamsDto::class, FormFieldParamsDtoNotConstructableException::class, (object) ['max_size_kb' => 'big']],
            'FormFieldTranslationDto'                         => [FormFieldTranslationDto::class, FormFieldTranslationDtoNotConstructableException::class, (object) []],
            'FormListDto'                                     => [FormListDto::class, FormListDtoNotConstructableException::class, (object) []],
            'FormPageDto'                                     => [FormPageDto::class, FormPageDtoNotConstructableException::class, (object) []],
            'FormPageTranslationDto'                          => [FormPageTranslationDto::class, FormPageTranslationDtoNotConstructableException::class, (object) []],
            'FormPrefillSourceDto'                            => [FormPrefillSourceDto::class, FormPrefillSourceDtoNotConstructableException::class, (object) []],
            'FormPrefillSourcesDto'                           => [FormPrefillSourcesDto::class, FormPrefillSourcesDtoNotConstructableException::class, (object) []],
            'FormSectionDto'                                  => [FormSectionDto::class, FormSectionDtoNotConstructableException::class, (object) []],
            'FormSectionTranslationDto'                       => [FormSectionTranslationDto::class, FormSectionTranslationDtoNotConstructableException::class, (object) []],
            'FormTranslationDto'                              => [FormTranslationDto::class, FormTranslationDtoNotConstructableException::class, (object) []],
            'FormTypeDto'                                     => [FormTypeDto::class, FormTypeDtoNotConstructableException::class, (object) []],
            'FormTypesDto'                                    => [FormTypesDto::class, FormTypesDtoNotConstructableException::class, (object) []],
            'LanguageDto'                                     => [LanguageDto::class, LanguageDtoNotConstructableException::class, (object) []],
            'LanguageListDto'                                 => [LanguageListDto::class, LanguageListDtoNotConstructableException::class, (object) []],
            'ModuleDto'                                       => [ModuleDto::class, ModuleDtoNotConstructableException::class, (object) []],
            'ModuleListDto'                                   => [ModuleListDto::class, ModuleListDtoNotConstructableException::class, (object) []],
            'OauthTokenDto'                                   => [OauthTokenDto::class, OauthTokenDtoNotConstructableException::class, (object) []],
            'ResetPasswordDto'                                => [ResetPasswordDto::class, ResetPasswordDtoNotConstructableException::class, (object) []],
            'SendResetPasswordEmailDto'                       => [SendResetPasswordEmailDto::class, SendResetPasswordEmailDtoNotConstructableException::class, (object) []],
            'DocumentTypeDto'                                 => [DocumentTypeDto::class, DocumentTypeDtoNotConstructableException::class, (object) []],
            'DocumentTypeListDto'                             => [DocumentTypeListDto::class, DocumentTypeListDtoNotConstructableException::class, (object) []],
            'SellerBankAccountDto'                            => [SellerBankAccountDto::class, SellerBankAccountDtoNotConstructableException::class, (object) []],
            'SellerDocumentDto'                               => [SellerDocumentDto::class, SellerDocumentDtoNotConstructableException::class, (object) []],
            'SellerDto'                                       => [SellerDto::class, SellerDtoNotConstructableException::class, (object) []],
            'SellerLegalTypeListDto'                          => [SellerLegalTypeListDto::class, SellerLegalTypeListDtoNotConstructableException::class, (object) []],
            'SellerListDto'                                   => [SellerListDto::class, SellerListDtoNotConstructableException::class, (object) []],
            'ServiceBookingCustomerDto'                       => [ServiceBookingCustomerDto::class, ServiceBookingCustomerDtoNotConstructableException::class, (object) []],
            'ServiceBookingCustomerUserDto'                   => [ServiceBookingCustomerUserDto::class, ServiceBookingCustomerUserDtoNotConstructableException::class, (object) []],
            'ServiceBookingDto'                               => [ServiceBookingDto::class, ServiceBookingDtoNotConstructableException::class, (object) []],
            'ServiceBookingListDto'                           => [ServiceBookingListDto::class, ServiceBookingListDtoNotConstructableException::class, (object) []],
            'ServiceBookingOrderDto'                          => [ServiceBookingOrderDto::class, ServiceBookingOrderDtoNotConstructableException::class, (object) []],
            'ServiceBookingSellerDto'                         => [ServiceBookingSellerDto::class, ServiceBookingSellerDtoNotConstructableException::class, (object) []],
            'ServicePlanningDto'                              => [ServicePlanningDto::class, ServicePlanningDtoNotConstructableException::class, (object) []],
            'ServicePlanningListDto'                          => [ServicePlanningListDto::class, ServicePlanningListDtoNotConstructableException::class, (object) []],
            'ServiceSlotDto'                                  => [ServiceSlotDto::class, ServiceSlotDtoNotConstructableException::class, (object) []],
            'StateDto'                                        => [StateDto::class, StateDtoNotConstructableException::class, (object) []],
            'AddressDto'                                      => [AddressDto::class, AddressDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewDto'                    => [ServiceAvailabilityReviewDto::class, ServiceAvailabilityReviewDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewApproveSeriesDto'       => [ServiceAvailabilityReviewApproveSeriesDto::class, ServiceAvailabilityReviewApproveSeriesDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewApproveSeriesFailedDto' => [ServiceAvailabilityReviewApproveSeriesFailedDto::class, ServiceAvailabilityReviewApproveSeriesFailedDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewPresenceDto'            => [ServiceAvailabilityReviewPresenceDto::class, ServiceAvailabilityReviewPresenceDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewRejectSeriesDto'        => [ServiceAvailabilityReviewRejectSeriesDto::class, ServiceAvailabilityReviewRejectSeriesDtoNotConstructableException::class, (object) []],
            'ServicePresenceCreateSeriesDto'                  => [ServicePresenceCreateSeriesDto::class, ServicePresenceCreateSeriesDtoNotConstructableException::class, (object) []],
            'ServicePresenceCreateSeriesSkippedDto'           => [ServicePresenceCreateSeriesSkippedDto::class, ServicePresenceCreateSeriesSkippedDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewListDto'                => [ServiceAvailabilityReviewListDto::class, ServiceAvailabilityReviewListDtoNotConstructableException::class, (object) []],
            'ServiceAvailabilityReviewSlotDto'                => [ServiceAvailabilityReviewSlotDto::class, ServiceAvailabilityReviewSlotDtoNotConstructableException::class, (object) []],
            'ServiceLateWithdrawalDto'                        => [ServiceLateWithdrawalDto::class, ServiceLateWithdrawalDtoNotConstructableException::class, (object) []],
            'ServiceLateWithdrawalListDto'                    => [ServiceLateWithdrawalListDto::class, ServiceLateWithdrawalListDtoNotConstructableException::class, (object) []],
            'ServiceLateWithdrawalSellerSummaryDto'           => [ServiceLateWithdrawalSellerSummaryDto::class, ServiceLateWithdrawalSellerSummaryDtoNotConstructableException::class, (object) []],
            'ServiceLateWithdrawalSummaryDto'                 => [ServiceLateWithdrawalSummaryDto::class, ServiceLateWithdrawalSummaryDtoNotConstructableException::class, (object) []],
            'ServicePlanningSiteDto'                          => [ServicePlanningSiteDto::class, ServicePlanningSiteDtoNotConstructableException::class, (object) []],
            'ServicePresenceDto'                              => [ServicePresenceDto::class, ServicePresenceDtoNotConstructableException::class, (object) []],
            'ServicePresenceListDto'                          => [ServicePresenceListDto::class, ServicePresenceListDtoNotConstructableException::class, (object) []],
            'ServiceResourceDto'                              => [ServiceResourceDto::class, ServiceResourceDtoNotConstructableException::class, (object) []],
            'ServiceResourceUpdateDto'                        => [ServiceResourceUpdateDto::class, ServiceResourceUpdateDtoNotConstructableException::class, (object) []],
            'ServiceResourceListDto'                          => [ServiceResourceListDto::class, ServiceResourceListDtoNotConstructableException::class, (object) []],
            'ServiceSiteBoardBookingDto'                      => [ServiceSiteBoardBookingDto::class, ServiceSiteBoardBookingDtoNotConstructableException::class, (object) []],
            'ServiceSiteBoardDto'                             => [ServiceSiteBoardDto::class, ServiceSiteBoardDtoNotConstructableException::class, (object) []],
            'ServiceSiteBoardEventDto'                        => [ServiceSiteBoardEventDto::class, ServiceSiteBoardEventDtoNotConstructableException::class, (object) []],
            'ServiceSiteBoardResourceDto'                     => [ServiceSiteBoardResourceDto::class, ServiceSiteBoardResourceDtoNotConstructableException::class, (object) []],
            'ServiceSiteBoardSiteDto'                         => [ServiceSiteBoardSiteDto::class, ServiceSiteBoardSiteDtoNotConstructableException::class, (object) []],
            'ServiceSiteBoardSlotDto'                         => [ServiceSiteBoardSlotDto::class, ServiceSiteBoardSlotDtoNotConstructableException::class, (object) []],
            'ServiceSiteDto'                                  => [ServiceSiteDto::class, ServiceSiteDtoNotConstructableException::class, (object) []],
            'ServiceSiteEventDto'                             => [ServiceSiteEventDto::class, ServiceSiteEventDtoNotConstructableException::class, (object) []],
            'ServiceSiteEventCreateDto'                       => [ServiceSiteEventCreateDto::class, ServiceSiteEventCreateDtoNotConstructableException::class, (object) []],
            'ServiceSiteEventListDto'                         => [ServiceSiteEventListDto::class, ServiceSiteEventListDtoNotConstructableException::class, (object) []],
            'ServiceSiteListDto'                              => [ServiceSiteListDto::class, ServiceSiteListDtoNotConstructableException::class, (object) []],
            'ServiceSiteOpeningHourDto'                       => [ServiceSiteOpeningHourDto::class, ServiceSiteOpeningHourDtoNotConstructableException::class, (object) []],
            'ServiceSitePlanningDto'                          => [ServiceSitePlanningDto::class, ServiceSitePlanningDtoNotConstructableException::class, (object) []],
            'ProductDto'                                      => [ProductDto::class, ProductDtoNotConstructableException::class, (object) []],
            'ProductOfferDto'                                 => [ProductOfferDto::class, ProductOfferDtoNotConstructableException::class, (object) []],
            'ProductTranslationDto'                           => [ProductTranslationDto::class, ProductTranslationDtoNotConstructableException::class, (object) []],
            'TaxDto'                                          => [TaxDto::class, TaxDtoNotConstructableException::class, (object) []],
            'TaxListDto'                                      => [TaxListDto::class, TaxListDtoNotConstructableException::class, (object) []],
            'TaxTranslationDto'                               => [TaxTranslationDto::class, TaxTranslationDtoNotConstructableException::class, (object) []],
            'RoleDto'                                         => [RoleDto::class, RoleDtoNotConstructableException::class, (object) []],
            'RolePermissionDto'                               => [RolePermissionDto::class, RolePermissionDtoNotConstructableException::class, (object) []],
            'UserDto'                                         => [UserDto::class, UserDtoNotConstructableException::class, (object) []],
            'UserGroupDto'                                    => [UserGroupDto::class, UserGroupDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationCancelDto'                    => [UserGroupInvitationCancelDto::class, UserGroupInvitationCancelDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationCheckPendingDto'              => [UserGroupInvitationCheckPendingDto::class, UserGroupInvitationCheckPendingDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationDto'                          => [UserGroupInvitationDto::class, UserGroupInvitationDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationHistoryDto'                   => [UserGroupInvitationHistoryDto::class, UserGroupInvitationHistoryDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationListDto'                      => [UserGroupInvitationListDto::class, UserGroupInvitationListDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationResultDto'                    => [UserGroupInvitationResultDto::class, UserGroupInvitationResultDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationSendDto'                      => [UserGroupInvitationSendDto::class, UserGroupInvitationSendDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationStatsDto'                     => [UserGroupInvitationStatsDto::class, UserGroupInvitationStatsDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationValidateDto'                  => [UserGroupInvitationValidateDto::class, UserGroupInvitationValidateDtoNotConstructableException::class, (object) []],
        ];
    }

    private function makeFixture(array $apiData): IncludesDtoFixture
    {
        return new IncludesDtoFixture(Obj::fromArray($apiData));
    }
}
