<?php

declare(strict_types=1);

namespace EasyMeiTuan;

use EasyMeiTuan\Interfaces\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as SymfonyResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(
        public SymfonyResponseInterface $symfonyResponse
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->symfonyResponse->getStatusCode();
    }

    public function getHeaders(bool $throw = true): array
    {
        return $this->symfonyResponse->getHeaders($throw);
    }

    public function getContent(bool $throw = true): string
    {
        return $this->symfonyResponse->getContent($throw);
    }

    public function toArray(bool $throw = true): array
    {
        return $this->symfonyResponse->toArray($throw);
    }

    public function cancel(): void
    {
        $this->symfonyResponse->cancel();
    }

    public function getInfo(string $type = null)
    {
        $this->symfonyResponse->getInfo($type);
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function isSuccess(): bool
    {
        return !\array_key_exists('error', $this->toArray());
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function isError(): bool
    {
        return !$this->isSuccess();
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function getError(): array
    {
        return $this->toArray()['error'] ?? [];
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function getErrorMsg(): ?string
    {
        return $this->getError()['msg'] ?? null;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function getErrorCode(): string|int|null
    {
        return $this->getError()['code'] ?? null;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function getData(): array
    {
        return $this->toArray()['data'] ?? [];
    }
}
