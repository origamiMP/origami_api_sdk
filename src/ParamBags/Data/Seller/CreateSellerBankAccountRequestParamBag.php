<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class CreateSellerBankAccountRequestParamBag extends RequestParamBag
{
    /**
     * Nom de la banque
     */
    public string $bankName;

    /**
     * Nom du propriétaire du compte
     */
    public string $ownerName;

    /**
     * Adresse du propriétaire du compte
     */
    public string $ownerAddress;

    /**
     * Numéro IBAN du compte bancaire
     */
    public string $iban;

    /**
     * Code BIC de la banque
     */
    public string $bic;

    /**
     * Indique si ce compte bancaire est le compte par défaut
     */
    public bool $isDefault;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'bankName'     => 'bank_name',
            'ownerName'    => 'owner_name',
            'ownerAddress' => 'owner_address',
            'iban'         => 'iban',
            'bic'          => 'bic',
            'isDefault'    => 'is_default',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'bank_name'     => ['required', 'string', 'max:255'],
            'owner_name'    => ['required', 'string', 'max:255'],
            'owner_address' => ['required', 'string', 'max:500'],
            'iban'          => ['required', 'string', 'iban'],
            'bic'           => ['required', 'string', 'bic'],
            'is_default'    => ['boolean'],
        ];
    }
}
