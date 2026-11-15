<?php

namespace Tests\Unit\Dtos\Workflow;

use OrigamiMp\OrigamiApiSdk\Dtos\Workflow\StateDto;
use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

class StateDtoTest extends TestCase
{
    /**
     * The name, the action name and the description, sent until the API release 2.14, should be null once the API no
     * longer sends them.
     */
    public function test_name_case_1(): void
    {
        $data = $this->mockStateApiData(['id' => 31]);
        unset($data['name'], $data['action_name'], $data['description']);

        $state = new StateDto(Obj::fromArray($data));

        $this->assertSame(31, $state->id);
        $this->assertNull($state->name);
        $this->assertNull($state->actionName);
        $this->assertNull($state->description);
    }
}
