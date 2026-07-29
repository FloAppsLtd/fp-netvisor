<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use DateTime;
use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;

class SalesInvoice extends Root
{
    // properties order matters
    private ?string $salesInvoiceNumber;
    private string $salesInvoiceDate;
    private string $salesInvoiceReferenceNumber;
    private string $salesInvoiceAmount;
    private AttributeElement $salesInvoiceStatus;
    private string $salesInvoiceFreeTextAfterLines;
    private AttributeElement $invoicingCustomerIdentifier;
    private string $paymentTermNetDays;
    private ?AttributeElement $secondName;

    #[XmlList(entry: "invoiceline")]
    private $invoiceLines = [];

    public function __construct(
        ?string $salesInvoiceNumber,
        DateTime $salesInvoiceDate,
        string $salesInvoiceReferenceNumber,
        string $salesInvoiceAmount,
        string $salesInvoiceStatus,
        string $salesInvoiceFreeTextAfterLines,
        string $invoicingCustomerIdentifier,
        string $paymentTermNetDays,
        ?string $secondName,
    ) {
        parent::__construct();
        $this->salesInvoiceNumber = $salesInvoiceNumber;
        $this->salesInvoiceDate = $salesInvoiceDate->format('Y-m-d');
        $this->salesInvoiceReferenceNumber = $salesInvoiceReferenceNumber;
        $this->salesInvoiceAmount = $salesInvoiceAmount;
        $this->salesInvoiceStatus = new AttributeElement($salesInvoiceStatus, ['type' => 'netvisor']);
        $this->salesInvoiceFreeTextAfterLines = $salesInvoiceFreeTextAfterLines;
        $this->invoicingCustomerIdentifier = new AttributeElement($invoicingCustomerIdentifier, ['type' => 'netvisor']);
        $this->paymentTermNetDays = $paymentTermNetDays;
        if ($secondName) {
            $this->secondName = new AttributeElement($secondName, ['type' => 'customer']);
        }
    }

    /**
     * @param SalesInvoiceProductLine $line
     */
    public function addSalesInvoiceProductLine(SalesInvoiceProductLine $line): void
    {
        $this->invoiceLines[] = new WrapperElement('salesinvoiceproductline', $line);
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('salesinvoice.dtd');
    }

    protected function getXmlName(): string
    {
        return 'salesinvoice';
    }
}
