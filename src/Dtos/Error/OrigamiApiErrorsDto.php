<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Error;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Error\OrigamiApiErrorsDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Traits\HasCorrespondingException;

class OrigamiApiErrorsDto extends ApiResponseDto
{
    use HasCorrespondingException;

    public int $statusCode;

    /**
     * @var OrigamiApiErrorDto[]|Collection
     */
    public Collection $errors;

    /**
     * @throws OrigamiApiErrorsDtoNotConstructableException
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
        return new OrigamiApiErrorsDtoNotConstructableException($msg, previous: $previous);
    }

    public function getDefaultDataStructureToProperties(): array
    {
        return [
            'status_code'    => 'statusCode',
            'errors'         => fn ($errors) => $this->errors = collect($errors)->map(fn ($error) => new OrigamiApiErrorDto($error)),
        ];
    }

    public function throwCorrespondingException(): void
    {
        if ($this->errors->count() === 1) {
            $this->errors[0]->throwCorrespondingException();
        }

        // TODO handle multiple errors
    }
}
