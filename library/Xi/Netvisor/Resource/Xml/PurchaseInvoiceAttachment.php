<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

class PurchaseInvoiceAttachment
{
    private string $mimetype = 'application/pdf';
    private string $attachmentdescription;
    private string $filename;
    private string $documentdata;

    public function __construct(string $description, string $filename, string $documentdata)
    {
        $this->attachmentdescription = $description;
        $this->filename = $filename;
        $this->documentdata = base64_encode($documentdata);
    }
}
