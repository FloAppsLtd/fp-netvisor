<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class Product extends Root
{
    private ProductBaseInformation $productBaseInformation;
    private ?ProductBookkeepingDetails $productBookkeepingDetails;
    private ?ProductAdditionalInformation $productAdditionalInformation;

    public function __construct(
        ProductBaseInformation $productBaseInformation,
        ?ProductBookkeepingDetails $productBookkeepingDetails = null,
        ?ProductAdditionalInformation $productAdditionalInformation = null
    ) {
        parent::__construct();
        $this->productBaseInformation = $productBaseInformation;
        $this->productBookkeepingDetails = $productBookkeepingDetails;
        $this->productAdditionalInformation = $productAdditionalInformation;
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('product.dtd');
    }

    protected function getXmlName(): string
    {
        return 'product';
    }
}
