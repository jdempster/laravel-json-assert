<?php

namespace Tests;

use Jdempster\JsonAssert\JsonAssert;
use PHPUnit\Framework\AssertionFailedError;

class AssertJsonValueEqualsTest extends TestCase
{
    public function testAssertValue(): void
    {
        $data = [
            'id'     => 123,
            'nested' => [
                'id' => 456,
            ],
            'foo'    => [
                'bar' => ['baz' => 1],
                'bam' => ['baz' => 2],
                'boo' => ['baz' => 3],
            ],
            'name'   => 'Joe',
        ];

        JsonAssert::assertJsonValueEquals(123, 'id', $data);
        JsonAssert::assertJsonValueEquals('123', 'id', $data);
        JsonAssert::assertJsonValueEquals(456, 'nested.id', $data);
        JsonAssert::assertJsonValueEquals([1, 2, 3], 'foo.*.baz', $data);
        JsonAssert::assertJsonValueEquals('Joe', 'name', $data);
    }

    public function testAssertFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $data = [
            'id' => 123,
        ];

        JsonAssert::assertJsonValueEquals(234, 'id', $data);
    }

    public function testAssertKeyFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $data = [];

        JsonAssert::assertJsonValueEquals(234, 'id', $data);
    }

    public function testAssertEqualsCanonicalizing(): void
    {
        $data = [
            'foo' => [
                ['baz' => 1],
                ['baz' => 2],
                ['baz' => 3],
            ],
        ];

        JsonAssert::assertJsonValueEqualsCanonicalizing(
            [2, 3, 1],
            'foo.*.baz',
            $data
        );

        JsonAssert::assertJsonValueEqualsCanonicalizing(
            [3, 2, 1],
            'foo.*.baz',
            $data
        );
    }

    public function testAssertEqualsCanonicalizingFails(): void
    {
        $this->expectException(AssertionFailedError::class);
        $data = [
            'foo' => [
                ['baz' => 1],
                ['baz' => 2],
                ['baz' => 3],
            ],
        ];

        JsonAssert::assertJsonValueEqualsCanonicalizing(
            [2, 3, 1, 4],
            'foo.*.baz',
            $data
        );
    }
}
