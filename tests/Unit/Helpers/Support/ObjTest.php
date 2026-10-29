<?php

namespace Tests\Unit\Helpers\Support;

use OrigamiMp\OrigamiApiSdk\Helpers\Support\Obj;
use Tests\TestCase;

class ObjTest extends TestCase
{
    /**
     * An object should be converted to an array, nested objects included.
     */
    public function test_to_array_case_1(): void
    {
        $array = Obj::toArray((object) ['id' => 42, 'seller' => (object) ['name' => 'John']]);

        $this->assertSame(['id' => 42, 'seller' => ['name' => 'John']], $array);
    }

    /**
     * An associative array should be converted to an object, nested arrays included.
     */
    public function test_from_array_case_1(): void
    {
        $object = Obj::fromArray(['seller' => ['name' => 'John']]);

        $this->assertSame('John', $object->seller->name);
    }

    /**
     * A dotted key should read a nested value of an object.
     */
    public function test_get_case_1(): void
    {
        $value = Obj::get(Obj::fromArray(['seller' => ['name' => 'John']]), 'seller.name');

        $this->assertSame('John', $value);
    }

    /**
     * A property of an object set to null should give null, not the default value.
     */
    public function test_get_case_2(): void
    {
        $value = Obj::get((object) ['name' => null], 'name', 'default');

        $this->assertNull($value);
    }

    /**
     * A missing property should give the default value.
     */
    public function test_get_case_3(): void
    {
        $value = Obj::get((object) [], 'name', 'default');

        $this->assertSame('default', $value);
    }

    /**
     * A wildcard should read the key of each item, a property set to null giving null.
     */
    public function test_get_case_4(): void
    {
        $value = Obj::get(Obj::fromArray(['items' => [['name' => 'John'], ['name' => null]]]), 'items.*.name');

        $this->assertSame(['John', null], $value);
    }

    /**
     * A null key should give the target itself.
     */
    public function test_get_case_5(): void
    {
        $target = (object) ['name' => 'John'];

        $value = Obj::get($target, null);

        $this->assertSame($target, $value);
    }
}
