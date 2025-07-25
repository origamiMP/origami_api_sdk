<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerBankAccountDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class CreateBankAccountRequestParamBag extends DataApiRequestParamBag
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
            'iban'         => ['required', 'string', 'iban'],
            'bic'          => ['required', 'string', 'bic'],
            'isDefault'    => ['boolean'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return SellerBankAccountDto::class;
    }
}
