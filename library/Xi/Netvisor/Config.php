<?php

declare(strict_types=1);

namespace Xi\Netvisor;

class Config
{
    private bool $enabled;

    private string $host;

    private string $sender;

    private string $customerId;

    private string $partnerId;

    private string $language;

    private string $organizationId;

    private string $userKey;

    private string $partnerKey;

    public function __construct(
        bool $enabled,
        string $host,
        string $sender,
        string $customerId,
        string $partnerId,
        string $language,
        string $organizationId,
        string $userKey,
        string $partnerKey
    ) {
        $this->enabled = $enabled;
        $this->host = $host;
        $this->sender = $sender;
        $this->customerId = $customerId;
        $this->partnerId = $partnerId;
        $this->language = $language;
        $this->organizationId = $organizationId;
        $this->userKey = $userKey;
        $this->partnerKey = $partnerKey;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function getPartnerId(): string
    {
        return $this->partnerId;
    }

    public function getPartnerKey(): string
    {
        return $this->partnerKey;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getUserKey(): string
    {
        return $this->userKey;
    }
}


