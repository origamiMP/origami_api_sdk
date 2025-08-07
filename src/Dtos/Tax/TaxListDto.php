<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Tax;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Taxes\TaxListDtoNotConstructableException;

class TaxListDto extends ApiResponseDto
{
    /**
     * @var Collection|TaxDto[]
     */
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['present', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new TaxListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($tax) => new TaxDto($tax));
    }
}
