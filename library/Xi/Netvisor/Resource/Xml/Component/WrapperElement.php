<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml\Component;

use JMS\Serializer\Annotation\XmlKeyValuePairs;
use JMS\Serializer\Annotation\Inline;
use JMS\Serializer\Annotation\XmlRoot;

#[XmlRoot("root")]
class WrapperElement
{
    #[XmlKeyValuePairs]
    #[Inline]
    private array $value;

    /**
     * @param mixed $value
     */
    public function __construct(string $elementName, mixed $value)
    {
        $this->value = [$elementName => $value];
    }
}
