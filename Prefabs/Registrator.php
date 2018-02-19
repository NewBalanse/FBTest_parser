<?php
require_once "vendor/autoload.php";

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Registrator
{
    private $hostSelenium = null;
    private $driver = null;
    private $url = "https://passport.i.ua/";
    private $countEmailConCat;

    /*
    $browser_type
    # :firefox => firefox
    # :chrome =>chrome
    # :ie => iexplore
    #*/

    public function ReturnDriver(){
        return $this->driver;
    }
    function __construct()
    {
        try {
            $this->hostSelenium = "http://localhost:4444/wd/hub";
            $this->countEmailConCat = 0;

            $this->driver = RemoteWebDriver::create($this->hostSelenium,
                array(
                    "platform" => "WIN10",
                    "browserName" => "chrome",
                    "version" => "64"
                ), 120000);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    public function GoToURL()
    {
        $this->driver->manage()->deleteAllCookies();
        echo "Delete cookies\n";
        $this->driver->navigate()->to($this->url);
        sleep(2);
    }

    public function RegistrationCompleat()
    {
        try {
            return null;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function NextStepRegistration()
    {
        try {
            $this->driver->findElement(WebDriverBy::cssSelector("body > div.Body.clear > form > table:nth-child(11) > tbody > tr:nth-child(1) > td:nth-child(2) > div > input"))
                ->sendKeys("Alan");

            $this->Find_SelectOptions("body > div.Body.clear > form > table:nth-child(11) > tbody > tr:nth-child(3) > td:nth-child(2) > div > select",
                "1");
            $this->Find_SelectOptions("body > div.Body.clear > form > table:nth-child(11) > tbody > tr:nth-child(4) > td:nth-child(2) > div > select:nth-child(1)",
                "6");
            $this->Find_SelectOptions("body > div.Body.clear > form > table:nth-child(11) > tbody > tr:nth-child(4) > td:nth-child(2) > div > select:nth-child(2)",
                "5");

            $this->driver->findElement(WebDriverBy::cssSelector("body > div.Body.clear > form > table:nth-child(11) > tbody > tr:nth-child(4) > td:nth-child(2) > div > input[type=\"text\"]"))
                ->sendKeys("1998");
            sleep(5);
            $this->Find_SelectOptions("body > div.Body.clear > form > table:nth-child(11) > tbody > tr:nth-child(5) > td:nth-child(2) > div > select",
                "10000");

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }

    private function Find_SelectOptions($LinkSelected,$selectedOptions){
        try{
            $SelectedMale = $this->driver->findElement(WebDriverBy::cssSelector($LinkSelected));
            if ($SelectedMale) {
                $Options = $SelectedMale->findElement(WebDriverBy::tagName("options"));
                foreach ($Options as $item) {
                    if ($item->getArteibute('value') == $selectedOptions) {
                        $item->click();
                    }
                }
            }
        }catch (Exception $exception){
            throw $exception;
        }
    }
    public function EnterRegistrationForm()
    {
        $isOnse = true;
        try {

            do {
                $Error = false;
                $ElementEmailLog = null;
                #reg_login > td:nth-child(2) > div > input:nth-child(1)
                #reg_login > td:nth-child(2) > div > input:nth-child(2)
                #reg_login > td:nth-child(2) > div > input:nth-child(3)
                if ($isOnse) {
                    if ($this->FindElementLog("#reg_login > td:nth-child(2) > div > input:nth-child(") != null) {
                        $ElementEmailLog = $this->FindElementLog("#reg_login > td:nth-child(2) > div > input:nth-child(");
                        $isOnse = false;
                    }
                }
                $ElementEmailLog->sendKeys(self::getEmailAdress($this->countEmailConCat++));

                sleep(5);

                if ($this->getErrorEmail())
                    $Error = false;
                else
                    $Error = true;

            } while (!$Error);
            #reg_form > tr:nth-child(4) > td:nth-child(2) > div > input:nth-child(1)
            #reg_form > tr:nth-child(4) > td:nth-child(2) > div > input:nth-child(2)
            #reg_form > tr:nth-child(4) > td:nth-child(2) > div > input:nth-child(3)
            if ($this->FindElementLog("#reg_form > tr:nth-child(4) > td:nth-child(2) > div > input:nth-child(") != null) {
                $this->FindElementLog("#reg_form > tr:nth-child(4) > td:nth-child(2) > div > input:nth-child(")->sendKeys("X2yhsg3j7a9E3159");
            }
            #reg_pconf > td:nth-child(2) > div > input:nth-child(1)
            #reg_pconf > td:nth-child(2) > div > input:nth-child(2)
            #reg_pconf > td:nth-child(2) > div > input:nth-child(3)
            if ($this->FindElementLog("#reg_pconf > td:nth-child(2) > div > input:nth-child(") != null) {
                $this->FindElementLog("#reg_pconf > td:nth-child(2) > div > input:nth-child(")->sendKeys("X2yhsg3j7a9E3159");
            }

            try {
                //05AMHTQt8P3V7geEyDHtG9XIi3560AdwZm6Jzye-6fE-BeAiDUuVdPOkdSRH0p7-KsxjbWWL9f8w
                //rc::b
                $EEE = $this->driver->findElement(WebDriverBy::cssSelector("body > div.rc-anchor.rc-anchor-normal.rc-anchor-light"));
            } catch (Exception $exception) {
                throw $exception;
            }


        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }

    private
    function FindElementLog($linkElement)
    {
        for ($i = 1; $i < 4; $i++) {
            $TextStyle = $this->driver->
            findElement(WebDriverBy::cssSelector($linkElement . $i . ")"))
                ->getAttribute("style");
            if (empty($TextStyle)) {
                return $ElementLogg = $this->driver->findElement(WebDriverBy::cssSelector($linkElement . $i . ")"));
            }
        }
        return null;
    }

    private
    static function getEmailAdress($count)
    {
        return "botFBTestAcc." . $count;
    }

    private
    function getErrorEmail()
    {
        echo "1\n";
        $Element = null;
        try {
            if ($this->driver->findElement(WebDriverBy::className("message"))->getAttribute("style")) {
                echo "2\n";
                $Element = $this->driver->findElement(WebDriverBy::className("message"))->getText();
                $Element = substr($Element, -7);
                echo "3\n";
                if ($Element == "вільний")
                    return true;
            } else
                return false;
        } catch (Exception $exception) {
            if ($exception->getCode() == 0) {
                if ($this->driver->findElement(WebDriverBy::className("error"))->getAttribute("style")) {
                    $Element = $this->driver->findElement(WebDriverBy::className("error"))->getText();
                    $Element = substr($Element, -8);
                    echo "3\n";
                    if ($Element == "зайнятий")
                        return false;
                }
            }
        }

        return false;
    }


}


