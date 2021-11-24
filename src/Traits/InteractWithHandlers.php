<?php

declare(strict_types=1);

namespace EasyMeiTuan\Traits;

use EasyMeiTuan\Exceptions\InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;

trait InteractWithHandlers
{
    protected array $handlers = [];

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function with(callable | string $handler): static
    {
        return $this->withHandler(...\func_get_args());
    }

    public function prepend(callable | string $handler): static
    {
        return $this->prependHandler(...\func_get_args());
    }

    public function without(callable | string $handler): static
    {
        return $this->withoutHandler(...\func_get_args());
    }

    public function when(mixed $value, callable | string $handler): static
    {
        if (\is_callable($value)) {
            $value = \call_user_func($value, $this);
        }

        !!$value && $this->withHandler($handler);

        return $this;
    }

    public function handle(mixed $result, mixed $payload = null): mixed
    {
        $next = $result = \is_callable($result) ? $result : fn (mixed $p): mixed => $result;

        foreach (\array_reverse($this->handlers) as $item) {
            $next = fn (mixed $p): mixed => $item['handler']($p, $next) ?? $result($p);
        }

        return $next($payload);
    }

    public function has(callable | string $handler): bool
    {
        return $this->indexOf($handler) > -1;
    }

    public function indexOf(callable | string $handler): int
    {
        foreach ($this->handlers as $index => $item) {
            if ($item['hash'] === $this->getHandlerHash($this->makeClosure($handler))) {
                return $index;
            }
        }

        return -1;
    }

    public function withHandler(callable | string $handler): static
    {
        $this->handlers[] = $this->createHandlerItem($handler);

        return $this;
    }

    public function prependHandler(callable | string $handler): static
    {
        \array_unshift($this->handlers, $this->createHandlerItem($handler));

        return $this;
    }

    #[ArrayShape(['hash' => "string", 'handler' => "callable|string"])]
    public function createHandlerItem(callable | string $handler): array
    {
        $handler = $this->makeClosure($handler);

        return [
            'hash' => $this->getHandlerHash($handler),
            'handler' => $handler,
        ];
    }

    public function withHandlers(array $handlers): static
    {
        foreach ($handlers as $handler) {
            $this->withHandler($handler);
        }

        return $this;
    }

    public function withoutHandler(callable | string $handler): static
    {
        $index = $this->indexOf($handler);

        if ($index > -1) {
            unset($this->handlers[$this->indexOf($handler)]);
        }

        return $this;
    }

    public function withoutHandlers(array $handlers = null): static
    {
        if (!is_array($handlers)) {
            $handlers = $this->handlers;
        }

        foreach ($handlers as $handler) {
            $this->withoutHandler($handler);
        }

        return $this;
    }

    protected function getHandlerHash(callable | string $handler): string
    {
        return match (true) {
            \is_string($handler) => $handler,
            \is_array($handler) => is_string($handler[0]) ? $handler[0] . '::' . $handler[1] : get_class($handler[0]) . $handler[1],
            $handler instanceof \Closure => \spl_object_hash($handler),
        };
    }

    /**
     * @throws \EasyMeiTuan\Exceptions\InvalidArgumentException
     */
    protected function makeClosure(callable | string $handler): callable | string
    {
        if (is_callable($handler)) {
            return $handler;
        }

        if (class_exists($handler) && \method_exists($handler, '__invoke')) {
            return $handler;
        }

        throw new InvalidArgumentException(sprintf('Invalid handler: %s.', $handler));
    }
}
