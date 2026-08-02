<?php

namespace OrigamiMp\OrigamiApiSdk\Traits\ParamBags;

trait HasSearch
{
    private string $search;

    public function search(string $search): void
    {
        $this->search = $search;
    }

    public function getSearch(): string
    {
        return $this->search;
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
