<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class PaymentLine
{
    private $LineNumber;
    private $OriginatingON;
    private $InvoiceDate;
    private $SettlementAmount;
    private $CreditAmount;

    public function __construct($LineNumber, $OriginatingON, $InvoiceDate, $SettlementAmount, $CreditAmount)
    {
        $this->LineNumber = $LineNumber;
        $this->OriginatingON = $OriginatingON;
        $this->InvoiceDate = $InvoiceDate;
        $this->SettlementAmount = $SettlementAmount;
        $this->CreditAmount = $CreditAmount;
    }


    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('Line');

        $xmlWriter->startElement('LineNumber');
        $xmlWriter->text($this->LineNumber);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SourceDocumentID');
            $xmlWriter->startElement('OriginatingON');
            $xmlWriter->text($this->OriginatingON);
            $xmlWriter->endElement();
            $xmlWriter->startElement('InvoiceDate');
            $xmlWriter->text($this->InvoiceDate);
            $xmlWriter->endElement();
        $xmlWriter->endElement();//end SourceDocumentID

        $xmlWriter->startElement('SettlementAmount');
        $xmlWriter->text($this->SettlementAmount);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CreditAmount');
        $xmlWriter->text($this->CreditAmount);
        $xmlWriter->endElement();

        $xmlWriter->endElement();//End Line
        $xmlWriter->endDocument();
        return $xmlWriter->outputMemory(true);

    }

}
