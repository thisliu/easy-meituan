<?php

declare(strict_types=1);

namespace EasyMeiTuan;

use EasyMeiTuan\Exceptions\InvalidArgumentException;
use EasyMeiTuan\Exceptions\InvalidParamsException;
use EasyMeiTuan\Traits\InteractWithHandlers;
use EasyMeiTuan\Traits\InteractWithServerRequest;
use EasyMeiTuan\Traits\ParamsDecoder;
use EasyMeiTuan\Traits\Signature;

class Server
{
    use Signature;
    use InteractWithHandlers;
    use InteractWithServerRequest;
    use ParamsDecoder;

    protected string $url;

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function __construct(public array | Config $config)
    {
        if (\is_array($this->config)) {
            $this->config = new Config($this->config);
        }
    }

    public function withUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function getUrl(): string
    {
        if ($this->url) {
            return $this->url;
        }

        throw new InvalidArgumentException('url is not set');
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    public function serve(): mixed
    {
        $response = new ServerResponse(200, [], json_encode(['data' => 'ok'], JSON_UNESCAPED_UNICODE));

        if ($this->getRequest()->getMethod() === 'GET') {
            return $response;
        }

        $content = $this->createContentFromRequest();

        try {
            return $this->handle($response, $content);
        } catch (\Exception $e) {
            return new ServerResponse(500, [], json_encode(['data' => 'error', 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidParamsException
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    protected function createContentFromRequest(): array
    {
        $originalContent = $this->getRequest()->getParsedBody();

        if (empty($originalContent)) {
            return [];
        }

        // verify signature
        if (!$this->verifySignature($this->getUrl(), $this->formatContentToValidator($originalContent))) {
            throw new InvalidParamsException('signature verification failed');
        }

        return $this->formatContentToApp($originalContent);
    }
}
