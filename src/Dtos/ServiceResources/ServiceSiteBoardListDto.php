<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteBoardListDtoNotConstructableException;

class ServiceSiteBoardListDto extends ApiResponseDto
{
    /** @var Collection<int, ServiceSiteBoardDto> */
    public Collection $data;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'data' => fn (array $data) => $this->initData($data),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteBoardListDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initData(array $data): void
    {
        $this->data = collect($data)->map(fn (object $board) => new ServiceSiteBoardDto($board));
    }
}
