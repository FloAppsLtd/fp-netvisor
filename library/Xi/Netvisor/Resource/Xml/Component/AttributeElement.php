<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml\Component;

use JMS\Serializer\Annotation\XmlAttributeMap;
use JMS\Serializer\Annotation\Inline;

class AttributeElement
{
    #[Inline]
    private mixed $value;

    #[XmlAttributeMap]
    private array $attributes;

    public function __construct(mixed $value, array $attributes)
    {
        $this->value = $value;
        $this->attributes = $attributes;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttribute(string $attribute, mixed $value): self
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }
}
