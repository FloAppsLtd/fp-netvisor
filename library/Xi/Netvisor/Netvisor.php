<?php
namespace Xi\Netvisor;

use DateTime;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use SimpleXMLElement;
use Xi\Netvisor\Component\Request;
use Xi\Netvisor\Exception\NetvisorException;
use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Resource\Xml\Component\Root;
use JMS\Serializer\Serializer;
use Xi\Netvisor\Filter\SalesInvoicesFilter;
use Xi\Netvisor\Resource\Xml\Customer;
use Xi\Netvisor\Resource\Xml\SalesInvoice;
use Xi\Netvisor\Resource\Xml\PurchaseInvoice;
use Xi\Netvisor\Resource\Xml\PurchaseInvoiceState;
use Xi\Netvisor\Resource\Xml\Voucher;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;

/**
 * Connects to Netvisor-interface via HTTP.
 * Authentication is based on HTTP headers.
 * A single XML file is sent to the server.
 * The server returns a XML response that contains the transaction status.
 *
 * @category Xi
 * @package  Netvisor
 * @author   Panu Leppäniemi <me@panuleppaniemi.com>
 * @author   Henri Vesala    <henri.vesala@gmail.fi>
 * @author   Petri Koivula   <petri.koivula@iki.fi>
 * @author   Artur Gajewski  <info@arturgajewski.com>
 */
class Netvisor
{
    private Config $config;

    private Client $client;

    private Validate $validate;

    private Serializer $serializer;

    /**
     * Initialize with Netvisor::build()
     */
    public function __construct(
        Client $client,
        Config $config,
        Validate $validate
    ) {
        $this->client     = $client;
        $this->config     = $config;
        $this->validate   = $validate;
        $this->serializer = $this->createSerializer();
    }

    /**
     * Builds a default instance of this class.
     */
    public static function build(Config $config): Netvisor
    {
        return new Netvisor(new Client(), $config, new Validate());
    }

    public function sendInvoice(SalesInvoice $invoice, ?string $language = null): ?string
    {
        return $this->requestWithBody($invoice, 'salesinvoice', [], $language);
    }

    public function sendCustomer(Customer $customer): ?string
    {
        return $this->requestWithBody($customer, 'customer', ['method' => 'add']);
    }

    public function sendVoucher(Voucher $voucher): ?string
    {
        return $this->requestWithBody($voucher, 'accounting');
    }

    public function sendPurchaseInvoice(PurchaseInvoice $invoice): ?string
    {
        return $this->requestWithBody($invoice, 'purchaseinvoice');
    }

    public function updatePurchaseInvoiceState(PurchaseInvoiceState $state): ?string
    {
        return $this->requestWithBody($state, 'purchaseinvoicepostingdata');
    }

    public function updateCustomer(Customer $customer, int $id): ?string
    {
        return $this->requestWithBody(
            $customer,
            'customer',
            [
                'method' => 'edit',
                'id' => $id,
            ]
        );
    }

    public function updateInvoice(SalesInvoice $invoice, int $id): ?string
    {
        return $this->requestWithBody(
            $invoice,
            'salesinvoice',
            [
                'method' => 'edit',
                'id' => $id,
            ]
        );
    }

    /**
     * List customers, optionally filtered by a keyword.
     *
     * The keyword matches Netvisor fields
     * Name, Customer Code, Organization identifier, CoName
     */
    public function getCustomers(?string $keyword = null): ?string
    {
        return $this->get(
            'customerlist',
            [
                'keyword' => $keyword,
            ]
        );
    }

    /**
     * List customers that have changed since given date.
     *
     * Giving a keyword would override the changed since parameter.
     */
    public function getCustomersChangedSince(DateTime $changedSince): ?string
    {
        return $this->get(
            'customerlist',
            [
                'changedsince' => $changedSince->format('Y-m-d'),
            ]
        );
    }

    /**
     * Get details for a product identified by Netvisor id.
     */
    public function getProduct(int $id): ?string
    {
        return $this->get(
            'getproduct',
            [
                'id' => $id,
            ]
        );
    }

    /**
     * Get details for a invoice identified by Netvisor id.
     */
    public function getSalesInvoice(int $id): ?string
    {
        return $this->get(
            'getsalesinvoice',
            [
                'netvisorkey' => $id,
            ]
        );
    }

    /**
     * Get sales invoices by filters
     */
    public function getSalesInvoices(SalesInvoicesFilter $salesInvoicesFilter): ?string
    {
        return $this->get(
            'salesinvoicelist',
            $salesInvoicesFilter->getFilterArray() 
        );
    }

    /**
     * Get details for a invoices identified by Netvisor id.
     */
    public function getPurchaseInvoice(int $id): ?string
    {
        return $this->get(
            'getpurchaseinvoice',
            [
                'netvisorkey' => $id,
            ]
        );
    }

    /**
     * Get vouchers by timeframe
     */
    public function getVouchers(\DateTime $startDate, \DateTime $endDate): ?string
    {
        return $this->get(
            'accountingledger',
            [
                'startdate' => $startDate->format('Y-m-d'),
                'enddate' => $endDate->format('Y-m-d'),
            ]
        );
    }

    /**
     * Get details for a certain voucher from timeframe identified by Netvisor id.
     */
    public function getVoucher(int $id, \DateTime $startDate, \DateTime $endDate): ?string
    {
        $response = new SimpleXMLElement($this->getVouchers($startDate, $endDate));
        
        foreach ($response->Vouchers->children() as $voucher) {
            if ((int) $voucher->NetvisorKey === $id) {
                return $voucher->asXml();
            }
        }

        return null;
    }

    protected function get(string $service, array $params = []): ?string
    {
        if (!$this->config->isEnabled()) {
            return null;
        }

        $request = new Request($this->client, $this->config);

        return $request->get($service, $params);
    }

    /**
     * @throws NetvisorException
     */
    public function requestWithBody(Root $root, string $service, array $params = [], ?string $language = null): ?string
    {
        if (!$this->config->isEnabled()) {
            return null;
        }

        $xml = $this->serializer->serialize($root->getSerializableObject(), 'xml');

        if (!$this->validate->isValid($xml, $root->getDtdPath())) {
            throw new NetvisorException('XML is not valid according to DTD');
        }

        if ($language !== null) {
            $this->config->setLanguage($language);
        }

        $request = new Request($this->client, $this->config);

        return $request->post($this->processXml($xml), $service, $params);
    }

    private function createSerializer(): Serializer
    {
        $builder = SerializerBuilder::create();
        $builder->setPropertyNamingStrategy(new LowercaseNamingStrategy());

        return $builder->build();
    }

    /**
     * Process given XML into Netvisor specific format
     */
    public function processXml(string $xml): string
    {
        return str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n", '', $xml);
    }
}
