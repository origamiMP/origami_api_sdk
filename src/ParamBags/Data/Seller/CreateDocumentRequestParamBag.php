<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\CreateDocumentResponseDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\Data\DataApiRequestParamBag;

class CreateDocumentRequestParamBag extends DataApiRequestParamBag
{
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
            'pages',
            'documentTypeId',
            'name',
            'userGroupId',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'pages'          => ['required', 'array', 'min:1'],
            'pages.*'        => ['required', 'string'],
            'documentTypeId' => ['required', 'integer'],
            'name'           => ['required', 'string', 'max:255'],
            'userGroupId'    => ['nullable', 'integer'],
        ];
    }

    protected static function getRequestMainDto(): string
    {
        return CreateDocumentResponseDto::class;
    }
}
