<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\CreateBankAccountResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class CreateBankAccountRequestParamBag extends DataApiRequestParamBag
{
    /**
     * Nom de la banque
     */
    public string $bankName = '';

    /**
     * Nom du propriétaire du compte
     */
    public string $ownerName = '';

    /**
     * Adresse du propriétaire du compte
     */
    public string $ownerAddress = '';

    /**
     * Numéro IBAN du compte bancaire
     */
    public string $iban = '';

    /**
     * Code BIC de la banque
     */
    public string $bic = '';

    /**
     * Indique si ce compte bancaire est le compte par défaut
     */
    public bool $isDefault = false;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'bankName',
            'ownerName',
            'ownerAddress',
            'iban',
            'bic',
            'isDefault',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'bankName'     => ['required', 'string', 'max:255'],
            'ownerName'    => ['required', 'string', 'max:255'],
            'ownerAddress' => ['required', 'string', 'max:500'],
            'iban'         => ['required', 'string', 'regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{4}[0-9]{7}([A-Z0-9]?){0,16}$/'],
            'bic'          => ['required', 'string', 'regex:/^[A-Z]{6}[A-Z2-9][A-NP-Z0-9]([A-Z0-9]{3})?$/'],
            'isDefault'    => ['boolean'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return CreateBankAccountResponseDto::class;
    }
} 