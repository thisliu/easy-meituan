<?php

declare(strict_types=1);

namespace EasyMeiTuan;

use EasyMeiTuan\Interfaces\ResponseInterface;
use EasyMeiTuan\Traits\ChainableHttpClient;
use EasyMeiTuan\Traits\Signature;
use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @method \EasyMeiTuan\Interfaces\ResponseInterface get(string|array $uri = [], array $options = [])
 * @method \EasyMeiTuan\Interfaces\ResponseInterface post(string|array $uri = [], array $options = [])
 * @method \EasyMeiTuan\Interfaces\ResponseInterface patch(string|array $uri = [], array $options = [])
 * @method \EasyMeiTuan\Interfaces\ResponseInterface put(string|array $uri = [], array $options = [])
 * @method \EasyMeiTuan\Interfaces\ResponseInterface delete(string|array $uri = [], array $options = [])
 */
class Client
{
    use DecoratorTrait;
    use ChainableHttpClient;
    use Signature;

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

        if (\is_array($this->config)) {
            $this->config = new Config($this->config);
        }
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return new Response($this->client->request($method, ltrim($url, '/'), $this->withSignature($url, $options)));
    }
}
