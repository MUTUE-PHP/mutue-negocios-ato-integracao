<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class TaxTableEntry
{
    private $taxType;
    private $taxCode;
    private $description;
    private $taxPercentage;

    public function __construct($taxType, $taxCode, $description, $taxPercentage)
    {
        $this->taxType = $taxType;
        $this->taxCode = $taxCode;
        $this->description = $description;
        $this->taxPercentage = $taxPercentage;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('TaxTableEntry');

        $xmlWriter->startElement('TaxType');
        $xmlWriter->text($this->taxType);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxCode');
        $xmlWriter->text($this->taxCode);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Description');
        $xmlWriter->text($this->description);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxPercentage');
        $xmlWriter->text($this->taxPercentage);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
        return $xmlWriter->outputMemory(true);
    }


}
