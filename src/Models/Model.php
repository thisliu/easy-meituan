<?php

namespace EasyMeiTuan\Models;

use EasyMeiTuan\Traits\HasAttributes;

class Model implements \ArrayAccess, \JsonSerializable, \Serializable
{
    use HasAttributes;

    public function serialize(): string
    {
        return serialize($this->attributes);
    }

    public function unserialize($serialized)
    {
        $this->attributes = unserialize($serialized) ?: [];
    }

    public function jsonSerialize(): array
    {
        return $this->attributes;
    }
}
