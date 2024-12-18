<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class BillingAddress
{

    private $addressDetail;
    private $city;
    private $country;

    public function __construct($addressDetail, $city = 'Luanda', $country = 'AO')
    {
        $this->addressDetail = $addressDetail ?? "Desconhecido";
        $this->city = $city;
        $this->country = $country;
    }

    public function toXML()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('BillingAddress');

        $xmlWriter->startElement('AddressDetail');
        $xmlWriter->text($this->addressDetail);
        $xmlWriter->endElement();
        $xmlWriter->startElement('City');
        $xmlWriter->text($this->city);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Country');
        $xmlWriter->text($this->country);
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        return $xmlWriter->outputMemory(true);
    }
}
