<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class CreateSellerDocumentRequestParamBag extends DataApiRequestParamBag
{
    /**
     * Unique page encodée en base64
     *
     * @var string
     */
    public string $content;

    /**
     * Tableau de pages encodées en base64
     *
     * @var string[]
     */
    public array $pages = [];

    /**
     * ID du type de document
     */
    public int $documentTypeId;

    /**
     * Nom du fichier
     */
    public string $name;

    /**
     * ID du vendeur (requis pour opérateur/connecteur)
     */
    public ?int $userGroupId = null;

    protected function getJsonRequestParamsList(): array
    {
        return [
            'content',
            'pages',
            'documentTypeId',
            'name',
            'userGroupId',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'content'        => ['string'],
            'pages'          => ['missing_with:content', 'array', 'min:1'],
            'pages.*'        => ['required', 'string'],
            'documentTypeId' => ['required', 'integer'],
            'name'           => ['required', 'string', 'max:255'],
            'userGroupId'    => ['nullable', 'integer'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return SellerDocumentDto::class;
    }
}
