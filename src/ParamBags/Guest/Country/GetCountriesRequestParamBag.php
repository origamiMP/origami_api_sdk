<?php

namespace OrigamiMp\OrigamiApiSdk\ParamBags\Guest\Country;

use OrigamiMp\OrigamiApiSdk\Dtos\Country\CountryListDto;
use OrigamiMp\OrigamiApiSdk\ParamBags\RequestParamBag;

class GetCountriesRequestParamBag extends RequestParamBag
{
    public bool $withoutPagination = false;

    public function setWithoutPagination(bool $withoutPagination): void
    {
        $this->withoutPagination = $withoutPagination;
    }

    protected function getQueryRequestParamsList(): array
    {
        return array_merge(
            parent::getQueryRequestParamsList(),
            ['withoutPagination'],
        );
    }

    protected static function getRequestMainDto(): string
    {
        return CountryListDto::class;
    }

    protected function asEncodableArray(?array $propertiesList = null): array
    {
        $array = parent::asEncodableArray($propertiesList);

        if ($this->withoutPagination) {
            $array['without_pagination'] = 'true';
        } else {
            unset($array['without_pagination']);
        }

        return $array;
    }
}
