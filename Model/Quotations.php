<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 05.02.2018
 * Time: 18:35
 */
class Quotations
{
    private $id;
    private $IdPeople;
    private $Quotations;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdPeople()
    {
        return $this->IdPeople;
    }

    /**
     * @param mixed $IdPeople
     */
    public function setIdPeople($IdPeople)
    {
        $this->IdPeople = $IdPeople;
    }

    /**
     * @return mixed
     */
    public function getQuotations()
    {
        return $this->Quotations;
    }

    /**
     * @param mixed $Quotations
     */
    public function setQuotations($Quotations)
    {
        $this->Quotations = $Quotations;
    }
}