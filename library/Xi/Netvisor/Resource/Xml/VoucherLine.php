<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class VoucherLine
{
    public const string UNIT_PRICE_TYPE_WITH_VAT = 'gross';
    public const string UNIT_PRICE_TYPE_WITHOUT_VAT = 'net';
    public const string VAT_CODE_KOMY = 'KOMY';
    public const string VAT_CODE_NONE = 'NONE';

    private AttributeElement $lineSum;
    private ?string $description = null;
    private int $accountNumber;
    private AttributeElement $vatPercent;

    #[XmlList(inline: true, entry: "dimension")]
    private array $dimensions = [];

    public function __construct(int $lineSum, int $accountNumber, int $vatPercent)
    {
        $this->lineSum = new AttributeElement($lineSum, ['type' => self::UNIT_PRICE_TYPE_WITHOUT_VAT]);
        $this->accountNumber = $accountNumber;
        $this->vatPercent = new AttributeElement($vatPercent, ['vatcode' => static::VAT_CODE_KOMY]);
    }

    public function addDimension(string $name, string $item): self
    {
        $this->dimensions[] = new Dimension($name, $item);
        return $this;
    }

    public function setLineSumType(string $type): self
    {
        $this->lineSum->setAttribute('type', $type);
        return $this;
    }

    public function setVatCode(string $code): self
    {
        $this->vatPercent->setAttribute('vatcode', $code);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = substr($description, 0, 255);
        return $this;
    }
}
