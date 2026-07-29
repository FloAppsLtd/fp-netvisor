<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class TestResource extends Root
{
    protected ?string $value = null;

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getDtdPath(): string
    {
        return __DIR__ . '/../Dtd/test.dtd';
    }

    protected function getXmlName(): string
    {
        return 'test';
    }
}
