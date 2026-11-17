<?php

namespace OrigamiMp\OrigamiApiSdk\Dtos\ServiceResources;

use Illuminate\Support\Collection;
use OrigamiMp\OrigamiApiSdk\Dtos\Address\AddressDto;
use OrigamiMp\OrigamiApiSdk\Dtos\ApiResponseDto;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ApiResponseDtoNotConstructableException;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\ServiceResources\ServiceSiteDtoNotConstructableException;

class ServiceSiteDto extends ApiResponseDto
{
    public int $id;

    public string $name;

    public ?string $phone;

    public ?string $email;

    public string $timezone;

    public bool $isActive;

    public ?AddressDto $address;

    /** @var Collection<int, ServiceSiteOpeningHourDto> */
    public Collection $openingHours;

    /** @var array<int, int> */
    public array $sellerIds;

    /** @var Collection<int, ServiceResourceDto> */
    public Collection $resources;

    /** @var Collection<int, ServiceSitePlanningDto> */
    public Collection $plannings;

    protected function getDefaultDataStructureToProperties(): array
    {
        return [
            'id'            => 'id',
            'name'          => 'name',
            'phone'         => 'phone',
            'email'         => 'email',
            'timezone'      => 'timezone',
            'is_active'     => 'isActive',
            'address'       => fn (?object $address) => $this->address = is_null($address) ? null : new AddressDto($address),
            'opening_hours' => fn (array $openingHours) => $this->initOpeningHours($openingHours),
            'seller_ids'    => 'sellerIds',
            'resources'     => fn (array $resources) => $this->initResources($resources),
            'plannings'     => fn (array $plannings) => $this->initPlannings($plannings),
        ];
    }

    protected static function getDefaultNotConstructableException(
        string $msg,
        ?\Throwable $previous = null,
    ): ApiResponseDtoNotConstructableException {
        return new ServiceSiteDtoNotConstructableException($msg, previous: $previous);
    }

    protected function initOpeningHours(array $openingHours): void
    {
        $this->openingHours = collect($openingHours)->map(fn (object $openingHour) => new ServiceSiteOpeningHourDto($openingHour));
    }

    protected function initResources(array $resources): void
    {
        $this->resources = collect($resources)->map(fn (object $resource) => new ServiceResourceDto($resource));
    }

    protected function initPlannings(array $plannings): void
    {
        $this->plannings = collect($plannings)->map(fn (object $planning) => new ServiceSitePlanningDto($planning));
    }
}
