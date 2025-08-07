<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\ParamBags;

trait HasSearch
{
    public string $search;

    public function search(string $search): void
    {
        $this->search = $search;
    }

    protected function getSearchParamsList(): array
    {
        return ['search'];
    }

    protected function getSearchValidationRules(): array
    {
        return [
            'search' => ['string'],
        ];
    }
}
