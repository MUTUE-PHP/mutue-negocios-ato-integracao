<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class CompanyAddress
{
    private $addressDetail;
    private $city;
    private $province;
    private $country;

    public function __construct($addressDetail, $city, $province, $country)
    {
        $this->addressDetail = $addressDetail ?? "Desconhecido";
        $this->city = $city;
        $this->province = $province;
        $this->country = $country;
    }

    public function toXML(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('CompanyAddress');
        $xmlWriter->startElement('AddressDetail');
        $xmlWriter->text($this->addressDetail);
        $xmlWriter->endElement();

        $xmlWriter->startElement('City');
        $xmlWriter->text($this->city);
        $xmlWriter->endElement();

        if ($this->province) {
            $xmlWriter->startElement('Province');
            $xmlWriter->text($this->province);
            $xmlWriter->endElement();
        }

        $xmlWriter->startElement('Country');
        $xmlWriter->text($this->country);
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // CompanyAddress
    }

}
