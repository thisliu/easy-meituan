<?php

declare(strict_types=1);

namespace EasyMeiTuan;

use EasyMeiTuan\Traits\ChainableHttpClient;
use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @method \Symfony\Contracts\HttpClient\ResponseInterface get(string|array $uri = [], array $options = [])
 * @method \Symfony\Contracts\HttpClient\ResponseInterface post(string|array $uri = [], array $options = [])
 * @method \Symfony\Contracts\HttpClient\ResponseInterface patch(string|array $uri = [], array $options = [])
 * @method \Symfony\Contracts\HttpClient\ResponseInterface put(string|array $uri = [], array $options = [])
 * @method \Symfony\Contracts\HttpClient\ResponseInterface delete(string|array $uri = [], array $options = [])
 */
class Client
{
    use DecoratorTrait;
    use ChainableHttpClient;

    public const BASE_URI = 'https://waimaiopen.meituan.com/api/v1';

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function __construct(
        public array | Config $config,
        ?HttpClientInterface $client = null,
        string $uri = self::BASE_URI,
    ) {
        $this->uri = $uri;
        $this->client = $client ?? HttpClient::create();

        \is_array($this->config) && $this->config = new Config($this->config);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->client->request($method, ltrim($url, '/'), $options);
    }

    public function getAppId()
    {
        return $this->config->get('app_id');
    }

    public function getSecretId()
    {
        return $this->config->get('secret_id');
    }
}
