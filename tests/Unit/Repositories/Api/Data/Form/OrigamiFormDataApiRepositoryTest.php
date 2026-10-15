<?php

namespace Tests\Unit\Repositories\Api\Data\Form;

use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormListDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPageDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormPrefillSourcesDto;
use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTypesDto;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormConditionDtoOperatorEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldParamsDtoWidthEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\CreateFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\DuplicateFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormConditionParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormFieldOptionParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormFieldOptionTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormFieldParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormFieldParamsParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormFieldTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormPageParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormPageTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormSectionParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormSectionTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\FormTranslationParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\GetFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\ListFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form\UpdateFormRequestParamBag;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Form\OrigamiFormDataApiRepository;
use Tests\TestCase;

class OrigamiFormDataApiRepositoryTest extends TestCase
{
    /**
     * `GET forms` should send the filters, pagination, search and fields count params as query, and read the
     * paginated list answered.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockFormApiData(['id' => 1, 'fields_count' => 3]),
            $this->mockFormApiData(['id' => 2, 'fields_count' => 0]),
        ])));
        $paramBag = new ListFormRequestParamBag;
        $paramBag->withFilters(['is_active' => true, 'context' => 'quote']);
        $paramBag->page(2);
        $paramBag->search('quote');
        $paramBag->withCount = 'fields';

        $forms = (new OrigamiFormDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'forms');
        $this->assertLastApiRequestQueryWas([
            'filter'        => ['is_active' => '1', 'context' => 'quote'],
            'page'          => '2',
            'item_per_page' => '20',
            'search'        => 'quote',
            'with_count'    => 'fields',
        ]);
        $this->assertInstanceOf(FormListDto::class, $forms);
        $this->assertSame([1, 2], $forms->data->pluck('id')->all());
        $this->assertSame([3, 0], $forms->data->pluck('fieldsCount')->all());
    }

    /**
     * A filter not available on `GET forms` should not be sent, and the fields count should not be asked for
     * when not set.
     */
    public function test_list_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockFormApiData(),
        ])));
        $paramBag = new ListFormRequestParamBag;
        $paramBag->withFilters(['is_quote_enabled' => false, 'unknown_filter' => 'value']);

        $forms = (new OrigamiFormDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestQueryWas([
            'filter'        => ['is_quote_enabled' => '0'],
            'page'          => '1',
            'item_per_page' => '20',
        ]);
        $this->assertNull($forms->data->first()->fieldsCount);
        $this->assertNull($forms->data->first()->pages);
    }

    /**
     * `GET forms/{id}` should read the form with its whole tree (pages, sections, fields, options), conditions
     * included, from the `data` key of the response. A field without params is answered with an empty object.
     */
    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormApiData([
            'id'    => 42,
            'pages' => ['data' => [$this->mockFormPageApiData([
                'condition' => $this->mockFormConditionApiData(['field_key' => 'has_company', 'value' => 'yes']),
                'sections'  => ['data' => [$this->mockFormSectionApiData([
                    'fields' => ['data' => [
                        $this->mockFormFieldApiData(['key' => 'company_name', 'position' => 1, 'params' => (object) []]),
                        $this->mockFormFieldApiData([
                            'key'       => 'company_size',
                            'type'      => FormFieldDtoTypeEnum::SELECT->value,
                            'position'  => 2,
                            'params'    => ['width' => FormFieldParamsDtoWidthEnum::SMALL->value],
                            'condition' => $this->mockFormConditionApiData([
                                'field_key' => 'company_name',
                                'operator'  => FormConditionDtoOperatorEnum::NOT_EQUALS->value,
                                'value'     => null,
                            ]),
                            'options' => ['data' => [
                                $this->mockFormFieldOptionApiData(['value' => 'small', 'position' => 1]),
                                $this->mockFormFieldOptionApiData(['value' => 'large', 'position' => 2]),
                            ]],
                        ]),
                    ]],
                ])]],
            ])]],
        ])]));

        $form = (new OrigamiFormDataApiRepository($restClient))->get(42);

        $this->assertLastApiRequestWas('GET', 'forms/42');
        $this->assertInstanceOf(FormDto::class, $form);
        $this->assertSame(42, $form->id);
        $this->assertSame('Quote request', $form->translations->first()->name);

        $page = $form->pages->first();
        $this->assertSame('Your company', $page->translations->first()->title);
        $this->assertSame('has_company', $page->condition->fieldKey);
        $this->assertSame(FormConditionDtoOperatorEnum::EQUALS, $page->condition->operator);
        $this->assertSame('yes', $page->condition->value);

        $fields = $page->sections->first()->fields;
        $this->assertSame(['company_name', 'company_size'], $fields->pluck('key')->all());
        $this->assertNull($fields[0]->condition);
        $this->assertNull($fields[0]->params->width);

        $selectField = $fields[1];
        $this->assertSame(FormFieldDtoTypeEnum::SELECT, $selectField->type);
        $this->assertSame(FormFieldParamsDtoWidthEnum::SMALL, $selectField->params->width);
        $this->assertSame(FormConditionDtoOperatorEnum::NOT_EQUALS, $selectField->condition->operator);
        $this->assertNull($selectField->condition->value);
        $this->assertSame(['small', 'large'], $selectField->options->pluck('value')->all());
        $this->assertSame('Yes', $selectField->options->first()->translations->first()->label);
    }

    /**
     * `POST forms` should send the form as JSON, its translations included, and read the created form from the
     * `data` key of the response.
     */
    public function test_create_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormApiData(['id' => 42])]));

        $translation = new FormTranslationParamBag;
        $translation->languageId = 1;
        $translation->name = 'Quote request';
        $translation->description = null;

        $paramBag = new CreateFormRequestParamBag;
        $paramBag->isActive = true;
        $paramBag->isPurchaseEnabled = false;
        $paramBag->isQuoteEnabled = true;
        $paramBag->isStandaloneEnabled = false;
        $paramBag->translations = [$translation];

        $form = (new OrigamiFormDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'forms');
        $this->assertLastApiRequestJsonWas([
            'is_active'             => true,
            'is_purchase_enabled'   => false,
            'is_quote_enabled'      => true,
            'is_standalone_enabled' => false,
            'translations'          => [
                ['language_id' => 1, 'name' => 'Quote request', 'description' => null],
            ],
        ]);
        $this->assertInstanceOf(FormDto::class, $form);
        $this->assertSame(42, $form->id);
    }

    /**
     * `PATCH forms/{id}` should send the whole tree as JSON, nested param bags included, with enums sent as their
     * value. A node without id (to create) or without condition should be sent without these keys.
     */
    public function test_update_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        $optionTranslation = new FormFieldOptionTranslationParamBag;
        $optionTranslation->languageId = 1;
        $optionTranslation->label = 'Small';

        $option = new FormFieldOptionParamBag;
        $option->id = 30;
        $option->value = 'small';
        $option->translations = [$optionTranslation];

        $fieldTranslation = new FormFieldTranslationParamBag;
        $fieldTranslation->languageId = 1;
        $fieldTranslation->label = 'Company size';
        $fieldTranslation->hint = null;
        $fieldTranslation->placeholder = 'Choose a size';

        $fieldParams = new FormFieldParamsParamBag;
        $fieldParams->width = FormFieldParamsDtoWidthEnum::SMALL;

        $fieldCondition = new FormConditionParamBag;
        $fieldCondition->fieldKey = 'has_company';
        $fieldCondition->operator = FormConditionDtoOperatorEnum::EQUALS;
        $fieldCondition->value = 'yes';

        $field = new FormFieldParamBag;
        $field->key = 'company_size';
        $field->type = FormFieldDtoTypeEnum::SELECT;
        $field->isRequired = true;
        $field->visibleToSeller = false;
        $field->prefillSource = null;
        $field->prefillReadonly = false;
        $field->saveToCustomer = true;
        $field->params = $fieldParams;
        $field->condition = $fieldCondition;
        $field->translations = [$fieldTranslation];
        $field->options = [$option];

        $sectionTranslation = new FormSectionTranslationParamBag;
        $sectionTranslation->languageId = 1;
        $sectionTranslation->title = null;
        $sectionTranslation->description = null;

        $section = new FormSectionParamBag;
        $section->id = 20;
        $section->translations = [$sectionTranslation];
        $section->fields = [$field];

        $pageTranslation = new FormPageTranslationParamBag;
        $pageTranslation->languageId = 1;
        $pageTranslation->title = 'Your company';
        $pageTranslation->description = null;

        $page = new FormPageParamBag;
        $page->id = 10;
        $page->translations = [$pageTranslation];
        $page->sections = [$section];

        $paramBag = new UpdateFormRequestParamBag;
        $paramBag->isActive = false;
        $paramBag->pages = [$page];

        (new OrigamiFormDataApiRepository($restClient))->update(42, $paramBag);

        $this->assertLastApiRequestWas('PATCH', 'forms/42');
        $this->assertLastApiRequestJsonWas([
            'is_active' => false,
            'pages'     => [[
                'id'           => 10,
                'translations' => [
                    ['language_id' => 1, 'title' => 'Your company', 'description' => null],
                ],
                'sections' => [[
                    'id'           => 20,
                    'translations' => [
                        ['language_id' => 1, 'title' => null, 'description' => null],
                    ],
                    'fields' => [[
                        'key'               => 'company_size',
                        'type'              => 'select',
                        'is_required'       => true,
                        'visible_to_seller' => false,
                        'prefill_source'    => null,
                        'prefill_readonly'  => false,
                        'save_to_customer'  => true,
                        'params'            => ['width' => 'small'],
                        'condition'         => ['field_key' => 'has_company', 'operator' => 'equals', 'value' => 'yes'],
                        'translations'      => [
                            ['language_id' => 1, 'label' => 'Company size', 'hint' => null, 'placeholder' => 'Choose a size'],
                        ],
                        'options' => [[
                            'id'           => 30,
                            'value'        => 'small',
                            'translations' => [
                                ['language_id' => 1, 'label' => 'Small'],
                            ],
                        ]],
                    ]],
                ]],
            ]],
        ]);
    }

    public function test_delete_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->emptyResponse());

        (new OrigamiFormDataApiRepository($restClient))->delete(42);

        $this->assertLastApiRequestWas('DELETE', 'forms/42');
        $this->assertLastApiRequestHadNoBody();
    }

    /**
     * `POST forms/{id}/duplicate` should send no body, and read the copy from the `data` key of the response.
     */
    public function test_duplicate_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormApiData(['id' => 43])]));

        $form = (new OrigamiFormDataApiRepository($restClient))->duplicate(42);

        $this->assertLastApiRequestWas('POST', 'forms/42/duplicate');
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(FormDto::class, $form);
        $this->assertSame(43, $form->id);
    }

    /**
     * `GET forms/types` should read the field types and the file and length limits from the `data` key of the
     * response.
     */
    public function test_get_types_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormTypesApiData([
            'types' => [
                $this->mockFormTypeApiData(['type' => 'select']),
                $this->mockFormTypeApiData(['type' => 'file', 'has_options' => false, 'params' => ['extensions', 'max_size_kb']]),
            ],
        ])]));

        $types = (new OrigamiFormDataApiRepository($restClient))->getTypes();

        $this->assertLastApiRequestWas('GET', 'forms/types');
        $this->assertInstanceOf(FormTypesDto::class, $types);
        $this->assertSame(['select', 'file'], $types->types->pluck('type')->all());
        $this->assertSame(['extensions', 'max_size_kb'], $types->types[1]->params);
        $this->assertSame(['pdf', 'jpg'], $types->fileExtensions);
        $this->assertSame(5120, $types->fileMaxSizeKb);
        $this->assertSame(3, $types->fileMaxFiles);
        $this->assertSame(['input' => 255, 'textarea' => 5000], $types->maxLength);
        $this->assertSame(['pages' => 10, 'fields' => 100], $types->limits);
    }

    /**
     * `GET forms/prefill-sources` should read the attributes and custom fields usable to prefill a field from the
     * `data` key of the response.
     */
    public function test_get_prefill_sources_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => [
            'attributes'    => [$this->mockFormPrefillSourceApiData(['key' => 'company_name'])],
            'custom_fields' => [
                $this->mockFormPrefillSourceApiData(['source' => 'custom_field', 'key' => 'siret', 'type' => null]),
            ],
        ]]));

        $sources = (new OrigamiFormDataApiRepository($restClient))->getPrefillSources();

        $this->assertLastApiRequestWas('GET', 'forms/prefill-sources');
        $this->assertInstanceOf(FormPrefillSourcesDto::class, $sources);
        $this->assertSame(['company_name'], $sources->attributes->pluck('key')->all());
        $this->assertSame(['siret'], $sources->customFields->pluck('key')->all());
        $this->assertNull($sources->customFields->first()->type);
    }

    /**
     * `GET forms` should send the includes available on a form as query, and read the included pages of each form.
     */
    public function test_list_case_3(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([
            $this->mockFormApiData(['pages' => ['data' => [$this->mockFormPageApiData(['id' => 42])]]]),
        ])));
        $paramBag = new ListFormRequestParamBag;
        $paramBag->setIncludes(['pages', 'unknown']);

        $forms = (new OrigamiFormDataApiRepository($restClient))->list($paramBag);

        $this->assertLastApiRequestWas('GET', 'forms');
        $this->assertLastApiRequestQueryWas(['page' => '1', 'item_per_page' => '20', 'include' => ['pages']]);
        $this->assertInstanceOf(FormListDto::class, $forms);
        $this->assertInstanceOf(FormPageDto::class, $forms->data->first()->pages->first());
        $this->assertSame(42, $forms->data->first()->pages->first()->id);
    }

    /**
     * `GET forms/{id}` should send the includes available on a form as query, and read the included pages.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormApiData([
            'pages' => ['data' => [$this->mockFormPageApiData(['id' => 42])]],
        ])]));
        $paramBag = new GetFormRequestParamBag;
        $paramBag->setIncludes(['pages', 'translations', 'unknown']);

        $form = (new OrigamiFormDataApiRepository($restClient))->get(1, $paramBag);

        $this->assertLastApiRequestWas('GET', 'forms/1');
        $this->assertLastApiRequestQueryWas(['include' => ['pages', 'translations']]);
        $this->assertInstanceOf(FormDto::class, $form);
        $this->assertInstanceOf(FormPageDto::class, $form->pages->first());
        $this->assertSame(42, $form->pages->first()->id);
    }

    /**
     * `POST forms` should send the includes available on a form as query, along with the form as JSON, and read the
     * included pages.
     */
    public function test_create_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormApiData([
            'pages' => ['data' => [$this->mockFormPageApiData(['id' => 42])]],
        ])]));
        $translation = new FormTranslationParamBag;
        $translation->languageId = 1;
        $translation->name = 'Quote request';
        $paramBag = new CreateFormRequestParamBag;
        $paramBag->translations = [$translation];
        $paramBag->setIncludes(['pages', 'unknown']);

        $form = (new OrigamiFormDataApiRepository($restClient))->create($paramBag);

        $this->assertLastApiRequestWas('POST', 'forms');
        $this->assertLastApiRequestQueryWas(['include' => ['pages']]);
        $this->assertLastApiRequestJsonWas(['translations' => [['language_id' => 1, 'name' => 'Quote request']]]);
        $this->assertInstanceOf(FormDto::class, $form);
        $this->assertSame(42, $form->pages->first()->id);
    }

    /**
     * `POST forms/{id}/duplicate` should send the includes available on a form as query, without any body, and read
     * the included pages of the copy.
     */
    public function test_duplicate_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockFormApiData([
            'pages' => ['data' => [$this->mockFormPageApiData(['id' => 42])]],
        ])]));
        $paramBag = new DuplicateFormRequestParamBag;
        $paramBag->setIncludes(['pages', 'unknown']);

        $form = (new OrigamiFormDataApiRepository($restClient))->duplicate(1, $paramBag);

        $this->assertLastApiRequestWas('POST', 'forms/1/duplicate');
        $this->assertLastApiRequestQueryWas(['include' => ['pages']]);
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(FormDto::class, $form);
        $this->assertSame(42, $form->pages->first()->id);
    }

    /**
     * `GET forms` should be callable without param bag, then sending no query.
     */
    public function test_list_case_4(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse($this->mockApiPaginatedListData([$this->mockFormApiData(['id' => 42])])));

        $forms = (new OrigamiFormDataApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'forms');
        $this->assertLastApiRequestQueryWas([]);
        $this->assertInstanceOf(FormListDto::class, $forms);
        $this->assertSame(42, $forms->data->first()->id);
    }
}
