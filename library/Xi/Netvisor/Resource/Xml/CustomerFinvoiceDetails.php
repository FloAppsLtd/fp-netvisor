<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

class CustomerFinvoiceDetails
{
    private string $finvoiceAddress;
    private string $finvoiceRouterCode;

    public function __construct(
        string $finvoiceAddress,
        string $finvoiceRouterCode
    ) {
        $this->finvoiceAddress = $finvoiceAddress;
        $this->finvoiceRouterCode = $finvoiceRouterCode;
    }
}
