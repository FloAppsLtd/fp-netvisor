<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class ProductBaseInformation
{
    private string $productCode;
    private string $productGroup;
    private string $name;
    private ?string $description;
    private AttributeElement $unitPrice;
    private string $isActive;
    private string $isSalesProduct;

    public function __construct(
        string $productCode,
        string $productGroup,
        string $name,
        ?string $description,
        ?float $unitPrice,
        string $isActive,
        string $isSalesProduct
    ) {
        $this->productCode = $productCode;
        $this->productGroup = $productGroup;
        $this->name = $name;
        $this->description = $description;
        $this->unitPrice = new AttributeElement($unitPrice, ['type' => 'net']);
        $this->isActive = $isActive;
        $this->isSalesProduct = $isSalesProduct;
    }
}
