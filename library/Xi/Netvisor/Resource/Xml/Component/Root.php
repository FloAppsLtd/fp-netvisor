<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml\Component;

abstract class Root
{
    public function __construct()
    {
    }

    /**
     * File path to a DTD file
     * which should be used for XML validation.
     */
    abstract public function getDtdPath(): string;

    /**
     * TODO: Could this be called implicitly?
     *
     * Because Netvisor wants XML to be wrapped inside a root tag.
     */
    public function getSerializableObject(): WrapperElement|array
    {
        return new WrapperElement($this->getXmlName(), $this);
    }

    /**
     * Name of the first child element of the root, e.g. salesInvoice.
     */
    abstract protected function getXmlName(): string;

    protected function getDtdFile(string $filename): string
    {
        return __DIR__ . '/../../Dtd/' . $filename;
    }
}
