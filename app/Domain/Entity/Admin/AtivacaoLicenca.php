<?php

namespace App\Domain\Entity\Admin;

class AtivacaoLicenca
{
    private $licencaId;
    private $empresaId;
    private $pagamentoId;
    private $dataInicio;
    private $dataFim;
    private $dataActivacao;
    private $userId;
    private $operador;
    private $statusLicencaId;
    private $observacao;

    /**
     * @param $licencaId
     * @param $empresaId
     * @param $pagamentoId
     * @param $dataInicio
     * @param $dataFim
     * @param $dataActivacao
     * @param $userId
     * @param $operador
     * @param $statusLicencaId
     * @param $dataRejeicao
     * @param $observacao
     */
    public function __construct($licencaId, $empresaId, $pagamentoId, $dataInicio, $dataFim, $dataActivacao, $userId, $operador, $statusLicencaId, $observacao)
    {
        $this->licencaId = $licencaId;
        $this->empresaId = $empresaId;
        $this->pagamentoId = $pagamentoId;
        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
        $this->dataActivacao = $dataActivacao;
        $this->userId = $userId;
        $this->operador = $operador;
        $this->statusLicencaId = $statusLicencaId;
        $this->observacao = $observacao;
    }

    /**
     * @return mixed
     */
    public function getLicencaId()
    {
        return $this->licencaId;
    }

    /**
     * @return mixed
     */
    public function getEmpresaId()
    {
        return $this->empresaId;
    }

    /**
     * @return mixed
     */
    public function getPagamentoId()
    {
        return $this->pagamentoId;
    }

    /**
     * @return mixed
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    /**
     * @return mixed
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    /**
     * @return mixed
     */
    public function getDataActivacao()
    {
        return $this->dataActivacao;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getOperador()
    {
        return $this->operador;
    }

    /**
     * @return mixed
     */
    public function getStatusLicencaId()
    {
        return $this->statusLicencaId;
    }

    /**
     * @return mixed
     */


    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }




}
