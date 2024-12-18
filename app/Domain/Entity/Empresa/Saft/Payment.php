<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class Payment
{
    private $PaymentRefNo;
    private $Period;
    private $TransactionDate;
    private $PaymentType;
    private $SystemID;
    private $PaymentStatus;
    private $PaymentStatusDate;
    private $SourceID;
    private $SourcePayment;
    private $PaymentAmount;
    private $PaymentDate;
    private $SystemEntryDate;
    private $CustomerID;
    private $TaxPayable;
    private $NetTotal;
    private $GrossTotal;
    private $PaymentLine;

    public function __construct($PaymentRefNo, $Period, $TransactionDate, $PaymentType, $SystemID, $PaymentStatus, $PaymentStatusDate, $SourceID, $SourcePayment, $PaymentAmount, $PaymentDate, $SystemEntryDate, $CustomerID, $TaxPayable, $NetTotal, $GrossTotal)
    {
        $this->PaymentRefNo = $PaymentRefNo;
        $this->Period = $Period;
        $this->TransactionDate = $TransactionDate;
        $this->PaymentType = $PaymentType;
        $this->SystemID = $SystemID;
        $this->PaymentStatus = $PaymentStatus;
        $this->PaymentStatusDate = $PaymentStatusDate;
        $this->SourceID = $SourceID;
        $this->SourcePayment = $SourcePayment;
        $this->PaymentAmount = $PaymentAmount;
        $this->PaymentDate = $PaymentDate;
        $this->SystemEntryDate = $SystemEntryDate;
        $this->CustomerID = $CustomerID;
        $this->TaxPayable = $TaxPayable;
        $this->NetTotal = $NetTotal;
        $this->GrossTotal = $GrossTotal;
    }


    public function addPaymentLine($paymentLine){
        $this->PaymentLine = $paymentLine;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('Payment');

        $xmlWriter->startElement('PaymentRefNo');
        $xmlWriter->text($this->PaymentRefNo);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Period');
        $xmlWriter->text($this->Period);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TransactionDate');
        $xmlWriter->text($this->TransactionDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('PaymentType');
        $xmlWriter->text($this->PaymentType);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SystemID');
        $xmlWriter->text($this->SystemID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('DocumentStatus');
                $xmlWriter->startElement('PaymentStatus');
                $xmlWriter->text($this->PaymentStatus);
                $xmlWriter->endElement();
                $xmlWriter->startElement('PaymentStatusDate');
                $xmlWriter->text($this->PaymentStatusDate);
                $xmlWriter->endElement();
                $xmlWriter->startElement('SourceID');
                $xmlWriter->text($this->SourceID);
                $xmlWriter->endElement();
                $xmlWriter->startElement('SourcePayment');
                $xmlWriter->text($this->SourcePayment);
                $xmlWriter->endElement();
        $xmlWriter->endElement();//End DocumentStatus

        $xmlWriter->startElement('PaymentMethod');
            $xmlWriter->startElement('PaymentAmount');
            $xmlWriter->text($this->PaymentAmount);
            $xmlWriter->endElement();
            $xmlWriter->startElement('PaymentDate');
            $xmlWriter->text($this->PaymentDate);
            $xmlWriter->endElement();
        $xmlWriter->endElement();//End PaymentMethod

        $xmlWriter->startElement('SourceID');
        $xmlWriter->text($this->SourceID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SystemEntryDate');
        $xmlWriter->text($this->SystemEntryDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CustomerID');
        $xmlWriter->text($this->CustomerID);
        $xmlWriter->endElement();

        $xmlWriter->writeRaw($this->PaymentLine->toXML());

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
        $xmlWriter->endElement();//End Payment
        return $xmlWriter->outputMemory(true);

    }

}
