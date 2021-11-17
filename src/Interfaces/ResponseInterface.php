<?php

declare(strict_types=1);

namespace EasyMeiTuan\Interfaces;

interface ResponseInterface extends \Symfony\Contracts\HttpClient\ResponseInterface
{
    public function isSuccess(): bool;
    public function isError(): bool;
    public function getError(): ?array;
    public function getErrorMsg(): ?string;
    public function getErrorCode(): string|int|null;
    public function getData(): ?array;
}
