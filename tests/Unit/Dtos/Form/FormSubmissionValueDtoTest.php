<?php

namespace Tests\Unit\Dtos\Form;

use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormSubmissionFileDto;
use Tests\TestCase;

class FormSubmissionValueDtoTest extends TestCase
{
    /**
     * A file answer should have no value, and its files listed.
     */
    public function test_files_case_1(): void
    {
        $value = $this->mockFormSubmissionValueDto([
            'value'         => null,
            'display_value' => 'store-plan.pdf',
            'files'         => [$this->mockFormSubmissionFileApiData()],
        ]);

        $this->assertNull($value->value);
        $this->assertContainsOnlyInstancesOf(FormSubmissionFileDto::class, $value->files);
        $this->assertSame(['store-plan.pdf'], $value->files->pluck('name')->all());
    }

    /**
     * Any other answer should have its value and no file.
     */
    public function test_files_case_2(): void
    {
        $value = $this->mockFormSubmissionValueDto();

        $this->assertSame(2500, $value->value);
        $this->assertTrue($value->files->isEmpty());
    }
}
