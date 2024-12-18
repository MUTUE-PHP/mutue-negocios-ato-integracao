<?php

namespace App\Domain\Entity\Admin;

class FormaPagamento
{

    private $id;
    private $designacao;

    /**
     * @param $id
     * @param $designacao
     */
    public function __construct($id, $designacao)
    {
        $this->id = $id;
        $this->designacao = $designacao;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }



}
