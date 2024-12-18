<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class CreditNotesLine
{
    private $LineNumber;
    private $ProductCode;
    private $ProductDescription;
    private $Quantity;
    private $UnitOfMeasure;
    private $UnitPrice;
    private $TaxPointDate;
    private $Reference;
    private $Reason;
    private $Description;
    private $DebitAmount;
    private $TaxType;
    private $TaxCountryRegion;
    private $TaxCode;
    private $TaxPercentage;
    private $TaxExemptionReason;
    private $TaxExemptionCode;
    private $SettlementAmount;

    public function __construct($LineNumber, $ProductCode, $ProductDescription, $Quantity, $UnitOfMeasure, $UnitPrice, $TaxPointDate, $Reference, $Reason, $Description, $DebitAmount, $TaxType, $TaxCountryRegion, $TaxCode, $TaxPercentage, $TaxExemptionReason, $TaxExemptionCode, $SettlementAmount)
    {
        $this->LineNumber = $LineNumber;
        $this->ProductCode = $ProductCode;
        $this->ProductDescription = $ProductDescription;
        $this->Quantity = $Quantity;
        $this->UnitOfMeasure = $UnitOfMeasure;
        $this->UnitPrice = $UnitPrice;
        $this->TaxPointDate = $TaxPointDate;
        $this->Reference = $Reference;
        $this->Reason = $Reason;
        $this->Description = $Description;
        $this->DebitAmount = $DebitAmount;
        $this->TaxType = $TaxType;
        $this->TaxCountryRegion = $TaxCountryRegion;
        $this->TaxCode = $TaxCode;
        $this->TaxPercentage = $TaxPercentage;
        $this->TaxExemptionReason = $TaxExemptionReason;
        $this->TaxExemptionCode = $TaxExemptionCode;
        $this->SettlementAmount = $SettlementAmount;
    }


    public function toXML()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);


        $xmlWriter->startElement('Line');

        $xmlWriter->startElement('LineNumber');
        $xmlWriter->text($this->LineNumber);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ProductCode');
        $xmlWriter->text($this->ProductCode);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ProductDescription');
        $xmlWriter->text($this->ProductDescription);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Quantity');
        $xmlWriter->text($this->Quantity);
        $xmlWriter->endElement();

        $xmlWriter->startElement('UnitOfMeasure');
        $xmlWriter->text($this->UnitOfMeasure);
        $xmlWriter->endElement();

        $xmlWriter->startElement('UnitPrice');
        $xmlWriter->text($this->UnitPrice);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxPointDate');
        $xmlWriter->text($this->TaxPointDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('References');
        $xmlWriter->startElement('Reference');
        $xmlWriter->text($this->Reference);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Reason');
        $xmlWriter->text($this->Reason);
        $xmlWriter->endElement();
        $xmlWriter->endElement();//End References

        $xmlWriter->startElement('Description');
        $xmlWriter->text($this->Description);
        $xmlWriter->endElement();

        $xmlWriter->startElement('DebitAmount');
        $xmlWriter->text($this->DebitAmount);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Tax');

        $xmlWriter->startElement('TaxType');
        $xmlWriter->text($this->TaxType);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxCountryRegion');
        $xmlWriter->text($this->TaxCountryRegion);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxCode');
        $xmlWriter->text($this->TaxCode);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxPercentage');
        $xmlWriter->text($this->TaxPercentage);
        $xmlWriter->endElement();
        $xmlWriter->endElement();//End Tax

        if ($this->TaxPercentage <= 0) {
            $xmlWriter->startElement('TaxExemptionReason');
            $xmlWriter->text($this->TaxExemptionReason);
            $xmlWriter->endElement();

            $xmlWriter->startElement('TaxExemptionCode');
            $xmlWriter->text($this->TaxExemptionCode);
            $xmlWriter->endElement();

            $xmlWriter->startElement('SettlementAmount');
            $xmlWriter->text($this->SettlementAmount);
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement();//End Line
        return $xmlWriter->outputMemory(true);
    }
}
