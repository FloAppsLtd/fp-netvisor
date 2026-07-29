<?php

namespace Xi\Netvisor\Resource\Xml;

class ProductAdditionalInformation
{
    private Dimension $dimension;

    public function __construct(string $dimensionName, string $dimensionItem) {
        $this->dimension = new Dimension($dimensionName, $dimensionItem);
    }
}
