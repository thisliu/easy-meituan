<?php

namespace EasyMeiTuan\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasAttributes
{
    protected array $attributes = [];

    public function getAttributes(): array
    {
        return \array_combine($keys = \array_keys($this->attributes), \array_map('getAttribute', $keys));
    }

    public function getAttribute(string $name, $default = null)
    {
        if (\method_exists($this, $method = \sprintf('get%sAttribute', Str::ucfirst(Str::camel(\strtolower($name)))))) {
            return $this->$method();
        }

        return $this->getOriginal($name, $default);
    }

    public function setAttribute(string $name, $value): static
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getOriginal(string $name, $default = null)
    {
        return Arr::get($this->attributes, $name, $default);
    }

    public function merge(array $attributes): static
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->attributes);
    }

    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    public function __get($property)
    {
        return $this->getAttribute($property);
    }

    public function toArray(): array
    {
        return $this->getAttributes();
    }

    public function toJSON(): string
    {
        return \json_encode($this->getAttributes(), JSON_UNESCAPED_UNICODE);
    }
}
