<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class TaxTable
{
    private $taxTableEntries = [];
    public function addTaxTableEntry($taxTableEntry){
        $this->taxTableEntries[] = $taxTableEntry;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('TaxTable');

        foreach ($this->taxTableEntries as $taxTableEntry) {
            $xmlWriter->writeRaw($taxTableEntry->toXML($xmlWriter));
        }

        $xmlWriter->endElement(); // TaxTable
        return $xmlWriter->outputMemory(true);
    }
}
