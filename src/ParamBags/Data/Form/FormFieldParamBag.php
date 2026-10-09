<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Form;

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

    /**
     * @var FormFieldTranslationParamBag[]
     */
    public array $translations;

    /**
     * @var FormFieldOptionParamBag[]
     */
    public array $options;

    protected function validationRulesForProperties(): array
    {
        return [
            'id'                => ['integer'],
            'key'               => ['string', 'max:64'],
            'type'              => ['required', 'string'],
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
