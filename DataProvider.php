<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 04.02.2018
 * Time: 18:05
 */
class DataProvider extends Threaded
{
    /**
     * @var int count elements in database
     */

    private $countElementsInDataBase = 20000;

    /**
     * @var int количество обработаных елементов
     */

    private $processed = 0;

    /**
     * Переход к следушому елементу и обработка его
     *
     * @return mixed
     */
    public function getNext()
    {
        //echo "Get next</br>";
        if ($this->processed === $this->countElementsInDataBase) {
            return null;
        }

        $this->processed++;
        return $this->processed;

    }
}