<?php

namespace Tests\Unit\Repositories\Api\Data\Configuration;

use OrigamiMp\OrigamiApiSdk\Dtos\Configuration\ConfigurationDto;
use OrigamiMp\OrigamiApiSdk\Repositories\Api\Data\Configuration\OrigamiConfigurationDataApiRepository;
use Tests\TestCase;

class OrigamiConfigurationDataApiRepositoryTest extends TestCase
{
    /**
     * `GET configurations/{key}` should read the configuration from the `data` key, its value as the API decodes it.
     */
    public function test_get_case_1(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockConfigurationApiData([
            'key'   => 'general_nav_access',
            'value' => ['products', 'orders'],
        ])]));

        $configuration = (new OrigamiConfigurationDataApiRepository($restClient))->get('general_nav_access');

        $this->assertLastApiRequestWas('GET', 'configurations/general_nav_access');
        $this->assertLastApiRequestHadNoBody();
        $this->assertInstanceOf(ConfigurationDto::class, $configuration);
        $this->assertSame('general_nav_access', $configuration->key);
        $this->assertSame(['products', 'orders'], $configuration->value);
    }

    /**
     * A configuration without value should be read with a null value.
     */
    public function test_get_case_2(): void
    {
        $restClient = $this->mockDataRestClientReturning($this->jsonResponse(['data' => $this->mockConfigurationApiData(['value' => null])]));

        $configuration = (new OrigamiConfigurationDataApiRepository($restClient))->get('general_marketplace_name');

        $this->assertInstanceOf(ConfigurationDto::class, $configuration);
        $this->assertNull($configuration->value);
    }
}
