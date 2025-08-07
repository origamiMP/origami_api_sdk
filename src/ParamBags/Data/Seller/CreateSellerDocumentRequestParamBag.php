<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateSellerDocumentRequestParamBag extends RequestParamBag
{
    use HasIncludes;

    /**
     * Unique page encodée en base64
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

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludeParamsList();
    }

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
        $rules = [
            'content'        => ['string'],
            'pages'          => ['missing_with:content', 'array', 'min:1'],
            'pages.*'        => ['required', 'string'],
            'documentTypeId' => ['required', 'integer'],
            'name'           => ['required', 'string', 'max:255'],
            'userGroupId'    => ['nullable', 'integer'],
        ];

        return array_merge(
            $rules,
            $this->getIncludeValidationRules(),
        );
    }

    protected static function getRequestMainDto(): string
    {
        return SellerDocumentDto::class;
    }
}
