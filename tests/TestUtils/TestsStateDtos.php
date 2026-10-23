<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsStateDtos
{
    protected function mockStateApiData(array $override = []): array
    {
        $baseData = [
            'id'          => TestCase::unique(),
            'name'        => 'Quote sent',
            'action_name' => 'Send the quote',
            'description' => null,
        ];

        return array_merge($baseData, $override);
    }

    /**
     * The API includes `to_state` by default, in place of the id of the state.
     */
    protected function mockStateLogApiData(array $override = []): array
    {
        $baseData = [
            'id'         => TestCase::unique(),
            'created_at' => '2024-05-12 16:12:56',
            'to_state'   => ['data' => $this->mockStateApiData()],
        ];

        return array_merge($baseData, $override);
    }
}
