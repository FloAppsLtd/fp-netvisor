<?php

namespace Xi\Netvisor\Resource\Xml;

class ProductBookkeepingDetails
{
    private int $defaultVatPercentage;
    private ?int $defaultDomesticAccountNumber;

    public function __construct(
        int $defaultVatPercentage,
        ?int $defaultDomesticAccountNumber = null
    ) {
        $this->defaultVatPercentage = $defaultVatPercentage;
        $this->defaultDomesticAccountNumber = $defaultDomesticAccountNumber;
    }

    public function setDefaultDomesticAccountNumber(?int $defaultDomesticAccountNumber): static
    {
        $this->defaultDomesticAccountNumber = $defaultDomesticAccountNumber;
        return $this;
    }
}
