<?php

namespace Tests\TestUtils;

use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionValueDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormConditionDtoOperatorEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldParamsDtoWidthEnum;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

trait TestsFormDtos
{
    /**
     * A form as listed, without its tree: add a `pages` include to get it.
     */
    protected function mockFormApiData(array $override = []): array
    {
        $baseData = [
            'id'                    => TestCase::unique(),
            'is_active'             => true,
            'is_purchase_enabled'   => false,
            'is_quote_enabled'      => true,
            'is_standalone_enabled' => false,
            'translations'          => ['data' => [$this->mockFormTranslationApiData()]],
            'created_at'            => '2024-05-12 16:12:56',
            'updated_at'            => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormTranslationApiData(array $override = []): array
    {
        $baseData = [
            'id'          => TestCase::unique(),
            'language_id' => TestCase::unique(),
            'locale'      => 'en',
            'name'        => 'Quote request',
            'description' => null,
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormPageApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'position'     => 1,
            'condition'    => null,
            'translations' => ['data' => [[
                'id'          => TestCase::unique(),
                'language_id' => TestCase::unique(),
                'locale'      => 'en',
                'title'       => 'Your company',
                'description' => null,
            ]]],
            'sections' => ['data' => []],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormSectionApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'position'     => 1,
            'condition'    => null,
            'translations' => ['data' => [[
                'id'          => TestCase::unique(),
                'language_id' => TestCase::unique(),
                'locale'      => 'en',
                'title'       => 'Contact',
                'description' => null,
            ]]],
            'fields' => ['data' => []],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormFieldApiData(array $override = []): array
    {
        $baseData = [
            'id'                => TestCase::unique(),
            'key'               => 'company_name',
            'type'              => FormFieldDtoTypeEnum::INPUT->value,
            'is_required'       => true,
            'position'          => 1,
            'params'            => ['width' => FormFieldParamsDtoWidthEnum::FULL->value],
            'visible_to_seller' => true,
            'prefill_source'    => null,
            'prefill_readonly'  => false,
            'save_to_customer'  => false,
            'condition'         => null,
            'translations'      => ['data' => [[
                'id'          => TestCase::unique(),
                'language_id' => TestCase::unique(),
                'locale'      => 'en',
                'label'       => 'Company name',
                'hint'        => null,
                'placeholder' => null,
            ]]],
            'options' => ['data' => []],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormFieldOptionApiData(array $override = []): array
    {
        $baseData = [
            'id'           => TestCase::unique(),
            'value'        => 'yes',
            'position'     => 1,
            'translations' => ['data' => [[
                'id'          => TestCase::unique(),
                'language_id' => TestCase::unique(),
                'locale'      => 'en',
                'label'       => 'Yes',
            ]]],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormConditionApiData(array $override = []): array
    {
        $baseData = [
            'field_key' => 'has_company',
            'operator'  => FormConditionDtoOperatorEnum::EQUALS->value,
            'value'     => 'yes',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormTypesApiData(array $override = []): array
    {
        $baseData = [
            'types' => [$this->mockFormTypeApiData()],
            'file'  => [
                'extensions'  => ['pdf', 'jpg'],
                'max_size_kb' => 5120,
                'max_files'   => 3,
            ],
            'max_length' => ['input' => 255, 'textarea' => 5000],
            'limits'     => ['pages' => 10, 'fields' => 100],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormTypeApiData(array $override = []): array
    {
        $baseData = [
            'type'                    => FormFieldDtoTypeEnum::SELECT->value,
            'label'                   => 'Select',
            'has_options'             => true,
            'accepts_several_options' => false,
            'can_be_prefilled'        => true,
            'params'                  => ['width'],
            'condition_operators'     => [FormConditionDtoOperatorEnum::EQUALS->value, FormConditionDtoOperatorEnum::NOT_EQUALS->value],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormPrefillSourceApiData(array $override = []): array
    {
        $baseData = [
            'source' => 'attribute',
            'label'  => 'Company name',
            'key'    => 'company_name',
            'type'   => 'string',
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormSubmissionApiData(array $override = []): array
    {
        $baseData = [
            'id'            => TestCase::unique(),
            'user_group_id' => TestCase::unique(),
            'user_id'       => TestCase::unique(),
            'form_id'       => TestCase::unique(),
            'form_name'     => 'Cleaning service',
            'context'       => 'quote',
            'language_id'   => TestCase::unique(),
            'submitted_at'  => '2024-05-12 16:12:56',
            'locked_at'     => null,
            'created_at'    => '2024-05-12 16:12:56',
            'updated_at'    => '2024-05-12 16:12:56',
            'values'        => ['data' => [$this->mockFormSubmissionValueApiData()]],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormSubmissionValueApiData(array $override = []): array
    {
        $baseData = [
            'id'                => TestCase::unique(),
            'form_field_id'     => TestCase::unique(),
            'field_key'         => 'total_area',
            'field_type'        => FormFieldDtoTypeEnum::NUMBER->value,
            'page_title'        => 'Your site',
            'section_title'     => 'Scope',
            'label'             => 'Total area',
            'hint'              => null,
            'value'             => 2500,
            'display_value'     => '2500 m²',
            'visible_to_seller' => true,
            'position'          => 1,
            'files'             => [],
        ];

        return array_merge($baseData, $override);
    }

    protected function mockFormSubmissionValueDto(array $override = []): FormSubmissionValueDto
    {
        return new FormSubmissionValueDto(Obj::fromArray($this->mockFormSubmissionValueApiData($override)));
    }

    protected function mockFormSubmissionFileApiData(array $override = []): array
    {
        $baseData = [
            'name'         => 'store-plan.pdf',
            'format'       => 'pdf',
            'size'         => 20480,
            'download_url' => 'http://api.test/v1/forms/submissions/42/files/store_plan/0',
        ];

        return array_merge($baseData, $override);
    }
}
