<?php

namespace Tests\Unit\Repositories\Api\Guest\Language;

use OrigamiMp\OrigamiApiSdk\Dtos\Language\LanguageListDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Guest\Language\OrigamiLanguageGuestApiRepository;
use Tests\TestCase;

class OrigamiLanguageGuestApiRepositoryTest extends TestCase
{
    /**
     * `GET languages` answers a non-paginated list: each of its items should be read as a language.
     */
    public function test_list_case_1(): void
    {
        $restClient = $this->mockGuestRestClientReturning($this->jsonResponse(['data' => [
            $this->mockLanguageApiData(['locale' => 'fr']),
            $this->mockLanguageApiData(['locale' => 'en']),
        ]]));

        $languages = (new OrigamiLanguageGuestApiRepository($restClient))->list();

        $this->assertLastApiRequestWas('GET', 'languages');
        $this->assertInstanceOf(LanguageListDto::class, $languages);
        $this->assertSame(['fr', 'en'], $languages->data->pluck('locale')->all());
    }
}
