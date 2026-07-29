<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class SalesInvoiceProductLine
{
    public const string PRODUCT_IDENTIFIER_TYPE_CUSTOMER = 'customer';
    public const string PRODUCT_IDENTIFIER_TYPE_NETVISOR = 'netvisor';
    public const string UNIT_PRICE_TYPE_WITH_VAT = 'gross';
    public const string UNIT_PRICE_TYPE_WITHOUT_VAT = 'net';
    public const string VAT_CODE_KOMY = 'KOMY';
    public const string VAT_CODE_NONE = 'NONE';

    private AttributeElement $productIdentifier;
    private string $productName;
    private AttributeElement $productUnitPrice;
    private AttributeElement $productVatPercentage;
    private string $salesInvoiceProductLineQuantity;
    private int|float|null $salesInvoiceProductLineDiscountPercentage = null;
    private ?string $salesinvoiceproductlinefreetext = null;
    private ?int $accountingaccountsuggestion = null;

    #[XmlList(inline: true, entry: "dimension")]
    private array $dimensions = [];

    public function __construct(
        string $productIdentifier,
        string $productName,
        string $productUnitPrice,
        string $productVatPercentage,
        string $salesInvoiceProductLineQuantity
    ) {
        $this->productIdentifier = new AttributeElement(
            $productIdentifier,
            ['type' => self::PRODUCT_IDENTIFIER_TYPE_NETVISOR]
        );

        $this->productName = substr($productName, 0, 200);

        $this->productUnitPrice = new AttributeElement(
            $productUnitPrice, ['type' => self::UNIT_PRICE_TYPE_WITHOUT_VAT]
        );
        
        $this->productVatPercentage = new AttributeElement(
            $productVatPercentage, ['vatcode' => static::VAT_CODE_KOMY]
        );

        $this->salesInvoiceProductLineQuantity = $salesInvoiceProductLineQuantity;
    }

    public function addDimension(string $name, string $item): self
    {
        $this->dimensions[] = new Dimension($name, $item);
        return $this;
    }

    public function setProductIdentiefierType(string $type): self
    {
        $this->productIdentifier->setAttribute('type', $type);
        return $this;
    }

    public function setUnitPriceType(string $type): self
    {
        $this->productUnitPrice->setAttribute('type', $type);
        return $this;
    }

    public function setFreeText(string $text): self
    {
        $this->salesinvoiceproductlinefreetext = $text;
        return $this;
    }

    public function setAccountingAccount(int $account): self
    {
        $this->accountingaccountsuggestion = $account;
        return $this;
    }

    public function setVatCode(string $code): self
    {
        $this->productVatPercentage->setAttribute('vatcode', $code);
        return $this;
    }

    public function setDiscountPercentage(int|float $discountPercentage): self
    {
        $this->salesInvoiceProductLineDiscountPercentage = $discountPercentage;
        return $this;
    }
}
