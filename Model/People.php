<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 05.02.2018
 * Time: 18:29
 */
class People
{
    private $id;
    private $Male;
    private $Name;
    private $Last_name;
    private $Works;
    private $City;
    private $Education;

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
    public function getCity()
    {
        return $this->City;
    }

    /**
     * @param mixed $City
     */
    public function setCity($City)
    {
        $this->City = $City;
    }

    /**
     * @return mixed
     */
    public function getEducation()
    {
        return $this->Education;
    }

    /**
     * @param mixed $Education
     */
    public function setEducation($Education)
    {
        $this->Education = $Education;
    }

    /**
     * @return mixed
     */
    public function getWorks()
    {
        return $this->Works;
    }

    /**
     * @param mixed $Works
     */
    public function setWorks($Works)
    {
        $this->Works = $Works;
    }


    /**
     * @return mixed
     */
    public function getMale()
    {
        return $this->Male;
    }

    /**
     * @param mixed $Male
     */
    public function setMale($Male)
    {
        $this->Male = $Male;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param mixed $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->Last_name;
    }

    /**
     * @param mixed $Last_name
     */
    public function setLastName($Last_name)
    {
        $this->Last_name = $Last_name;
    }


}