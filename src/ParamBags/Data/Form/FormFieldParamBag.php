<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Form\FormFieldDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\ParamBag;

class FormFieldParamBag extends ParamBag
{
    public int $id;

    public string $key;

    public FormFieldDtoTypeEnum $type;

    public bool $isRequired;

    public bool $visibleToSeller;

    public ?string $prefillSource;

    public bool $prefillReadonly;

    public bool $saveToCustomer;

    public FormFieldParamsParamBag $params;

    public FormConditionParamBag $condition;

    /** @var array<int, FormFieldTranslationParamBag> */
    public array $translations;

    /** @var array<int, FormFieldOptionParamBag> */
    public array $options;

    protected function getPropertiesToApiKeys(): array
    {
        return [
            'id'              => 'id',
            'key'             => 'key',
            'type'            => 'type',
            'isRequired'      => 'is_required',
            'visibleToSeller' => 'visible_to_seller',
            'prefillSource'   => 'prefill_source',
            'prefillReadonly' => 'prefill_readonly',
            'saveToCustomer'  => 'save_to_customer',
            'params'          => 'params',
            'condition'       => 'condition',
            'translations'    => 'translations',
            'options'         => 'options',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                => ['integer'],
            'key'               => ['string', 'max:64'],
            'type'              => ['required', Rule::enum(FormFieldDtoTypeEnum::class)],
            'is_required'       => ['required', 'boolean'],
            'visible_to_seller' => ['required', 'boolean'],
            'prefill_source'    => ['nullable', 'string'],
            'prefill_readonly'  => ['required', 'boolean'],
            'save_to_customer'  => ['required', 'boolean'],
            'translations'      => ['required', 'array'],
            'options'           => ['array'],
        ];
    }
}
