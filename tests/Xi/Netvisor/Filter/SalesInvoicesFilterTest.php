<?php

namespace Xi\Netvisor\Filter;

use Xi\Netvisor\Filter\SalesInvoicesFilter;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class SalesInvoicesFilterTest extends TestCase
{
    #[DataProvider('provider')]
    public function testReturnsFilterArray(bool $setId, bool $setInvoiceAboveId, int $count)
    {
        $datetime = new \DateTime('2020-02-02');
        $id = 1;

        $filter = new SalesInvoicesFilter();

        if ($setId) {
            $filter->setModifiedAfterDate($datetime);
        }

        if ($setInvoiceAboveId) {
            $filter->setGreaterThanId($id);
        }   

        $filters = $filter->getFilterArray();
        $this->assertCount($count, $filters);
    }

    public static function provider()
    {
        return [
            [
                'setId' => true,
                'setInvoiceAboveId' => true,
                'count' => 2,
            ],
            [
                'setId' => false,
                'setInvoiceAboveId' => true,
                'count' => 1,
            ],
            [
                'setId' => true,
                'setInvoiceAboveId' => false,
                'count' => 1,
            ],
            [
                'setId' => false,
                'setInvoiceAboveId' => false,
                'count' => 0,
            ],
        ];
    }

    
    
}
