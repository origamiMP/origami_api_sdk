<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Oauth;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Contracts\Dtos\Oauth\OauthToken as OauthTokenContract;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Oauth\OauthTokenDtoNotConstructableException;

class OauthTokenDto extends ApiResponseDto implements OauthTokenContract
{
    /**
     * Most likely, will always contain 'Bearer'.
     */
    public string $tokenType;

    public string $accessToken;

    public string $refreshToken;

    public Carbon $expiresAt;

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpiresAt(): Carbon
    {
        return $this->expiresAt;
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'token_type'    => 'tokenType',
            'access_token'  => 'accessToken',
            'refresh_token' => 'refreshToken',
            'expires_in'    => fn ($expiresIn) => $this->expiresAt = Carbon::createFromTimestamp(Carbon::now()->timestamp + $expiresIn),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OauthTokenDtoNotConstructableException($msg, previous: $previous);
    }
}
