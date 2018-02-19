<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 05.02.2018
 * Time: 19:04
 */
class LlistCity
{
    private $id;
    private $CityName;

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
    public function getCityName()
    {
        return $this->CityName;
    }

    /**
     * @param mixed $CityName
     */
    public function setCityName($CityName)
    {
        $this->CityName = $CityName;
    }

}