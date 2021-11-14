<?php

declare(strict_types=1);

namespace EasyMeiTuan;

use EasyMeiTuan\Exceptions\InvalidArgumentException;
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
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->client->request($method, ltrim($url, '/'), $this->withSignature($url, $options));
    }

    public function getAppId()
    {
        return $this->config->get('app_id');
    }

    public function getSecretId()
    {
        return $this->config->get('secret_id');
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function withSignature(string $uri, array $options): array
    {
        $bodyKeys = \array_values(\array_intersect(['body', 'query', 'json'], \array_keys($options)));

        if (empty($bodyKeys)) {
            throw new InvalidArgumentException();
        }

        $bodyOptions = $options[$bodyKey = $bodyKeys[0]];

        return \array_merge($options, [$bodyKey => \array_merge($bodyOptions, ['sig' => $this->createSignature($uri, $bodyOptions)])]);
    }

    public function createSignature(string $uri, array $options): string
    {
        $options = \array_merge(['timestamp' => \time(), 'app_id' => $this->getAppId()], $options);

        unset($options['sig']);

        // 此字段不参与签名计算
        unset($options['img_data']);

        \ksort($options);

        $strOptions = \implode('&', \array_map(fn ($key, $value) => $key . '=' . $value, \array_keys($options), $options));

        return \md5(\sprintf('%s?%s%s', $uri, $strOptions, $this->getSecretId()));
    }
}
