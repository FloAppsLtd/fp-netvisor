<?php

declare(strict_types=1);

namespace Xi\Netvisor\Filter;

use DateTime;

class SalesInvoicesFilter
{
    private ?string $lastmodifiedstart = null;

    private ?int $invoicesabovenetvisorkey = null;

    public function getFilterArray(): array
    {
        return array_filter(get_object_vars($this));
    }

    public function setModifiedAfterDate(DateTime $date): void
    {
        $this->lastmodifiedstart = $date->format('Y-m-d');
    }

    public function setGreaterThanId(int $id): void
    {
        $this->invoicesabovenetvisorkey = $id;
    }
}
