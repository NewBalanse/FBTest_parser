<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 01.03.2018
 * Time: 15:30
 */
require_once "DriverHelper.php";
class ComponentPeople
{
    private $driver = null;
    private $SeleniumHost = null;
    private $DriverHelper = null;

    function __construct($driver = null, $driverHelper = null)
    {
        if ($driverHelper != null)
            $this->DriverHelper = $driverHelper;
        else {
            $this->DriverHelper = new DriverHelper();
        }

        if ($driver != null)
            $this->driver = $driver;
        else {
            $this->SeleniumHost = "http://localhost:4444/wd/hub";
            $this->driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create(
                $this->SeleniumHost,
                array(
                    'platform' => "WIN10",
                    'browserName' => "chrome",
                    'version' => "64"
                ),
                120000
            );
        }

    }

    public function NavigatePeoplePage($linkPeople, $driver = null)
    {
        try {
            if ($driver != null)
                $this->driver = $driver;
            else {
                $this->driver->navigate()->to($linkPeople);
                $ElementVerify = $this->DriverHelper->SearchIdElementVerify();
                $lineSelector = "#" . $ElementVerify . " > div > div > div._2t-e > div:nth-child(1)";
                if ($this->DriverHelper->WaitElementVisible($this->driver,
                    \Facebook\WebDriver\WebDriverBy::cssSelector($lineSelector))
                ) {
                    $this->ParseInformationPeople();
                }
            }

        } catch (Exception $exception) {
            throw new Exception("Navigate page people exception");
        }
    }

    private function ParseInformationPeople()
    {
        try {
            $link = null;
            $HeadLine = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::id("fbTimelineHeadline"));
            if($HeadLine->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a")))
                $link=$HeadLine->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->getAttribute("href");
            echo $link;
            if($link)
                $HeadLine->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();

            $UL_List_leftNavBar = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::id("info_section_left_nav"));
            $end_operations = false;
            while (!$end_operations){

            }

        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}