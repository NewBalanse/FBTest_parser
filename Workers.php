<?php

class Workers extends Threaded
{
    /**
     *
     */
    public function run()
    {
        do {
            //echo "</br>Workers run</br>";
            $result = null;
            $provider = $this->worker->getDataProvider();

            $provider->synchronized(function ($provider) use (&$value) {
                $value = $provider->getNext();
                //echo "</br>". $value ."</br>";
            }, $provider);

            if ($value === null)
                continue;


        } while ($value != null);

    }

}