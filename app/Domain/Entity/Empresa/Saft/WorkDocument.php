<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class WorkDocument
{
    private $DocumentNumber;
    private $WorkStatus;
    private $WorkStatusDate;
    private $SourceID;
    private $SourceBilling;
    private $Hash;
    private $HashControl;
    private $Period;
    private $WorkDate;
    private $WorkType;
    private $SystemEntryDate;
    private $TransactionID;
    private $CustomerID;

    private $WorkLines = [];
    private $TaxPayable;
    private $NetTotal;
    private $GrossTotal;


    public function __construct($DocumentNumber, $WorkStatus, $WorkStatusDate, $SourceID, $SourceBilling, $Hash, $HashControl, $Period, $WorkDate, $WorkType, $SystemEntryDate, $TransactionID, $CustomerID, $TaxPayable, $NetTotal, $GrossTotal)
    {
        $this->DocumentNumber = $DocumentNumber;
        $this->WorkStatus = $WorkStatus;
        $this->WorkStatusDate = $WorkStatusDate;
        $this->SourceID = $SourceID;
        $this->SourceBilling = $SourceBilling;
        $this->Hash = $Hash;
        $this->HashControl = $HashControl;
        $this->Period = $Period;
        $this->WorkDate = $WorkDate;
        $this->WorkType = $WorkType;
        $this->SystemEntryDate = $SystemEntryDate;
        $this->TransactionID = $TransactionID;
        $this->CustomerID = $CustomerID;
        $this->TaxPayable = $TaxPayable;
        $this->NetTotal = $NetTotal;
        $this->GrossTotal = $GrossTotal;
    }


    public function addInvoiceLine($invoiceLine){
        $this->WorkLines[] = $invoiceLine;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('WorkDocument');

        $xmlWriter->startElement('DocumentNumber');
        $xmlWriter->text($this->DocumentNumber);
        $xmlWriter->endElement();

        $xmlWriter->startElement('DocumentStatus');
        $xmlWriter->startElement('WorkStatus');
        $xmlWriter->text($this->WorkStatus);
        $xmlWriter->endElement();
        $xmlWriter->startElement('WorkStatusDate');
        $xmlWriter->text($this->WorkStatusDate);
        $xmlWriter->endElement();
        $xmlWriter->startElement('SourceID');
        $xmlWriter->text($this->SourceID);
        $xmlWriter->endElement();
        $xmlWriter->startElement('SourceBilling');
        $xmlWriter->text($this->SourceBilling);
        $xmlWriter->endElement();
        $xmlWriter->endElement();//End DocumentStatus

        $xmlWriter->startElement('Hash');
        $xmlWriter->text($this->Hash);
        $xmlWriter->endElement();

        $xmlWriter->startElement('HashControl');
        $xmlWriter->text($this->HashControl);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Period');
        $xmlWriter->text($this->Period);
        $xmlWriter->endElement();

        $xmlWriter->startElement('WorkDate');
        $xmlWriter->text($this->WorkDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('WorkType');
        $xmlWriter->text($this->WorkType);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SourceID');
        $xmlWriter->text($this->SourceID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SystemEntryDate');
        $xmlWriter->text($this->SystemEntryDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TransactionID');
        $xmlWriter->text($this->TransactionID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CustomerID');
        $xmlWriter->text($this->CustomerID);
        $xmlWriter->endElement();

        foreach ($this->WorkLines as $workLine){
            $xmlWriter->writeRaw($workLine->toXML($xmlWriter));
        }
        $xmlWriter->startElement('DocumentTotals');
        $xmlWriter->startElement('TaxPayable');
        $xmlWriter->text($this->TaxPayable);
        $xmlWriter->endElement();
        $xmlWriter->startElement('NetTotal');
        $xmlWriter->text($this->NetTotal);
        $xmlWriter->endElement();
        $xmlWriter->startElement('GrossTotal');
        $xmlWriter->text($this->GrossTotal);
        $xmlWriter->endElement();
        $xmlWriter->endElement();//End DocumentTotals
        $xmlWriter->endElement();//End WorkDocument
        return $xmlWriter->outputMemory(true);

    }

}
