<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 01.03.2018
 * Time: 15:39
 */
class DriverHelper
{
    private $driver = null;
    private $host = null;

    function __construct()
    {
        try {
            $this->host = "http://localhost:4444/wd/hub";
            $this->driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create(
                $this->host,
                array(
                    'platform' => "WIN10",
                    'browserName' => "chrome",
                    'version' => "64"
                ),
                120000
            );
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function ReturnDriver()
    {
        try {
            if ($this->driver != null)
                return $this->driver;
            else
                throw new Exception("Exception returned driver in if!::");
        } catch (Exception $exception) {
            throw new Exception("Exception returned driver!");
        }
    }

    public function WaitElementVisible($driver, $WebDriverBySelector, $timeout = 10, $interval = 250, $message = "TIME_OUT")
    {
        $end_time = microtime(true) + $timeout;
        $last_exception = null;
        while ($end_time > microtime(true)) {
            try {

                $driver->wait($timeout)->until(
                    \Facebook\WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated($WebDriverBySelector)
                );
                return true;
            } catch (Exception $exception) {
                $last_exception = $exception;
            }
            usleep($interval * 1000);
        }
        if ($last_exception)
            throw $last_exception;
        throw new \Facebook\WebDriver\Exception\TimeOutException($message);
    }

    public function SearchIdElementVerify()
    {
        for ($i = 0; $i < 10; $i++) {
            try {
                if ($this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#js_" . $i))->getAttribute("role") == "banner") {
                    $Elemnt = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#js_" . $i))->getAttribute("id");
                    return $Elemnt;
                }
            } catch (Exception $e) {
                //echo "\nError element id";
                //echo "\nElement id: " . $i . "\n";
                continue;
            }
        }
        return null;
    }
}