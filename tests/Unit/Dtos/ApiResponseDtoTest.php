<?php

namespace Tests\Unit\Dtos;

use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuoteCostDriverDto;
use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuotePriceBuildUpRowDto;
use OrigamiMp\OrigamiApiSdk\Dtos\B2BAdvanced\QuoteResponseDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
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
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionFileDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionValueDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTranslationDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTypeDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTypesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Module\ModuleListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Oauth\OauthTokenDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Order\OrderLineDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Order\QuoteSellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\ResetPasswordDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Password\SendResetPasswordEmailDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\DocumentTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerLegalTypeListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\State\StateDto;
use OrigamiMp\OrigamiApiSdk\Dtos\State\StateLogDto;
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
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuoteCostDriverDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuotePriceBuildUpRowDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\B2BAdvanced\QuoteResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Country\CountryListDtoNotConstructableException;
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
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionFileDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormSubmissionValueDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypeDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Form\FormTypesDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Language\LanguageListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Oauth\OauthTokenDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Order\OrderLineDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Order\QuoteSellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\ResetPasswordDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Password\SendResetPasswordEmailDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\DocumentTypeDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\DocumentTypeListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerBankAccountDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDocumentDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerLegalTypeListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Seller\SellerListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\State\StateDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\State\StateLogDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxListDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Tax\TaxTranslationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RoleDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\RolePermissionDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCancelResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationCheckPendingResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationHistoryResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationListResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationResultDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationsSendResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationStatsResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\User\UserGroupInvitation\UserGroupInvitationValidateResponseDtoNotConstructableException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Guards the conventions shared by every Dto:
 * - any Dto implementing `Illuminate\Contracts\Support\Arrayable` must expose its properties as-is in `toArray()`
 *   (camelCase, matching the Dto's own PHP property names) — never converted to snake_case. This is what
 *   `ApiResponseDto::arrayFromPublicProperties()` provides to every Dto using it;
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
            'CountryDto'                         => [CountryDto::class, CountryDtoNotConstructableException::class, (object) []],
            'CountryListDto'                     => [CountryListDto::class, CountryListDtoNotConstructableException::class, (object) []],
            'CustomFieldDto'                     => [CustomFieldDto::class, CustomFieldDtoNotConstructableException::class, (object) []],
            'CustomFieldGroupDto'                => [CustomFieldGroupDto::class, CustomFieldGroupDtoNotConstructableException::class, (object) []],
            'CustomFieldGroupTranslationDto'     => [CustomFieldGroupTranslationDto::class, CustomFieldGroupTranslationDtoNotConstructableException::class, (object) []],
            'CustomFieldListDto'                 => [CustomFieldListDto::class, CustomFieldListDtoNotConstructableException::class, (object) []],
            'CustomFieldModelValueDto'           => [CustomFieldModelValueDto::class, CustomFieldModelValueDtoNotConstructableException::class, (object) []],
            'CustomFieldTranslationDto'          => [CustomFieldTranslationDto::class, CustomFieldTranslationDtoNotConstructableException::class, (object) []],
            'CustomFieldValueDto'                => [CustomFieldValueDto::class, CustomFieldValueDtoNotConstructableException::class, (object) []],
            'CustomFieldValueListDto'            => [CustomFieldValueListDto::class, CustomFieldValueListDtoNotConstructableException::class, (object) []],
            'DocumentTypeDto'                    => [DocumentTypeDto::class, DocumentTypeDtoNotConstructableException::class, (object) []],
            'DocumentTypeListDto'                => [DocumentTypeListDto::class, DocumentTypeListDtoNotConstructableException::class, (object) []],
            'FeatureFlagListDto'                 => [FeatureFlagListDto::class, FeatureFlagListDtoNotConstructableException::class, (object) ['some_flag' => 'yes']],
            'FormConditionDto'                   => [FormConditionDto::class, FormConditionDtoNotConstructableException::class, (object) []],
            'FormDto'                            => [FormDto::class, FormDtoNotConstructableException::class, (object) []],
            'FormFieldDto'                       => [FormFieldDto::class, FormFieldDtoNotConstructableException::class, (object) []],
            'FormFieldOptionDto'                 => [FormFieldOptionDto::class, FormFieldOptionDtoNotConstructableException::class, (object) []],
            'FormFieldOptionTranslationDto'      => [FormFieldOptionTranslationDto::class, FormFieldOptionTranslationDtoNotConstructableException::class, (object) []],
            'FormFieldParamsDto'                 => [FormFieldParamsDto::class, FormFieldParamsDtoNotConstructableException::class, (object) ['max_size_kb' => 'big']],
            'FormFieldTranslationDto'            => [FormFieldTranslationDto::class, FormFieldTranslationDtoNotConstructableException::class, (object) []],
            'FormListDto'                        => [FormListDto::class, FormListDtoNotConstructableException::class, (object) []],
            'FormPageDto'                        => [FormPageDto::class, FormPageDtoNotConstructableException::class, (object) []],
            'FormPageTranslationDto'             => [FormPageTranslationDto::class, FormPageTranslationDtoNotConstructableException::class, (object) []],
            'FormPrefillSourceDto'               => [FormPrefillSourceDto::class, FormPrefillSourceDtoNotConstructableException::class, (object) []],
            'FormPrefillSourcesDto'              => [FormPrefillSourcesDto::class, FormPrefillSourcesDtoNotConstructableException::class, (object) []],
            'FormSectionDto'                     => [FormSectionDto::class, FormSectionDtoNotConstructableException::class, (object) []],
            'FormSectionTranslationDto'          => [FormSectionTranslationDto::class, FormSectionTranslationDtoNotConstructableException::class, (object) []],
            'FormSubmissionDto'                  => [FormSubmissionDto::class, FormSubmissionDtoNotConstructableException::class, (object) []],
            'FormSubmissionFileDto'              => [FormSubmissionFileDto::class, FormSubmissionFileDtoNotConstructableException::class, (object) []],
            'FormSubmissionValueDto'             => [FormSubmissionValueDto::class, FormSubmissionValueDtoNotConstructableException::class, (object) []],
            'FormTranslationDto'                 => [FormTranslationDto::class, FormTranslationDtoNotConstructableException::class, (object) []],
            'FormTypeDto'                        => [FormTypeDto::class, FormTypeDtoNotConstructableException::class, (object) []],
            'FormTypesDto'                       => [FormTypesDto::class, FormTypesDtoNotConstructableException::class, (object) []],
            'LanguageDto'                        => [LanguageDto::class, LanguageDtoNotConstructableException::class, (object) []],
            'LanguageListDto'                    => [LanguageListDto::class, LanguageListDtoNotConstructableException::class, (object) []],
            'ModuleDto'                          => [ModuleDto::class, ModuleDtoNotConstructableException::class, (object) []],
            'ModuleListDto'                      => [ModuleListDto::class, ModuleListDtoNotConstructableException::class, (object) []],
            'OauthTokenDto'                      => [OauthTokenDto::class, OauthTokenDtoNotConstructableException::class, (object) []],
            'OrderLineDto'                       => [OrderLineDto::class, OrderLineDtoNotConstructableException::class, (object) []],
            'OrigamiApiErrorDto'                 => [OrigamiApiErrorDto::class, OrigamiApiErrorDtoNotConstructableException::class, (object) []],
            'OrigamiApiErrorsDto'                => [OrigamiApiErrorsDto::class, OrigamiApiErrorsDtoNotConstructableException::class, (object) []],
            'QuoteCostDriverDto'                 => [QuoteCostDriverDto::class, QuoteCostDriverDtoNotConstructableException::class, (object) []],
            'QuotePriceBuildUpRowDto'            => [QuotePriceBuildUpRowDto::class, QuotePriceBuildUpRowDtoNotConstructableException::class, (object) []],
            'QuoteResponseDto'                   => [QuoteResponseDto::class, QuoteResponseDtoNotConstructableException::class, (object) []],
            'QuoteSellerDto'                     => [QuoteSellerDto::class, QuoteSellerDtoNotConstructableException::class, (object) []],
            'ResetPasswordDto'                   => [ResetPasswordDto::class, ResetPasswordDtoNotConstructableException::class, (object) []],
            'RoleDto'                            => [RoleDto::class, RoleDtoNotConstructableException::class, (object) []],
            'RolePermissionDto'                  => [RolePermissionDto::class, RolePermissionDtoNotConstructableException::class, (object) []],
            'SellerBankAccountDto'               => [SellerBankAccountDto::class, SellerBankAccountDtoNotConstructableException::class, (object) []],
            'SellerDocumentDto'                  => [SellerDocumentDto::class, SellerDocumentDtoNotConstructableException::class, (object) []],
            'SellerDto'                          => [SellerDto::class, SellerDtoNotConstructableException::class, (object) []],
            'SellerLegalTypeListDto'             => [SellerLegalTypeListDto::class, SellerLegalTypeListDtoNotConstructableException::class, (object) []],
            'SellerListDto'                      => [SellerListDto::class, SellerListDtoNotConstructableException::class, (object) []],
            'SendResetPasswordEmailDto'          => [SendResetPasswordEmailDto::class, SendResetPasswordEmailDtoNotConstructableException::class, (object) []],
            'StateDto'                           => [StateDto::class, StateDtoNotConstructableException::class, (object) []],
            'StateLogDto'                        => [StateLogDto::class, StateLogDtoNotConstructableException::class, (object) []],
            'TaxDto'                             => [TaxDto::class, TaxDtoNotConstructableException::class, (object) []],
            'TaxListDto'                         => [TaxListDto::class, TaxListDtoNotConstructableException::class, (object) []],
            'TaxTranslationDto'                  => [TaxTranslationDto::class, TaxTranslationDtoNotConstructableException::class, (object) []],
            'UserDto'                            => [UserDto::class, UserDtoNotConstructableException::class, (object) []],
            'UserGroupDto'                       => [UserGroupDto::class, UserGroupDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationCancelDto'       => [UserGroupInvitationCancelDto::class, UserGroupInvitationCancelResponseDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationCheckPendingDto' => [UserGroupInvitationCheckPendingDto::class, UserGroupInvitationCheckPendingResponseDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationDto'             => [UserGroupInvitationDto::class, UserGroupInvitationDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationHistoryDto'      => [UserGroupInvitationHistoryDto::class, UserGroupInvitationHistoryResponseDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationListDto'         => [UserGroupInvitationListDto::class, UserGroupInvitationListResponseDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationResultDto'       => [UserGroupInvitationResultDto::class, UserGroupInvitationResultDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationSendDto'         => [UserGroupInvitationSendDto::class, UserGroupInvitationsSendResponseDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationStatsDto'        => [UserGroupInvitationStatsDto::class, UserGroupInvitationStatsResponseDtoNotConstructableException::class, (object) []],
            'UserGroupInvitationValidateDto'     => [UserGroupInvitationValidateDto::class, UserGroupInvitationValidateResponseDtoNotConstructableException::class, (object) []],
        ];
    }
}
