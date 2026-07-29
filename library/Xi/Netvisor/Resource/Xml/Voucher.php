<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class Voucher extends Root
{
    public const string CALCULATION_MODE_WITHOUT_VAT = 'net';
    public const string CALCULATION_MODE_WITH_VAT = 'gross';

    private string $calculationMode;
    private AttributeElement $voucherDate;
    private ?string $number = null;
    private ?string $description = null;
    private string $voucherClass;

    #[XmlList(inline: true, entry: "voucherline")]
    private array $voucherLines = [];

    public function __construct(string $voucherClass, string $calculationMode, \DateTime $voucherDate)
    {
        parent::__construct();

        $this->voucherClass = $voucherClass;
        $this->calculationMode = $calculationMode;
        $this->voucherDate = new AttributeElement($voucherDate->format('Y-m-d'), ['format' => 'ansi']);
    }

    public function addVoucherLine(VoucherLine $line): self
    {
        $this->voucherLines[] = $line;
        return $this;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('accounting.dtd');
    }

    protected function getXmlName(): string
    {
        return 'voucher';
    }
}
