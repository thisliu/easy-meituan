<?php

declare(strict_types=1);

namespace EasyMeiTuan\Support;

use EasyMeiTuan\Exceptions\InvalidParamsException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class Validator extends Factory
{
    public static function getInstance()
    {
        static $validator = null;

        if ($validator) {
            return $validator;
        }

        return new Factory(new Translator(new FileLoader(new Filesystem(), __DIR__ . '/lang'), 'zh_cn'));
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     */
    public static function verify(
        array $data,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ): bool {
        $data = \array_merge($data, $data['json'] ?? [], $data['body'] ?? [], $data['query'] ?? []);

        $validator = self::getInstance()->make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new InvalidParamsException($validator->errors()->first(), 422);
        }

        return true;
    }
}
