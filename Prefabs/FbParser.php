<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 15.02.2018
 * Time: 14:04
 */
class FbParser
{
    private $url = "https://www.facebook.com";//facebook url
    private $hostSelenium = null;
    private $driver = null;
    private $_islogIn = false;

    function __construct($driver = null)
    {
        if ($driver != null) {
            $this->driver = $driver;
        } else {
            $this->hostSelenium = "http://localhost:4444/wd/hub";
            $this->driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create(
                $this->hostSelenium,
                array(
                    'platform' => "WIN10",
                    'browserName' => "chrome",
                    'version' => "64"
                ),
                120000
            );
        }
    }

    private function WaitElementVisible($driver, $webDriverBy, $timeout = 10, $interval = 250, $message = "TIME_OUT")
    {
        $end = microtime(true) + $timeout;
        $last_exception = null;
        while ($end > microtime(true)) {
            try {
                $driver->wait($timeout)->until(
                    \Facebook\WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated($webDriverBy)
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

    public
    function LogInFb($mail = "coocies12@gmail.com", $pass = "X2yhsg3j7a9E3159")
    {
        try {
            $isLoadingPage = false;
            try {
                $this->driver->navigate()->to($this->url);
                $isLoadingPage = $this->WaitElementVisible($this->driver,
                    \Facebook\WebDriver\WebDriverBy::cssSelector("#login_form > table > tbody > tr:nth-child(1) > td:nth-child(1) > label"));
            } catch (Exception $e) {
                echo $e;
                #$msg = $e->getMessage();
                #echo "_Exception || msg=[[\n$msg\n]]";
                exit;
            }
            if ($isLoadingPage == true) {

                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#email"))->sendKeys($mail);
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#pass"))->sendKeys($pass);

                $btn_LogIn = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#loginbutton"));

                echo $btn_LogIn->getAttribute("for");
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#" . $btn_LogIn->getAttribute("for")))->click();

                $Elemnt = $this->Search_id_ElemetVerifi();
                #js_5 > div > div > div._2t-e > div:nth-child(1)
                //#js_9 > div > div > div._2t-e > div:nth-child(1)
                if ($this->WaitElementVisible($this->driver,
                    \Facebook\WebDriver\WebDriverBy::cssSelector("#" . $Elemnt . " > div > div > div._2t-e > div:nth-child(1)"))
                ) {

                    return true;
                }

            }

            return false;
        } catch (Exception $exception) {
            #js_6 > div > div > div._2t-e > div:nth-child(1)
            $EmelentVerifi = $this->Search_id_ElemetVerifi();
            if ($this->WaitElementVisible($this->driver,
                \Facebook\WebDriver\WebDriverBy::cssSelector("#" . $EmelentVerifi . " > div > div > div._2t-e > div:nth-child(1)"))
            ) {
                return true;
            } else
                throw new Exception("Log Error");
        }
    }

private
function Search_id_ElemetVerifi()
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

private
function GoToUrlFind_PoplePlusCity($People_name, $City_name)
{
    $FindsUrl = "https://www.facebook.com/search/str/" . $People_name . "+" . $City_name . "/keywords_users";
    $this->driver->navigate()->to($FindsUrl);

    $Element = $this->Search_id_ElemetVerifi();
    if ($this->WaitElementVisible($this->driver,
        \Facebook\WebDriver\WebDriverBy::cssSelector("#" . $Element . " > div > div > div._2t-e > div:nth-child(1)"))
    ) {
        return true;
    }
    return false;
}

public
function SearchPeople($ArrayPeople = [], $ArrayCity = [])
{
    if ($this->GoToUrlFind_PoplePlusCity($ArrayPeople[0], $ArrayCity[0])) {
        $Array_result = null;

        //#BrowseResultsContainer > div:nth-child(1)
        //#BrowseResultsContainer > div:nth-child(1)
        #u_ps_0_3_8 > div > div > div > div.clearfix > div._2mch._gll > div > div > div > a
        //#BrowseResultsContainer > div:nth-child(2)
        #u_ps_0_3_d > div > div > div > div.clearfix > div._2mch._gll > div > div > div > a

        //#u_ps_0_3_0_browse_result_below_fold > div > div:nth-child(1)

        //#fbBrowseScrollingPagerContainer0 > div > div:nth-child(1)

        //#fbBrowseScrollingPagerContainer1 > div > div:nth-child(1)

        //#fbBrowseScrollingPagerContainer2 > div > div:nth-child(1)

        $Array_result = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::className("#BrowseResultsContainer > div:nth-child(1)"));
        if (is_array($Array_result))
            var_dump($Array_result);
        else
            echo $Array_result;
    }
}

public
function ClickPeople()
{

}

public
function GetInfoPeople()
{

}

public
function GetIsLogIn()
{
    return false;
}

}