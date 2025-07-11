<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\Fees;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Fees\FeeProfileListDtoNotConstructableException;

class FeeProfileListDto extends ApiResponseDto
{
    /**
     * @var Collection|FeeProfileDto[]
     */
    public Collection $data;

    public function __construct(object $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->validateAndFill();
    }

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn ($data) => $this->initData($data),
        ];
    }

    protected function validationRulesForProperties(): array
    {
        return [
            'data' => ['required', 'array'],
        ];
    }

    protected static function getDefaultNotConstructableException(string $msg, ?\Throwable $previous = null): ApiResponseDtoNotConstructableException
    {
        return new FeeProfileListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn ($item) => new FeeProfileDto((object)$item));
    }
}
