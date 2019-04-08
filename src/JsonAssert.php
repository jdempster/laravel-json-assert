<?php

namespace Jdempster\JsonAssert;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Opis\JsonSchema\Schema;
use Opis\JsonSchema\Validator;
use PHPUnit\Framework\Assert;

final class JsonAssert
{
    public static function assertJsonMatchesSchema($data, $schema): void
    {
        if (is_array($data)) {
            $data = json_decode(json_encode($data));
        } else {
            if (is_string($data)) {
                $data = json_decode($data);
            }
        }

        $loader = new \Opis\JsonSchema\Loaders\File('', [
            Config::get('json-schema.schema_base_path'),
        ]);

        if (is_array($schema) || is_object($schema)) {
            $schema = Schema::fromJsonString(json_encode($schema));
        } else {
            $schema = $loader->loadSchema(Str::start($schema, '/'));
        }

        $validator = new Validator();
        $validator->setLoader($loader);
        $result = $validator->schemaValidation($data, $schema);
        if (!$result->isValid()) {
            $error = $result->getFirstError();

            $keywordArgs = [];
            foreach ($error->keywordArgs() as $key => $value) {
                $keywordArgs[] = "$key $value";
            }

            $message = sprintf(
                'Error on keyword %s, %s, input data was %s at %s',
                $error->keyword(),
                implode(' ', $keywordArgs),
                json_encode($error->data(), JSON_PRETTY_PRINT),
                implode('.', $error->dataPointer())
            );

            Assert::fail($message);
        }

        Assert::assertTrue(true);
    }

    public static function assertJsonValueEquals($expected, $expression, $data): void
    {
        if (is_string($data)) {
            $data = json_decode($data);
        }

        $actual = data_get($data, $expression);
        Assert::assertEquals($expected, $actual);
    }

    public static function assertJsonValueEqualsCanonicalizing($expected, $expression, $data): void
    {
        if (is_string($data)) {
            $data = json_decode($data);
        }

        $actual = data_get($data, $expression);
        Assert::assertEqualsCanonicalizing($expected, $actual);
    }
}
