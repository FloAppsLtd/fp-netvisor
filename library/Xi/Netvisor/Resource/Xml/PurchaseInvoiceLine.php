<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class PurchaseInvoiceLine
{
    private string $productname;
    private int|float|string $deliveredamount;
    private int|float|string $unitprice;
    private int|float|string $vatpercent;
    private AttributeElement $linesum;
    private ?int $accountingsuggestion = null;

    #[XmlList(inline: true, entry: "dimension")]
    private array $dimensions = [];

    public function __construct(
        string $productName,
        int|float|string $deliveredAmount,
        int|float|string $unitPrice,
        int|float|string $vatPercent,
        int|float|string $lineSum
    ) {
        $this->productname = substr($productName, 0, 200);
        $this->deliveredamount = $deliveredAmount;
        $this->unitprice = $unitPrice;
        $this->vatpercent = $vatPercent;

        $this->linesum = new AttributeElement(
            round((float) $lineSum, 2), ['type' => 'brutto']
        );
    }

    public function addDimension(string $name, string $item): self
    {
        $this->dimensions[] = new Dimension($name, $item);
        return $this;
    }

    public function setAccountingAccount(int $account): self
    {
        $this->accountingsuggestion = $account;
        return $this;
    }
}
