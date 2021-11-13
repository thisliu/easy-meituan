<?php

declare(strict_types=1);

namespace EasyMeiTuan;

use EasyMeiTuan\Exceptions\ServiceNotFoundException;

/**
 * @property \EasyMeiTuan\Services\Store store
 */
class Application
{
    protected Config $config;
    protected Client $client;

    public static bool $formVerify = false;

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function __construct(array | Config $config)
    {
        if (\is_array($config)) {
            $config = new Config($config);
        }

        $this->config = $config;

        if ($config->get('form_verify', false)) {
            $this->openVerify();
        }

        $this->client = new Client($this->config);
    }

    public function openVerify()
    {
        self::$formVerify = true;
    }

    public function closeVerify()
    {
        self::$formVerify = false;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\ServiceNotFoundException
     */
    public function __get($service)
    {
        if (isset($this->$service)) {
            return $this->$service;
        }

        $service = "EasyMeiTuan\Services\\" . $serviceName = \ucfirst($service);

        if (!class_exists($service)) {
            throw new ServiceNotFoundException($serviceName . '不存在');
        }

        return new $service($this->config);
    }
}
