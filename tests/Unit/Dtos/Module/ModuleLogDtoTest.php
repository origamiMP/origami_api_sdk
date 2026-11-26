<?php

namespace Tests\Unit\Dtos\Module;

use OrigamiMp\OrigamiApiSdk\Enums\Dtos\Module\ModuleLogDtoTypeEnum;
use OrigamiMp\OrigamiApiSdk\Exceptions\Dtos\Module\ModuleLogDtoNotConstructableException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Guards that `ModuleLogDto` accepts every log type the central API writes (`ModuleLogTypeEnum`). `ModuleLogDto`
 * converts it to `ModuleLogDtoTypeEnum`: a missing case makes the Dto — and the module including its logs — not
 * constructable.
 */
class ModuleLogDtoTest extends TestCase
{
    /**
     * Each log type the API writes should be read as its enum case.
     */
    #[DataProvider('typeProvider')]
    public function test_type_case_1(string $type, ModuleLogDtoTypeEnum $expected): void
    {
        $moduleLog = $this->mockModuleLogDto(['type' => $type]);

        $this->assertSame($expected, $moduleLog->type);
    }

    public static function typeProvider(): array
    {
        return [
            'info'    => ['INFO', ModuleLogDtoTypeEnum::INFO],
            'success' => ['SUCCESS', ModuleLogDtoTypeEnum::SUCCESS],
            'error'   => ['ERROR', ModuleLogDtoTypeEnum::ERROR],
        ];
    }

    /**
     * A log type the API does not write should make the log not constructable.
     */
    public function test_type_case_2(): void
    {
        $this->assertThatExceptionWasThrown(
            fn () => $this->mockModuleLogDto(['type' => 'WARNING']),
            ModuleLogDtoNotConstructableException::class,
        );
    }

    /**
     * A log written as a map should be read as an object of its keys.
     */
    public function test_log_case_1(): void
    {
        $moduleLog = $this->mockModuleLogDto(['log' => ['message' => 'Unable to reach the PSP']]);

        $this->assertEquals((object) ['message' => 'Unable to reach the PSP'], $moduleLog->log);
    }

    /**
     * A log written as a list (JSON list) should be read as an object indexed by position.
     */
    public function test_log_case_2(): void
    {
        $moduleLog = $this->mockModuleLogDto(['log' => ['Unable to reach the PSP', 'Retry later']]);

        $this->assertEquals((object) ['Unable to reach the PSP', 'Retry later'], $moduleLog->log);
    }
}
