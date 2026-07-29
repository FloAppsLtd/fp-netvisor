<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use DateTime;
use Exception;
use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class PurchaseInvoice extends Root
{
    public const string INVOICE_SOURCE_FINVOICE = 'finvoice';
    public const string INVOICE_SOURCE_MANUAL = 'manual';

    private int $invoicenumber;
    private AttributeElement $invoicedate;
    private ?string $invoicesource = null;
    private AttributeElement $valuedate;
    private AttributeElement $duedate;
    private ?string $vendorname = null;
    private ?string $vendoraddressline = null;
    private ?string $vendorpostnumber = null;
    private ?string $vendorcity = null;
    private ?string $vendorcountry = null;
    private ?string $vendorphonenumber = null;
    private ?string $vendoremail = null;
    private float $amount;
    private ?string $accountnumber = null;
    private ?string $organizationidentifier = null;
    private ?string $bankreferencenumber = null;
    private ?string $comment = null;

    #[XmlList(entry: "purchaseinvoiceline")]
    private array $purchaseinvoicelines = array();

    #[XmlList(entry: "purchaseinvoiceattachment")]
    private array $purchaseinvoiceattachments = array();

    public function __construct(
        int $invoiceNumber,
        DateTime $invoiceDate,
        DateTime $valueDate,
        DateTime $dueDate,
        int|float $amount
    ) {
        parent::__construct();

        $this->invoicenumber = $invoiceNumber;
        $this->amount = round((float) $amount, 2);

        $this->invoicedate = new AttributeElement(
            $invoiceDate->format('Y-m-d'),
            ['format' => 'ansi']
        );

        $this->valuedate = new AttributeElement(
            $valueDate->format('Y-m-d'),
            ['format' => 'ansi']
        );

        $this->duedate = new AttributeElement(
            $dueDate->format('Y-m-d'),
            ['format' => 'ansi']
        );
    }

    public function addPurchaseInvoiceLine(PurchaseInvoiceLine $line): self
    {
        $this->purchaseinvoicelines[] = $line;
        return $this;
    }

    public function addAttachment(PurchaseInvoiceAttachment $attachment): self
    {
        $this->purchaseinvoiceattachments[] = $attachment;
        return $this;
    }

    public function setVendorDetails(
        ?string $bankAccount = null,
        ?string $businessId = null,
        ?string $name = null,
        ?string $streetAddress = null,
        ?string $postNumber = null,
        ?string $city = null,
        ?string $countryCode = null,
        ?string $phone = null,
        ?string $email = null
    ): self {
        $this->accountnumber = $bankAccount ?: null;
        $this->organizationidentifier = $businessId ?: null;
        $this->vendorname = $name ? substr($name, 0, 250) : null;
        $this->vendoraddressline = $streetAddress ? substr($streetAddress, 0, 80) : null;
        $this->vendorpostnumber = $postNumber ? substr($postNumber, 0, 50) : null;
        $this->vendorcity = $city ? substr($city, 0, 50) : null;
        $this->vendorcountry = $countryCode ? substr($countryCode, 0, 2) : null;
        $this->vendorphonenumber = $phone ? substr($phone, 0, 80) : null;
        $this->vendoremail = $email ? substr($email, 0, 80) : null;

        return $this;
    }

    public function setBankReferenceNumber(string $reference): self
    {
        $this->bankreferencenumber = $reference;
        return $this;
    }

    public function setComment(string $comment): self
    {
        $this->comment = substr($comment, 0, 255);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function setInvoiceSource(string $source): self
    {
        $allowed = [
            static::INVOICE_SOURCE_FINVOICE,
            static::INVOICE_SOURCE_MANUAL,
        ];

        if (!in_array($source, $allowed)) {
            throw new Exception('Invalid invoice source: ' . $source);
        }

        $this->invoicesource = $source;
        return $this;
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('purchaseinvoice.dtd');
    }

    protected function getXmlName(): string
    {
        return 'purchaseinvoice';
    }
}
