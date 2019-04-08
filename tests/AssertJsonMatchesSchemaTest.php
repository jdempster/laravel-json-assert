<?php

namespace Tests;

use Jdempster\JsonAssert\JsonAssert;
use PHPUnit\Framework\AssertionFailedError;

class AssertJsonMatchesSchemaTest extends TestCase
{
    public function testSchemaArray(): void
    {
        $data = ['foo' => 'bar'];

        $schema = [
            'type'       => 'object',
            'properties' => [
                'foo' => [
                    'type' => 'string',
                ],
            ],
        ];

        JsonAssert::assertJsonMatchesSchema($data, $schema);
    }

    public function testSchemaFile(): void
    {
        $data = ['foo' => 'bar'];

        JsonAssert::assertJsonMatchesSchema($data, 'foo.json');
    }

    public function testSchemaFileLinked(): void
    {
        $data = ['email' => 'test@example.com'];

        JsonAssert::assertJsonMatchesSchema($data, 'linking.json');
    }

    public function testSchemaObject(): void
    {
        $data = ['foo' => 'bar'];

        $schema = new \stdClass();
        $schema->type = 'object';
        $schema->properties = new \stdClass();
        $schema->properties->foo = new \stdClass();
        $schema->properties->foo->type = 'string';
        $schema->required = ['foo'];

        JsonAssert::assertJsonMatchesSchema($data, $schema);
    }

    public function testDataObject(): void
    {
        $data = new \stdClass();
        $data->foo = 'bar';
        JsonAssert::assertJsonMatchesSchema($data, 'foo.json');
    }

    public function testDataString(): void
    {
        $data = ['foo' => 'bar'];
        JsonAssert::assertJsonMatchesSchema(json_encode($data), 'foo.json');
    }

    public function testValidationFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        $data = ['foo' => 123];
        JsonAssert::assertJsonMatchesSchema(json_encode($data), 'foo.json');
    }
}
