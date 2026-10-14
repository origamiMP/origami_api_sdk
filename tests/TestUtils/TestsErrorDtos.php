<?php

namespace Tests\TestUtils;

use GuzzleHttp\Psr7\Response;

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

    /**
     * An Origami API error response with status $status, holding the given $errors (built with mockApiErrorData()).
     */
    protected function mockApiErrorsResponse(int $status, array ...$errors): Response
    {
        return $this->jsonResponse(['status_code' => $status, 'errors' => $errors], $status);
    }
}
