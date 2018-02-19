<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 11.02.2018
 * Time: 2:55
 */
class FBRegistration
{
    private $url = "https://www.facebook.com";
    private $hostSelenium = null;
    private $driver = null;

    function __construct($driver = null)
    {

        try {
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
                    )
                    , 120000);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function navigateToRegistrationFB()
    {
        $this->driver->manage()->deleteAllCookies();
        $this->driver->navigate()->to($this->url);
    }

    public function EnterRegistrationForm()
    {
        try {
            //name
            $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_l"))
                ->sendKeys("Alan");
            //sure name
            $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_n"))
                ->sendKeys("Kertch");
            //@mail box
            $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_q"))
                ->sendKeys("coocies12@gmail.com");
            //mail again
            $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_x"))->click();
            sleep(3);
            $MailAgain =
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_t"));
            if ($MailAgain->isDisplayed()) {
                echo "Displayed";
                $MailAgain->sendKeys("coocies12@gmail.com");
            }
            //password
            $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_x"))
                ->sendKeys("X2yhsg3j7a9E3159");
            //selected Male/Famale
            $ElementsForSelected =
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::xpath("//*[@id=\"u_0_11\"]/span[2]"));
            if ($ElementsForSelected) {
                $ElementsForSelected->click();
                sleep(3);
            }
            // clicked button submit registration
            $btn_submit_registrations =
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#u_0_13"));
            if ($btn_submit_registrations)
                $btn_submit_registrations->click();
            sleep(5);

            if ($this->IsRegistrationCompleate()) {
                echo "REgistration true";
                sleep(3);
                $this->ClickYesBtnDayBird();
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function IsRegistrationCompleate()
    {
        try {
            $Element_IFRegistration =
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::xpath("//*[@id=\"u_0_5\"]"))
                    ->getText();
            if (!empty($Element_IFRegistration)) {
                return true;
            } else {
                $Element_IFRegistration =
                    $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::xpath("//*[@id=\"u_0_2\"]"))
                        ->getText();
                if (!empty($Element_IFRegistration))
                    return true;
            }
            return false;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function ClickYesBtnDayBird()
    {
        try {
            $btn_click =
                $this->driver->findElement(
                    \Facebook\WebDriver\WebDriverBy::xpath("//*[@id=\"facebook\"]/body/div[3]/div[2]/div/div/div/div[3]/button")
                );
            if ($btn_click)
                $btn_click->click();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function RegistrationComplite()
    {
        $Compleat = "";
        if ($Compleat) {
            return true;
        }
        return false;
    }

}