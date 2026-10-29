<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Data\Seller;

use OrigamiMp\OrigamiApiSdk\Contracts\Traits\ParamBags\HasIncludes as HasIncludesContract;
use OrigamiMp\OrigamiApiSdk\Dtos\Seller\SellerDocumentDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;
use OrigamiMp\OrigamiApiSdk\Traits\ParamBags\HasIncludes;

class CreateSellerDocumentRequestParamBag extends RequestParamBag implements HasIncludesContract
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

    protected function getJsonRequestParamsList(): array
    {
        return [
            'content'        => 'content',
            'pages'          => 'pages',
            'documentTypeId' => 'document_type_id',
            'name'           => 'name',
            'userGroupId'    => 'user_group_id',
        ];
    }

    protected function getQueryRequestParamsList(): array
    {
        return $this->getIncludesParamsList();
    }

    protected static function getRequestMainDto(): string
    {
        return SellerDocumentDto::class;
    }
}
