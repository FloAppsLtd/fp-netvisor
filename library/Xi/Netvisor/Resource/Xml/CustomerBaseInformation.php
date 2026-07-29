<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

class CustomerBaseInformation
{
    private ?string $externalIdentifier = null;
    private string $name;
    private string $streetAddress;
    private string $city;
    private string $postNumber;
    private string $country;
    private ?string $phonenumber = null;
    private ?string $email = null;
    private int $isprivatecustomer = 1;

    public function __construct(
        string $name,
        string $streetAddress,
        string $city,
        string $postNumber,
        string $country
    ) {
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postNumber = $postNumber;
        $this->country = $country;
    }

    public function setPhoneNumber(string $number): self
    {
        $this->phonenumber = $number;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setBusinessId(?string $id): self
    {
        $this->externalIdentifier = null;
        $this->isprivatecustomer = 1;

        if ($id) {
            $this->externalIdentifier = $id;
            $this->isprivatecustomer = 0;
        }

        return $this;
    }
}
