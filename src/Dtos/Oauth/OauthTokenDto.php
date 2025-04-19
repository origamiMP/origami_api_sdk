<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Oauth;

use Carbon\Carbon;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Oauth\OauthTokenDtoNotConstructableException;

class OauthTokenDto extends ApiResponseDto
{
    /**
     * Most likely, will always contain 'Bearer'.
     */
    public string $tokenType;

    public string $accessToken;

    public string $refreshToken;

    public Carbon $expiresAt;

    /**
     * @throws OauthTokenDtoNotConstructableException
     */
    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);

        $this->throwIfDataIsMissingFromApiResponse();
    }

    public static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new OauthTokenDtoNotConstructableException($msg, previous: $previous);
    }

    public function getDefaultDataStructureToProperties(): array
    {
        return [
            'token_type'    => 'tokenType',
            'access_token'  => 'accessToken',
            'refresh_token' => 'refreshToken',
            'expires_in'    => fn ($expiresIn) => $this->expiresAt = Carbon::createFromTimestamp(Carbon::now()->timestamp + $expiresIn),
        ];
    }
}
