<?php

namespace App\Domain\Entity\Empresa;

class TaxasAeronauticaByPMD
{
    protected $id;
    protected $toneladasMin;
    protected $toneladasMax;
    protected $taxa;

    public function __construct($toneladasMin, $toneladasMax, $taxa) {
        $this->id = $id;
        $this->toneladasMin = $toneladasMin;
        $this->toneladasMax = $toneladasMax;
        $this->taxa = $taxa;
    }

    public function calcularTaxa($toneladas) {
        if ($toneladas >= $this->toneladasMin && $toneladas <= $this->toneladasMax) {
            return $this->taxa;
        }
        return 0; // Retorna 0 se não houver correspondência
    }
}
