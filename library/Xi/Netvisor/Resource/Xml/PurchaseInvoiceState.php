<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class PurchaseInvoiceState extends Root
{
    public const string STATUS_APPROVED = 'approved';
    public const string STATUS_ACCEPTED = 'accepted';
    public const string STATUS_OPEN_REJECTED = 'contentsupervisorrejected';
    public const string STATUS_ACCEPTED_REJECTED  = 'acceptorrejected';

    private int $purchaseinvoicenetvisorkey;
    private string $status;
    private int $isreadyforaccounting;

    public function __construct(int $netvisorId, string $status, bool $isReadyForAccounting)
    {
        parent::__construct();
        $this->purchaseinvoicenetvisorkey = $netvisorId;
        $this->status = $status;
        $this->isreadyforaccounting = (int) $isReadyForAccounting;
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('purchaseinvoicepostingdata.dtd');
    }

    protected function getXmlName(): string
    {
        return 'purchaseinvoicepostingdata';
    }
}
