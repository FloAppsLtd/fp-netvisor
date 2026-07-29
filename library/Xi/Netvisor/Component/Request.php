<?php

declare(strict_types=1);

namespace Xi\Netvisor\Component;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Xi\Netvisor\Exception\NetvisorException;
use Xi\Netvisor\Config;

class Request
{
    private Client $client;

    private Config $config;

    public function __construct(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * @throws GuzzleException
     * @throws NetvisorException
     */
    public function get(string $service, array $params = []): string
    {
        $url = $this->createUrl($service, $params);
        $headers = $this->createHeaders($url);

        $response = $this->client->request(
            'GET',
            $url,
            [
                'headers' => $headers,
            ]
        );

        if ($this->hasRequestFailed($response)) {
            throw new NetvisorException((string)$response->getBody());
        }

        return (string)$response->getBody();
    }

    /**
     * Makes a request to Netvisor and returns a response.
     *
     * @throws NetvisorException
     */
    public function post(string $xml, string $service, array $params = []): string
    {
        $url     = $this->createUrl($service, $params);
        $headers = $this->createHeaders($url);

        $response = $this->client->request(
            'POST',
            $url,
            [
                'headers' => $headers,
                'body' => $xml,
            ]
        );

        if ($this->hasRequestFailed($response)) {
            throw new NetvisorException((string)$response->getBody());
        }

        return (string)$response->getBody();
    }

    private function createUrl(string $service, array $params = []): string
    {
        $url = "{$this->config->getHost()}/{$service}.nv";

        $params = array_filter($params);
        $queryString = http_build_query($params);

        if ($queryString) {
            $url .= '?' . $queryString;
        }

        return $url;
    }

    private function createHeaders(string $url): array
    {
        $authenticationTransactionId = $this->getAuthenticationTransactionId();
        $authenticationTimestamp     = $this->getAuthenticationTimestamp();

        return array(
            'X-Netvisor-Authentication-Sender'        => $this->config->getSender(),
            'X-Netvisor-Authentication-CustomerId'    => $this->config->getCustomerId(),
            'X-Netvisor-Authentication-PartnerId'     => $this->config->getPartnerId(),
            'X-Netvisor-Authentication-Timestamp'     => $authenticationTimestamp,
            'X-Netvisor-Interface-Language'           => $this->config->getLanguage(),
            'X-Netvisor-Organisation-ID'              => $this->config->getOrganizationId(),
            'X-Netvisor-Authentication-TransactionId' => $authenticationTransactionId,
            'X-Netvisor-Authentication-MAC'           => $this->getAuthenticationMac($url, $authenticationTimestamp, $authenticationTransactionId)
        );
    }

    private function hasRequestFailed(Response $response): bool
    {
        return str_contains((string)$response->getBody(), '<Status>FAILED</Status>');
    }

    /**
     * Calculates MAC MD5-hash for headers.
     */
    private function getAuthenticationMac(string $url, string $authenticationTimestamp, string $authenticationTransactionId): string
    {
        $parameters = [
            $url,
            $this->config->getSender(),
            $this->config->getCustomerId(),
            $authenticationTimestamp,
            $this->config->getLanguage(),
            $this->config->getOrganizationId(),
            $authenticationTransactionId,
            $this->config->getUserKey(),
            $this->config->getPartnerKey(),
        ];

        return md5(implode('&', $parameters));
    }

    /**
     * Generates unique transaction ID.
     */
    private function getAuthenticationTransactionId(): string
    {
        return rand(1000, 9999) . microtime();
    }

    /**
     * Returns the current timestamp with 3-digit micro time.
     */
    private function getAuthenticationTimestamp(): string
    {
        $timestamp = DateTime::createFromFormat('U.u', (string) microtime(true));
        $timestamp->setTimezone(new \DateTimeZone('GMT'));

        return substr($timestamp->format('Y-m-d H:i:s.u'), 0, -3);
    }
}
