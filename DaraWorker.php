<?php
/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 04.02.2018
 * Time: 18:11
 */
class DaraWorker extends Worker
{
    private $dataProvider;

    function __construct(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        //echo "</br>Dara construct</br>";
    }

    public function run()
    {

    }

    /**
     * @return DataProvider
     */
    public function getDataProvider()
    {
        //echo "</br>get Data Provider</br>";
        return $this->dataProvider;
    }
}