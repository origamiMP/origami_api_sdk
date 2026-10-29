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
}
