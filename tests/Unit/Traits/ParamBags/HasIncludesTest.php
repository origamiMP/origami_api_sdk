<?php

namespace Tests\Unit\Traits\ParamBags;

use OrigamiMp\OrigamiApiSdk\Dtos\Form\FormTranslationDto;
use Tests\Fixtures\IncludesParamBagFixture;
use Tests\TestCase;

/**
 * Behavior shared by every param bag with includes, tested through a fixture (which adds nothing to it).
 */
class HasIncludesTest extends TestCase
{
    /**
     * Only the includes available on the Dto returned by the request should be kept, nested ones included.
     */
    public function test_set_includes_case_1(): void
    {
        $paramBag = new IncludesParamBagFixture;

        $paramBag->setIncludes(['children', 'unknown', 'children.parent', 'children.unknown', 'translation.anything']);

        $this->assertSame(['children', 'children.parent'], $paramBag->getIncludes());
    }

    /**
     * An include asked several times should be kept once, the includes staying a list.
     */
    public function test_set_includes_case_2(): void
    {
        $paramBag = new IncludesParamBagFixture;

        $paramBag->setIncludes(['children', 'children', 'unknown', 'parent']);

        $this->assertSame(['children', 'parent'], $paramBag->getIncludes());
    }

    /**
     * Setting includes again should replace the previous ones, not add to them.
     */
    public function test_set_includes_case_3(): void
    {
        $paramBag = new IncludesParamBagFixture;
        $paramBag->setIncludes(['children']);

        $paramBag->setIncludes(['parent']);

        $this->assertSame(['parent'], $paramBag->getIncludes());
    }

    /**
     * When the Dto returned by the request declares no includes, every include should be ignored.
     */
    public function test_set_includes_case_4(): void
    {
        $paramBag = new class extends IncludesParamBagFixture
        {
            protected static function getRequestMainDto(): string
            {
                return FormTranslationDto::class;
            }
        };

        $paramBag->setIncludes(['children']);

        $this->assertSame([], $paramBag->getIncludes());
    }

    public function test_get_includes_case_1(): void
    {
        $paramBag = new IncludesParamBagFixture;

        $this->assertSame([], $paramBag->getIncludes());
    }

    /**
     * The includes should be sent as an `include` query param.
     */
    public function test_query_case_1(): void
    {
        $paramBag = new IncludesParamBagFixture;
        $paramBag->setIncludes(['children', 'children.parent']);

        $guzzleParams = $paramBag->asGuzzleParams();

        $this->assertSame(['include' => ['children', 'children.parent']], $guzzleParams['query']);
    }
}
