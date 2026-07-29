<?php

namespace Xi\Netvisor\Resource\Xml;

class Recipient
{
    private ?string $organizationCode;
    private string $name;
    private ?string $address;
    private ?string $postcode;
    private ?string $town;

    public function __construct(
        ?string $businessId,
        string $name,
        ?string $address,
        ?string $postcode,
        ?string $postoffice,
    ) {
        $this->organizationCode = $businessId;
        $this->name = $name;
        $this->address = $address;
        $this->postcode = $postcode;
        $this->town = $postoffice;
    }
}
