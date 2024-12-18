<?php

namespace App\Domain\Entity\Empresa;

class Categoria
{

    private $categoria_pai;
    private $designacao;
    private $status_id;

    public function __construct($designacao, $categoria_pai, $status_id)
    {
        $this->categoria_pai = $categoria_pai;
        $this->designacao = $designacao;
        $this->status_id = $status_id;
    }

    /**
     * @return mixed
     */
    public function getCategoriaPai()
    {
        return $this->categoria_pai;
    }

    /**
     * @return mixed
     */
    public function getDesignacao()
    {
        return $this->designacao;
    }

    /**
     * @return mixed
     */
    public function getStatusId()
    {
        return $this->status_id;
    }



}
