<?php

namespace Tests\Unit\Dtos\Workflow;

use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Workflow\StateDtoNotConstructableException;
use Tests\TestCase;

class StateDtoTest extends TestCase
{
    /**
     * A translation without name, action name or description should be read with null values.
     */
    public function test_translations_case_1(): void
    {
        $state = $this->mockStateDto(['translations' => ['data' => [
            $this->mockStateTranslationApiData(['name' => null]),
        ]]]);

        $translation = $state->translations->first();
        $this->assertNull($translation->name);
        $this->assertNull($translation->actionName);
        $this->assertNull($translation->description);
    }

    /**
     * A malformed `translations` include should make the state not constructable.
     */
    public function test_translations_case_2(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockStateDto(['translations' => ['data' => [['name' => 'Confirmed']]]]),
            StateDtoNotConstructableException::class,
        );
    }
}
