<?php

namespace EasyMeiTuan\Traits;

use EasyMeiTuan\Exceptions\InvalidArgumentException;

trait Signature
{
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

        $bodyOptions = \array_merge($options[$bodyKey = $bodyKeys[0]], ['timestamp' => \time(), 'app_id' => $this->getAppId()]);

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

    public function verifySignature(string $uri, array $params = []): bool
    {
        return $this->createSignature($uri, $params) === ($params['sig'] ?? null);
    }
}
