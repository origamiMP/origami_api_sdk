<?php

namespace Tests\TestUtils;

use Tests\TestCase;

trait TestsStateDtos
{
    protected function mockStateApiData(array $override = []): array
    {
        $baseData = [
            'id'          => TestCase::unique(),
            'key'         => null,
            'color'       => '#4169E1',
            'initial'     => false,
            'is_final'    => false,
            'readonly'    => false,
            'workflow_id' => TestCase::unique(),
            'text_color'  => '#FFFFFF',
            'name'        => 'Quote sent',
            'action_name' => null,
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
            'id'              => TestCase::unique(),
            'to_state'        => ['data' => $this->mockStateApiData()],
            'user_group_type' => 'seller',
            'message'         => null,
            'created_at'      => '2024-05-12 16:12:56',
        ];

        return array_merge($baseData, $override);
    }
}
