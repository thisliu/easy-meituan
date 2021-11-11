<?php

namespace EasyMeiTuan;

use ArrayAccess;
use EasyMeiTuan\Exceptions\InvalidArgumentException;
use JsonSerializable;

class Config implements ArrayAccess, JsonSerializable
{
    protected array $requiredKeys = ['app_id', 'secret_id'];

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function __construct(protected array $options)
    {
        $this->checkMissingKeys();
    }

    public function get(string $key, $default = null)
    {
        $config = $this->options;

        if (is_null($key)) {
            return $config;
        }

        if (isset($config[$key])) {
            return $config[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($config) || !array_key_exists($segment, $config)) {
                return $default;
            }
            $config = $config[$segment];
        }

        return $config;
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function set(string $key, $value)
    {
        if (is_null($key)) {
            throw new InvalidArgumentException('Invalid config key.');
        }

        $keys = explode('.', $key);
        $config = &$this->options;

        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($config[$key]) || !is_array($config[$key])) {
                $config[$key] = [];
            }
            $config = &$config[$key];
        }

        $config[array_shift($keys)] = $value;

        return $config;
    }

    public function has(string $key): bool
    {
        return (bool) $this->get($key);
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function extend(array $options): Config
    {
        return new Config(\array_merge($this->options, $options));
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->options);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }

    public function jsonSerialize()
    {
        return $this->options;
    }

    public function __toString()
    {
        return \json_encode($this, \JSON_UNESCAPED_UNICODE);
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function checkMissingKeys(): bool
    {
        if (empty($this->requiredKeys)) {
            return true;
        }

        $missingKeys = [];

        foreach ($this->requiredKeys as $key) {
            if (!$this->has($key)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            throw new InvalidArgumentException(sprintf("\"%s\" cannot be empty.\r\n", \join(',', $missingKeys)));
        }

        return true;
    }
}
