<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Oauth;

use Illuminate\Validation\Rule;
use OrigamiMp\OrigamiApiSdk\Enums\ParamBags\Oauth\GetOauthTokenParamBagGrantTypeEnum;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

abstract class GetOauthTokenRequestParamBag extends RequestParamBag
{
    public GetOauthTokenParamBagGrantTypeEnum $grantType;

    public string $clientId;

    public string $clientSecret;

    public function __construct(
        GetOauthTokenParamBagGrantTypeEnum $grantType,
    ) {
        $this->grantType = $grantType;
    }

    protected function getJsonRequestParamsList(): array
    {
        return [
            'clientId'     => 'client_id',
            'clientSecret' => 'client_secret',
            'grantType'    => 'grant_type',
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'client_id'     => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'grant_type'    => ['required', Rule::enum(GetOauthTokenParamBagGrantTypeEnum::class)],
        ];
    }
}
