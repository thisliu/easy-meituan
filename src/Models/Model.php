<?php

namespace EasyMeiTuan\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Model
{
    public function __construct(
        public array $attributes = []
    ) {
    }

    public function __get(string $name)
    {
        if (\method_exists($this, $method = \sprintf('get%sAttribute', Str::ucfirst(Str::camel(\strtolower($name)))))) {
            return $this->$method();
        }

        return $this->getOriginal($name);
    }

    public function __set(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getOriginal(string $name)
    {
        return Arr::get($this->attributes, $name);
    }
}
