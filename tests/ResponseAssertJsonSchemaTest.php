<?php

namespace Tests;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\AssertionFailedError;

class ResponseAssertJsonSchemaTest extends TestCase
{
    public function testFile(): void
    {
        Route::get('/foo', static function () {
            return ['foo' => 'bar'];
        });

        $this->get('/foo')->assertJsonSchema('foo.json');
    }

    public function testFileLinked(): void
    {
        Route::get('/foo', static function () {
            return ['email' => 'test@example.com'];
        });

        $this->get('/foo')->assertJsonSchema('linking.json');
    }

    public function testArray(): void
    {
        Route::get('/foo', static function () {
            return ['foo' => 'bar'];
        });

        $schema = [
            'type'       => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                ],
            ],
        ];

        $this->get('/foo')->assertJsonSchema($schema);
    }

    public function testFailure(): void
    {
        $this->expectException(AssertionFailedError::class);
        Route::get('/foo', static function () {
            return ['foo' => 123];
        });

        $this->get('/foo')->assertJsonSchema('foo.json');
    }
}
