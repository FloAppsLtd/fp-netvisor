<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class Customer extends Root
{
    private CustomerBaseInformation $customerBaseInformation;
    private ?CustomerFinvoiceDetails $customerFinvoiceDetails;

    public function __construct(
        CustomerBaseInformation $customerBaseInformation,
        ?CustomerFinvoiceDetails $customerFinvoiceDetails = null
    ) {
        parent::__construct();
        
        $this->customerBaseInformation = $customerBaseInformation;
        $this->customerFinvoiceDetails = $customerFinvoiceDetails;
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('customer.dtd');
    }

    protected function getXmlName(): string
    {
        return 'customer';
    }
}
