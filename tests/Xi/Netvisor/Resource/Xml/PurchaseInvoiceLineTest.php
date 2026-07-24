<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\PurchaseInvoiceLine;
use Xi\Netvisor\XmlTestCase;
use PHPUnit\Framework\Attributes\Test;

class PurchaseInvoiceLineTest extends XmlTestCase
{
    /**
     * @var PurchaseInvoiceLine
     */
    private $invoiceLine;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoiceLine = new PurchaseInvoiceLine(
            '100',
            'Name',
            '1,23',
            '24',
            '5'
        );
    }

    #[Test]
    public function xmlHasRequiredLineValues()
    {
        $name = 'Product name, which is longer than the limit of 200 characters Will add some lirum larum. Will add some lirum larum. Will add some lirum larum. Will add some lirum larum. Will add some lirum larum. Will add some lirum larum.';
        $amount = 2;
        $unitPrice = 10;
        $vatPercent = 24;
        $lineSum = 100.456;

        $xml = $this->toXml(
            new PurchaseInvoiceLine(
                $name,
                $amount,
                $unitPrice,
                $vatPercent,
                $lineSum
            )
        );

        $this->assertXmlContainsTagWithValue('productname', substr($name, 0, 200), $xml);
        $this->assertStringNotContainsString($name, $xml);

        $this->assertXmlContainsTagWithValue('deliveredamount', $amount, $xml);
        $this->assertXmlContainsTagWithValue('unitprice', $unitPrice, $xml);
        $this->assertXmlContainsTagWithValue('vatpercent', $vatPercent, $xml);
        
        $this->assertXmlContainsTagWithValue('linesum', round($lineSum, 2), $xml);
        $this->assertStringNotContainsString((string) $lineSum, $xml);
        $this->assertXmlContainsTagWithAttributes('linesum', array('type' => 'brutto'), $xml);
    }

    #[Test]
    public function xmlHasAddedDimensionLines()
    {
        $name = 'Test dimension name';
        $item = 'Test dimension item';
        $name2 = 'Another test dimension name';
        $item2 = 'Another test dimension item';

        $this->invoiceLine->addDimension($name, $item);
        $this->invoiceLine->addDimension($name2, $item2);

        $xml = $this->toXml($this->invoiceLine);

        $this->assertSame(2, substr_count($xml, '<dimensionname>'));
        $this->assertStringContainsString($name, $xml);
        $this->assertStringContainsString($item, $xml);
        $this->assertStringContainsString($name2, $xml);
        $this->assertStringContainsString($item, $xml);
    }

    public function testSetAccountingAccount()
    {
        $account = 3000;
        $this->invoiceLine->setAccountingAccount($account);

        $xml = $this->toXml($this->invoiceLine);

        $this->assertXmlContainsTagWithValue('accountingsuggestion', $account, $xml);
    }
}
