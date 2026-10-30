<?php

namespace Tests\TestUtils;

use GuzzleHttp\Psr7\Response;
use OrigamiMp\OrigamiApiSdk\Dtos\Error\OrigamiApiErrorDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;

trait TestsErrorDtos
{
    /**
     * One error of an Origami API error response, as found in its `errors` list.
     */
    protected function mockApiErrorData(int $status, string $detail, string $code, ?string $field = null): array
    {
        $error = [
            'status' => $status,
            'detail' => $detail,
            'code'   => $code,
        ];

        if (! is_null($field)) {
            $error['data'] = ['field' => $field];
        }

        return $error;
    }

    protected function mockOrigamiApiErrorDto(int $status, string $detail, string $code, ?string $field = null): OrigamiApiErrorDto
    {
        return new OrigamiApiErrorDto(Obj::fromArray($this->mockApiErrorData($status, $detail, $code, $field)));
    }

    /**
     * An Origami API error response with status $status, holding the given $errors (built with mockApiErrorData()).
     */
    protected function mockApiErrorsResponse(int $status, array ...$errors): Response
    {
        return $this->jsonResponse(['status_code' => $status, 'errors' => $errors], $status);
    }
}
